<?php

namespace App\Filament\Resources\ThemeFeatures\Pages;

use App\Filament\Resources\ThemeFeatures\ThemeFeatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageThemeFeatures extends ManageRecords
{
    protected static string $resource = ThemeFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
