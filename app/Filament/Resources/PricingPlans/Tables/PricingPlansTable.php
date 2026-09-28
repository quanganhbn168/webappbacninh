<?php

namespace App\Filament\Resources\PricingPlans\Tables;

use App\Enums\PricingGroup;
use App\Models\PricingPlan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PricingPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->defaultGroup('group')
            ->columns([
                TextColumn::make('name')->label('Gói')->searchable(),
                TextColumn::make('group')->label('Nhóm')->badge()->formatStateUsing(fn (PricingGroup $state): string => $state->label()),
                TextColumn::make('price')->label('Giá')->state(fn (PricingPlan $record): string => $record->priceText()),
                IconColumn::make('is_featured')->label('Nổi bật')->boolean(),
                IconColumn::make('is_active')->label('Hiển thị')->boolean(),
            ])
            ->filters([
                SelectFilter::make('group')->label('Nhóm')->options(PricingGroup::options()),
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
