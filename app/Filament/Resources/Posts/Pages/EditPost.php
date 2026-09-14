<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Domain\Content\PrepareBlogContent;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['content'] = app(PrepareBlogContent::class)->execute($data['content'] ?? null);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['curator_managed'] = true;

        return $data;
    }

    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
