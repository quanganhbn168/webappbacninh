<?php

namespace App\Filament\Resources\Menus\Pages;

use App\Filament\Resources\Menus\MenuResource;
use App\Support\FrontendMenuCache;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = MenuResource::class;

    protected function afterSave(): void
    {
        // Nested items are saved after the menu itself; clear once more at the end.
        app(FrontendMenuCache::class)->forget($this->record->location);
    }
}
