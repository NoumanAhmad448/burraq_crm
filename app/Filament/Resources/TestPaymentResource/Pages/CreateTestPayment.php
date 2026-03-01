<?php

namespace App\Filament\Resources\TestPaymentResource\Pages;

use App\Filament\Resources\TestPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestPayment extends CreateRecord
{
    protected static string $resource = TestPaymentResource::class;
}
