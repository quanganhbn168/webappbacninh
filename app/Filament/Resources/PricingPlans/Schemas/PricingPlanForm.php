<?php

namespace App\Filament\Resources\PricingPlans\Schemas;

use App\Enums\PricingGroup;
use App\Filament\Forms\IconPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PricingPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Gói giá')->schema([
                        Select::make('group')->label('Nhóm')->options(PricingGroup::options())->required()->native(false)
                            ->helperText('Website hiện ở trang chủ, Dịch vụ, Thiết kế website; Hosting ở trang Hosting; Chăm sóc ở trang Dịch vụ vận hành.'),
                        TextInput::make('name')->label('Tên gói')->required()->maxLength(255),
                        Group::make([
                            TextInput::make('price_prefix')->label('Chữ trước giá')->maxLength(30)->placeholder('Từ'),
                            TextInput::make('price')->label('Giá')->required()->maxLength(60)->placeholder('12.000.000đ'),
                            TextInput::make('price_suffix')->label('Chữ sau giá')->maxLength(30)->placeholder('/năm'),
                        ])->columns(3),
                        Textarea::make('summary')->label('Mô tả ngắn')->rows(2),
                        Repeater::make('features')->label('Hạng mục trong gói')
                            ->simple(TextInput::make('value')->required()->maxLength(255))
                            ->addActionLabel('Thêm hạng mục')->reorderable()->defaultItems(0),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Hiển thị')->schema([
                        Toggle::make('is_featured')->label('Gói nổi bật'),
                        TextInput::make('badge')->label('Nhãn gói nổi bật')->maxLength(60)->placeholder('Phổ biến nhất'),
                        TextInput::make('cta_label')->label('Chữ trên nút')->maxLength(60)->placeholder('Nhận tư vấn'),
                        IconPicker::make('icon')->helperText('Dùng cho Dịch vụ bổ sung.'),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                    ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
