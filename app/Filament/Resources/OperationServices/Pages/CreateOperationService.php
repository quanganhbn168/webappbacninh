<?php

namespace App\Filament\Resources\OperationServices\Pages;

use App\Filament\Resources\OperationServices\OperationServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOperationService extends CreateRecord
{
    protected static string $resource = OperationServiceResource::class;
}
