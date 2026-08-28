<?php

namespace App\Filament\Resources\OperationServices\Pages;

use App\Domain\Services\Actions\MapServiceUploadData;
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

    /** @param array<string, mixed> $data
     *  @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return app(MapServiceUploadData::class)->execute($data);
    }
}
