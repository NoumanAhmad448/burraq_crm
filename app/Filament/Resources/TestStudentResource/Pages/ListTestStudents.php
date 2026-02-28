<?php

namespace App\Filament\Resources\TestStudentResource\Pages;

use App\Filament\Resources\TestStudentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestStudents extends ListRecords
{
    protected static string $resource = TestStudentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
