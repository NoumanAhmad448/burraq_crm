<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestStudentResource\Pages;
use App\Filament\Resources\TestStudentResource\RelationManagers;
use App\Models\TestStudent;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestStudentResource extends Resource
{
    protected static ?string $model = TestStudent::class;
    protected static ?string $slug = 'students';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(), 
            Forms\Components\TextInput::make('mobile')->required(),
            Forms\Components\FileUpload::make('profile_photo')->image()->directory('students')->disk('public')]);
    }


public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('profile_photo')
                ->disk('public')
                ->circular(),

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('email')
                ->searchable(),

            Tables\Columns\TextColumn::make('mobile'),

            // 🔥 Total Enrollments
            Tables\Columns\TextColumn::make('student_courses_count')
                ->counts('studentCourses')
                ->label('Courses'),

            // 🔥 Total Paid
            Tables\Columns\TextColumn::make('total_paid')
                ->label('Total Paid')
                ->getStateUsing(function ($record) {
                    return $record->studentCourses
                        ->flatMap->payments
                        ->sum('amount');
                }),

            // 🔥 Total Outstanding
            Tables\Columns\TextColumn::make('outstanding')
                ->label('Outstanding')
                ->color('danger')
                ->getStateUsing(function ($record) {
                    $totalCourseAmount = $record->studentCourses->sum('final_price');
                    $totalPaid = $record->studentCourses
                        ->flatMap->payments
                        ->sum('amount');

                    return $totalCourseAmount - $totalPaid;
                }),
        ])
        ->defaultSort('created_at', 'desc')
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
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
            'index' => Pages\ListTestStudents::route('/'),
            'create' => Pages\CreateTestStudent::route('/create'),
            'edit' => Pages\EditTestStudent::route('/{record}/edit'),
        ];
    }
}
