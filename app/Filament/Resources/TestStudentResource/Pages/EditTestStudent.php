<?php

namespace App\Filament\Resources\TestStudentResource\Pages;

use App\Filament\Resources\TestStudentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestStudent extends EditRecord
{
    protected static string $resource = TestStudentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
