<?php

namespace App\Filament\Resources\OperationServices\Pages;

use App\Filament\Resources\OperationServices\OperationServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOperationService extends EditRecord
{
    protected static string $resource = OperationServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
