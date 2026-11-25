<?php

namespace App\Filament\Forms;

use App\Filament\Resources\PricingPlans\PricingPlanResource;
use App\Models\PricingPlan;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;

/**
 * A service picks its plans from the price table, the only place prices are typed.
 */
final class ServicePrices
{
    public static function make(): Section
    {
        return Section::make('Giá và gói dịch vụ')
            ->description('Giá chỉ nhập ở Bảng giá. Ở đây chọn gói cho dịch vụ này; một gói dùng được cho nhiều dịch vụ.')
            ->headerActions([
                Action::make('openPriceTable')->label('Mở Bảng giá')->link()
                    ->url(fn (): string => PricingPlanResource::getUrl('index'))
                    ->openUrlInNewTab(),
            ])
            ->schema([
                Select::make('pricingPlans')->label('Gói giá')
                    ->relationship('pricingPlans', 'name', fn (Builder $query) => $query->orderBy('pricing_plans.order'))
                    ->getOptionLabelFromRecordUsing(fn (PricingPlan $record): string => $record->name.' — '.$record->priceText())
                    ->multiple()->preload()->searchable()
                    ->helperText('Trang xếp gói theo thứ tự ở Bảng giá, gói đầu tiên là "Giá từ". Không chọn gói nào thì ghi "Liên hệ để tư vấn".'),
            ])
            ->columnSpanFull();
    }
}
