<?php

namespace App\Filament\Resources\TestCourseResource\Pages;

use App\Filament\Resources\TestCourseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestCourses extends ListRecords
{
    protected static string $resource = TestCourseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
