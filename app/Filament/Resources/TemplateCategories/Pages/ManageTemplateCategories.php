<?php

namespace App\Filament\Resources\TemplateCategories\Pages;

use App\Filament\Resources\TemplateCategories\TemplateCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageTemplateCategories extends ManageRecords
{
    protected static string $resource = TemplateCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::FiveExtraLarge),
        ];
    }
}
