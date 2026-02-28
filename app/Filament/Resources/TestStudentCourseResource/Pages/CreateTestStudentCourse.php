<?php

namespace App\Filament\Resources\TestStudentCourseResource\Pages;

use App\Filament\Resources\TestStudentCourseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestStudentCourse extends CreateRecord
{
    protected static string $resource = TestStudentCourseResource::class;
}
