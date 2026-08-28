<?php

namespace App\Filament\Resources\OperationServices\Pages;

use App\Domain\Services\Actions\MapServiceUploadData;
use App\Filament\Resources\OperationServices\OperationServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOperationService extends CreateRecord
{
    protected static string $resource = OperationServiceResource::class;

    /** @param array<string, mixed> $data
     *  @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return app(MapServiceUploadData::class)->execute($data);
    }
}
