<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    use AnalyzesSeo;

    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Shows the saved article; before it is live the same address is an admin-only preview.
            Action::make('view')->label(fn (Post $record): string => $record->isLive() ? 'Xem bài' : 'Xem trước')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Post $record): string => $record->url, shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->storeSeoScore();
    }

    /** The form edits only some keys of `data`; the others stay as they are. */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['data'] = array_merge($this->getRecord()->data ?? [], $data['data'] ?? []);

        return $data;
    }
}
