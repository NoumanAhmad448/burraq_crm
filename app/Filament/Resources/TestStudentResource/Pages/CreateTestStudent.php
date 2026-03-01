<?php

namespace App\Filament\Resources\TestStudentResource\Pages;

use App\Filament\Resources\TestStudentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestStudent extends CreateRecord
{
    protected static string $resource = TestStudentResource::class;
}
