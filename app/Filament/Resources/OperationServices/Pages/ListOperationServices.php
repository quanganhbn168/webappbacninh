<?php

namespace App\Filament\Resources\OperationServices\Pages;

use App\Filament\Resources\OperationServices\OperationServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperationServices extends ListRecords
{
    protected static string $resource = OperationServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
