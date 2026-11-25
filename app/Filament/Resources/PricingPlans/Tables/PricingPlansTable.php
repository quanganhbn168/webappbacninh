<?php

namespace App\Filament\Resources\PricingPlans\Tables;

use App\Enums\PricingGroup;
use App\Models\PricingPlan;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Prices are edited right in the list: name, price text and the two switches
 * save as soon as the cell loses focus, so a price round needs no form.
 */
class PricingPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['services', 'operationServices']))
            ->columns([
                TextInputColumn::make('name')->label('Gói')->searchable()->rules(['required', 'max:255']),
                TextColumn::make('used_by')->label('Dịch vụ dùng')->wrap()->placeholder('—')
                    ->state(fn (PricingPlan $record): array => $record->services->concat($record->operationServices)->pluck('title')->all())
                    ->listWithLineBreaks()->limitList(2),
                TextColumn::make('group')->label('Bảng giá chung')->badge()->placeholder('Không hiện')
                    ->state(fn (PricingPlan $record): ?string => $record->show_in_table ? $record->group?->label() : null),
                TextInputColumn::make('price_prefix')->label('Trước giá')->rules(['nullable', 'max:30']),
                TextInputColumn::make('price')->label('Giá')->rules(['required', 'max:60']),
                TextInputColumn::make('price_suffix')->label('Sau giá')->rules(['nullable', 'max:30']),
                ToggleColumn::make('is_featured')->label('Nổi bật'),
                ToggleColumn::make('is_active')->label('Hiển thị'),
            ])
            ->filters([
                SelectFilter::make('group')->label('Nhóm')->options(PricingGroup::options()),
            ])
            ->recordActions([
                ReplicateAction::make()->label('Nhân bản')
                    ->beforeReplicaSaved(function (PricingPlan $replica): void {
                        $replica->name .= ' (bản sao)';
                        $replica->is_active = false;
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('show')->label('Hiển thị')->icon('heroicon-o-eye')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('hide')->label('Ẩn')->icon('heroicon-o-eye-slash')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
