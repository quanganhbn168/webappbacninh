<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Forms\ServiceContentFields;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        $isLanding = fn (Get $get): bool => (bool) $get('is_landing');

        return $schema
            ->components([
                Tabs::make('Dịch vụ')
                    ->id('service-form-tabs')
                    ->persistTab()
                    ->tabs([
                        Tab::make('Thông tin chung')->schema([
                            Section::make('Thông tin dịch vụ')->schema([
                                TextInput::make('title')->label('Tên dịch vụ')->required()->maxLength(255),
                                TextInput::make('slug')->label('Đường dẫn')->required()->maxLength(255)->alphaDash()->unique(ignoreRecord: true),
                                Toggle::make('is_landing')->label('Có trang landing riêng')->live()
                                    ->helperText('Bật: trang thiết kế sẵn tại /thiet-ke-website/đường-dẫn với gói giá, FAQ… Tắt: trang nội dung đơn giản tại /đường-dẫn.')
                                    ->columnSpanFull(),
                                Select::make('service_category_id')->label('Danh mục')->relationship('category', 'name')
                                    ->searchable()->preload(),
                                TextInput::make('menu_key')->label('Mã menu')->maxLength(100),
                                TextInput::make('eyebrow')->label('Nhãn phụ')->maxLength(255)->placeholder('WEBSITE DOANH NGHIỆP'),
                                TextInput::make('icon')->label('Biểu tượng Font Awesome')->maxLength(255)->placeholder('fa-solid fa-building'),
                                TextInput::make('price_from')->label('Giá từ')->maxLength(255)->placeholder('Từ 8 triệu'),
                                TextInput::make('timeline')->label('Thời gian triển khai')->maxLength(255)->placeholder('2 - 4 tuần'),
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
                            Section::make('Nội dung trang')->hidden($isLanding)->schema([
                                RichEditor::make('content')->hiddenLabel(),
                            ]),
                            Section::make('Trang landing')->visible($isLanding)->schema([
                                TextInput::make('cta')->label('Nút kêu gọi')->maxLength(255)->placeholder('Nhận cấu trúc website doanh nghiệp'),
                                TextInput::make('need_value')->label('Nhu cầu điền sẵn trong form')->maxLength(255),
                                ServiceContentFields::textList('audiences', 'Phù hợp với ai?')->columnSpanFull(),
                                ServiceContentFields::cards('problems', 'Vấn đề thường gặp')->columnSpanFull(),
                                ServiceContentFields::textList('pages', 'Các trang đề xuất', 'Trang chủ')->columnSpanFull(),
                                ServiceContentFields::cards('features', 'Tính năng nổi bật')->columnSpanFull(),
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
