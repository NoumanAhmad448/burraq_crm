<?php

namespace App\Filament\Resources\TestStudentCourseResource\Pages;

use App\Filament\Resources\TestStudentCourseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestStudentCourses extends ListRecords
{
    protected static string $resource = TestStudentCourseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
