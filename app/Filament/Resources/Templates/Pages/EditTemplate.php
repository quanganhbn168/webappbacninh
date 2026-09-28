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
            DeleteAction::make(),
        ];
    }
}
