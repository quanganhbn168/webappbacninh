<?php

namespace App\Filament\Forms;

use App\Filament\Resources\PricingPlans\PricingPlanResource;
use App\Models\PricingPlan;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;

/**
 * The prices a service page currently shows. They are typed in the price table only,
 * so the service form lists them and links there.
 */
final class ServicePrices
{
    public static function make(): Section
    {
        return Section::make('Giá và gói dịch vụ')
            ->description('Giá chỉ nhập ở Bảng giá: tạo hoặc sửa gói rồi chọn "Thuộc dịch vụ". Gói đứng đầu là "Giá từ".')
            ->headerActions([
                Action::make('openPriceTable')->label('Mở Bảng giá')->link()
                    ->url(fn (): string => PricingPlanResource::getUrl('index', ['tab' => 'service']))
                    ->openUrlInNewTab(),
            ])
            ->schema([
                TextEntry::make('current_prices')->hiddenLabel()
                    ->state(fn (?Model $record): array => $record?->plans()->get()
                        ->map(fn (PricingPlan $plan): string => $plan->name.': '.$plan->priceText())->all() ?? [])
                    ->listWithLineBreaks()
                    ->placeholder('Chưa có gói nào. Trang dịch vụ đang ghi "Liên hệ để tư vấn".'),
            ])
            ->columnSpanFull();
    }
}
