<?php

namespace App\Filament\Resources\AdBanners\Tables;

use App\Enums\BannerSlot;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdBannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->columns([
                CuratorColumn::make('image')->label('Ảnh')->size(48),
                TextColumn::make('name')->label('Banner')->searchable(),
                TextColumn::make('slot')->label('Vị trí')->badge()->formatStateUsing(fn (BannerSlot $state): string => $state->label()),
                TextColumn::make('ends_at')->label('Kết thúc')->dateTime('d/m/Y')->placeholder('Không giới hạn'),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->filters([
                SelectFilter::make('slot')->label('Vị trí')->options(BannerSlot::options()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
