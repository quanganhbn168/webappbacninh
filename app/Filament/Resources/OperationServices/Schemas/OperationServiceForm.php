<?php

namespace App\Filament\Resources\OperationServices\Schemas;

use App\Filament\Forms\IconPicker;
use App\Filament\Forms\ServiceContentFields;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class OperationServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Dịch vụ vận hành')
                    ->id('operation-service-form-tabs')
                    ->persistTab()
                    ->tabs([
                        Tab::make('Thông tin chung')->schema([
                            Section::make('Thông tin dịch vụ vận hành')->schema([
                                TextInput::make('title')->label('Tên dịch vụ')->required()->maxLength(255),
                                TextInput::make('slug')->label('Đường dẫn')->required()->maxLength(255)->alphaDash()->unique(ignoreRecord: true)
                                    ->prefix(url('/dich-vu-van-hanh').'/'),
                                TextInput::make('menu_key')->label('Mã menu')->maxLength(100),
                                TextInput::make('eyebrow')->label('Nhãn phụ')->maxLength(255),
                                IconPicker::make('icon'),
                                TextInput::make('price_from')->label('Giá từ')->maxLength(255)->placeholder('Từ 500.000đ/tháng'),
                                TextInput::make('cadence')->label('Chu kỳ')->maxLength(255)->placeholder('Theo tháng'),
                                TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                                Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                            ])->columns(2),
                            Section::make('Giới thiệu')->schema([
                                Textarea::make('highlight')->label('Điểm nhấn')->rows(2),
                                Textarea::make('description')->label('Mô tả ngắn')->rows(3),
                            ])->columns(1),
                        ]),
                        Tab::make('Hình ảnh')->schema([
                            Section::make()->schema([
                                CuratorPicker::make('image_id')->label('Ảnh đại diện')->constrained(),
                                CuratorPicker::make('secondary_image_id')->label('Ảnh phụ')->constrained(),
                            ])->columns(2),
                        ]),
                        Tab::make('Nội dung')->schema([
                            Section::make()->schema([
                                TextInput::make('cta')->label('Nút kêu gọi')->maxLength(255),
                                TextInput::make('need_value')->label('Nhu cầu điền sẵn trong form')->maxLength(255),
                                ServiceContentFields::textList('audiences', 'Phù hợp với ai?')->columnSpanFull(),
                                ServiceContentFields::cards('scope', 'Phạm vi công việc')->columnSpanFull(),
                                ServiceContentFields::textList('deliverables', 'Sản phẩm bàn giao')->columnSpanFull(),
                                ServiceContentFields::process()->columnSpanFull(),
                                ServiceContentFields::packages()->columnSpanFull(),
                                ServiceContentFields::faqs()->columnSpanFull(),
                            ])->columns(2),
                        ]),
                        Tab::make('SEO')->schema([
                            Section::make('SEO dịch vụ')->schema([
                                TextInput::make('meta_title')->label('Tiêu đề SEO')->maxLength(255),
                                Textarea::make('meta_description')->label('Mô tả SEO')->rows(4),
                            ])->columns(1),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
