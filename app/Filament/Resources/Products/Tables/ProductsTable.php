<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ProductGroup;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->columns([
                CuratorColumn::make('image')->label('Ảnh')->size(48),
                TextColumn::make('name')->label('Sản phẩm')->searchable()->sortable(),
                TextColumn::make('group')->label('Nhóm')->badge()->formatStateUsing(fn (ProductGroup $state): string => $state->label()),
                IconColumn::make('is_featured')->label('Trang chủ')->boolean(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->filters([
                SelectFilter::make('group')->label('Nhóm')->options(ProductGroup::options()),
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
