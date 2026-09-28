<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Tên khách hàng')->required()->maxLength(255),
                TextInput::make('role')->label('Chức danh - Công ty')->maxLength(255),
                Textarea::make('quote')->label('Nội dung đánh giá')->required()->rows(4)->columnSpanFull(),
                CuratorPicker::make('avatar_id')->label('Ảnh đại diện')->constrained(),
                Select::make('rating')->label('Số sao')->options([5 => '5 sao', 4 => '4 sao', 3 => '3 sao'])->default(5)->native(false),
                Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
            ]);
    }
}
