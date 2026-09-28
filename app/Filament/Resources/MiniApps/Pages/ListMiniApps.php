<?php

namespace App\Filament\Resources\MiniApps\Pages;

use App\Filament\Resources\MiniApps\MiniAppResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMiniApps extends ListRecords
{
    protected static string $resource = MiniAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
