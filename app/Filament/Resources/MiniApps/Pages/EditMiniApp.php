<?php

namespace App\Filament\Resources\MiniApps\Pages;

use App\Filament\Resources\MiniApps\MiniAppResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMiniApp extends EditRecord
{
    protected static string $resource = MiniAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
