<?php

namespace App\Filament\Resources\PricingPlans\Pages;

use App\Filament\Resources\PricingPlans\PricingPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPricingPlans extends ListRecords
{
    protected static string $resource = PricingPlanResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tất cả'),
            'table' => Tab::make('Bảng giá chung')->modifyQueryUsing(fn (Builder $query) => $query->where('show_in_table', true)),
            'service' => Tab::make('Theo dịch vụ')->modifyQueryUsing(fn (Builder $query) => $query
                ->where(fn (Builder $query) => $query->whereHas('services')->orWhereHas('operationServices'))),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
