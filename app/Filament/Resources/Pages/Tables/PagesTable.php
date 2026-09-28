<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->paginated(false)
            ->columns([
                TextColumn::make('title')->label('Trang')->searchable()->weight('bold')
                    ->description(fn (Page $record): ?string => $record->url ? parse_url($record->url, PHP_URL_PATH) : null),
                TextColumn::make('meta_title')->label('Tiêu đề SEO')->placeholder('Mặc định')->limit(50)->wrap(),
                IconColumn::make('meta_description')->label('Mô tả SEO')->boolean()
                    ->state(fn (Page $record): bool => filled($record->meta_description)),
                IconColumn::make('banner_title')->label('Banner riêng')->boolean()
                    ->state(fn (Page $record): bool => filled($record->banner_title) || filled($record->banner_image_id)),
                IconColumn::make('noindex')->label('Ẩn khỏi Google')->boolean(),
                TextColumn::make('updated_at')->label('Cập nhật')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->recordActions([
                Action::make('view')->label('Xem')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Page $record): ?string => $record->url, shouldOpenInNewTab: true),
                EditAction::make(),
            ]);
    }
}
