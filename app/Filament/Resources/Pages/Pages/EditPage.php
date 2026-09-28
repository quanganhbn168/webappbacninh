<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')->label('Xem trang')->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Page $record): string => url('/'.$record->slug), shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }
}
