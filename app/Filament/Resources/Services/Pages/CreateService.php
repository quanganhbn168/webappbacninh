<?php

namespace App\Filament\Resources\Services\Pages;

use App\Domain\Services\Actions\MapServiceUploadData;
use App\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    /** @param array<string, mixed> $data
     *  @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return app(MapServiceUploadData::class)->execute($data);
    }
}
