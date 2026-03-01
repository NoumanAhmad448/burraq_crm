<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestStudentCourseResource\Pages;
use App\Filament\Resources\TestStudentCourseResource\RelationManagers;
use App\Models\TestStudentCourse;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class TestStudentCourseResource extends Resource
{
    protected static ?string $model = TestStudentCourse::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('test_student_id')->relationship('student', 'name')->required(),

            Select::make('test_course_id')
                ->relationship('course', 'name')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $course = \App\Models\TestCourse::find($state);

                    if ($course) {
                        $set('original_price', $course->price);
                        $set('final_price', $course->price);
                    }
                }),

            TextInput::make('original_price')->disabled(),
            DatePicker::make('admission_date')->required(),

            DatePicker::make('due_date')->nullable()->helperText('Optional. Leave empty if no due date.'),

            TextInput::make('coupon_percentage')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $original = $get('original_price');

                    if ($original && $state) {
                        $discount = ($original * $state) / 100;
                        $set('final_price', $original - $discount);
                    }
                }),

            TextInput::make('final_price')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Student Name
                Tables\Columns\TextColumn::make('student.name')->label('Student')->searchable()->sortable(),

                // Course Name
                Tables\Columns\TextColumn::make('course.name')->label('Course')->searchable()->sortable(),

                // Admission Date
                Tables\Columns\TextColumn::make('admission_date')->date()->sortable(),

                // Due Date
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->placeholder('No Due')
                    ->color(fn ($record) =>
                        filled($record->due_date) && now()->gt($record->due_date)
                            ? 'danger'
                            : null
                    ),
                // Original Price
                Tables\Columns\TextColumn::make('original_price')->money(config("names.currency"), true)->label('Original'),

                // Coupon %
                Tables\Columns\TextColumn::make('coupon_percentage')->label('Coupon %')->placeholder('-'),

                // Final Price
                Tables\Columns\TextColumn::make('final_price')->money(config("names.currency"), true)->label('Final'),

                // Total Paid
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Paid')
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        return $record->payments->sum('amount');
                    })
                    ->money(config("names.currency"), true),

                // Outstanding
                Tables\Columns\TextColumn::make('outstanding')
                    ->label('Outstanding')
                    ->color('danger')
                    ->getStateUsing(function ($record) {
                        $paid = $record->payments->sum('amount');
                        return $record->final_price - $paid;
                    })
                    ->money(config("names.currency"), true),

                // Status
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'success' => 'active',
                    'primary' => 'completed',
                    'danger' => 'cancelled',
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),

                Tables\Filters\Filter::make('has_due')->label('Has Outstanding')->query(
                    fn(Builder $query) => $query->whereRaw('final_price > (
                        SELECT COALESCE(SUM(amount),0)
                        FROM test_payments
                        WHERE test_payments.test_student_course_id = test_student_courses.id
                    )'),
                ),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestStudentCourses::route('/'),
            'create' => Pages\CreateTestStudentCourse::route('/create'),
            'edit' => Pages\EditTestStudentCourse::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->with(['student', 'course', 'payments'])
        ->withCount('payments');
}
}
