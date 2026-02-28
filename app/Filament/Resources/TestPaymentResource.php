<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestPaymentResource\Pages;
use App\Filament\Resources\TestPaymentResource\RelationManagers;
use App\Models\TestPayment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestPaymentResource extends Resource
{
    protected static ?string $model = TestPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('test_student_course_id')
                    ->relationship('studentCourse', 'id')
                    ->required(),

                TextInput::make('amount')->numeric()->required(),

                DatePicker::make('payment_date')->required(),

                FileUpload::make('payment_slip')
                    ->directory('payment-slips')
                    ->disk('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListTestPayments::route('/'),
            'create' => Pages\CreateTestPayment::route('/create'),
            'edit' => Pages\EditTestPayment::route('/{record}/edit'),
        ];
    }    
}
