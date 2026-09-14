<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['curator_managed'] = true;

        return $data;
    }

    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = PostResource::class;
}
