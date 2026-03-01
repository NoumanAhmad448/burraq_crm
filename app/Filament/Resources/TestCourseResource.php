<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestCourseResource\Pages;
use App\Filament\Resources\TestCourseResource\RelationManagers;
use App\Models\TestCourse;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class TestCourseResource extends Resource
{
    protected static ?string $model = TestCourse::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),

            TextInput::make('price')
                ->numeric()
                ->required()
                ->prefix('Rs') // since you're in Japan 😉
                ->rules(['min:0']),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('price')->money('JPY', true)->sortable(),

                // Total Students
                Tables\Columns\TextColumn::make('student_courses_count')->counts('studentCourses')->label('Students'),

                // Total Expected Revenue
                Tables\Columns\TextColumn::make('expected_revenue')
                    ->label('Expected Revenue')
                    ->getStateUsing(function ($record) {
                        return $record->studentCourses->sum('final_price');
                    })
                    ->money('JPY', true),

                // Total Collected
                Tables\Columns\TextColumn::make('collected')
                    ->label('Collected')
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        return $record->studentCourses->flatMap->payments->sum('amount');
                    })
                    ->money('JPY', true),

                // Outstanding
                Tables\Columns\TextColumn::make('outstanding')
                    ->label('Outstanding')
                    ->color('danger')
                    ->getStateUsing(function ($record) {
                        $expected = $record->studentCourses->sum('final_price');
                        $collected = $record->studentCourses->flatMap->payments->sum('amount');

                        return $expected - $collected;
                    })
                    ->money('JPY', true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['studentCourses.payments'])
            ->withCount('studentCourses');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestCourses::route('/'),
            'create' => Pages\CreateTestCourse::route('/create'),
            'edit' => Pages\EditTestCourse::route('/{record}/edit'),
        ];
    }
}
