<?php

namespace App\Filament\Resources\Templates\Pages;

use App\Filament\Resources\Templates\TemplateResource;
use App\Models\Template;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTemplate extends EditRecord
{
    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')->label('Xem trang')->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Template $record): string => $record->url, shouldOpenInNewTab: true),
            Action::make('demo')->label('Xem demo')->icon('heroicon-o-device-phone-mobile')->color('gray')
                ->visible(fn (Template $record): bool => $record->preview_url !== null)
                ->url(fn (Template $record): ?string => $record->preview_url, shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }
}
