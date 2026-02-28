<?php

namespace App\Filament\Resources\TestPaymentResource\Pages;

use App\Filament\Resources\TestPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestPayments extends ListRecords
{
    protected static string $resource = TestPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
