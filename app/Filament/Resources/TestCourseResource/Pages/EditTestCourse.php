<?php

namespace App\Filament\Resources\TestCourseResource\Pages;

use App\Filament\Resources\TestCourseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestCourse extends EditRecord
{
    protected static string $resource = TestCourseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
