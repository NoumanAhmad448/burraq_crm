<?php

namespace App\Filament\Resources\TestCourseResource\Pages;

use App\Filament\Resources\TestCourseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestCourse extends CreateRecord
{
    protected static string $resource = TestCourseResource::class;
}
