<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductGroup;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Thông tin sản phẩm')->schema([
                        TextInput::make('name')->label('Tên sản phẩm')->required()->maxLength(255),
                        Select::make('group')->label('Nhóm')->options(ProductGroup::options())->required()->native(false)
                            ->helperText('Hiển thị thành tab lọc ở trang Sản phẩm.'),
                        Textarea::make('summary')->label('Mô tả ngắn')->rows(3),
                        TagsInput::make('tags')->label('Nhãn')->placeholder('Website, Booking…'),
                    ])->columns(1),
                    Section::make('Liên kết')->schema([
                        TextInput::make('detail_url')->label('Link "Xem chi tiết"')->maxLength(255)->placeholder('/kho-giao-dien/ten-mau')
                            ->helperText('Bỏ trống: nút mở form tư vấn.'),
                        TextInput::make('demo_url')->label('Link "Xem Demo"')->url()->maxLength(255)
                            ->helperText('Bỏ trống: nút mở form tư vấn.'),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Hình ảnh')->schema([
                        CuratorPicker::make('image_id')->label('Ảnh sản phẩm')->constrained(),
                    ]),
                    Section::make('Hiển thị')->schema([
                        Toggle::make('is_featured')->label('Hiện ở trang chủ'),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                    ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
