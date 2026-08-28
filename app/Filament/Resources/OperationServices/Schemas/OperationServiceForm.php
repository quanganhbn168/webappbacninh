<?php

namespace App\Filament\Resources\OperationServices\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperationServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin dịch vụ vận hành')
                    ->schema([
                        TextInput::make('title')->label('Tên dịch vụ')->required()->maxLength(255),
                        TextInput::make('slug')->label('Slug')->required()->maxLength(255),
                        TextInput::make('menu_key')->label('Mã menu')->maxLength(100),
                        TextInput::make('eyebrow')->label('Nhãn phụ')->maxLength(255),
                        TextInput::make('icon')->label('Icon')->maxLength(255),
                        TextInput::make('image')->label('Ảnh đại diện'),
                        TextInput::make('secondary_image')->label('Ảnh phụ'),
                        TextInput::make('price_from')->label('Giá từ')->maxLength(255),
                        TextInput::make('cadence')->label('Chu kỳ / thời lượng')->maxLength(255),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        Textarea::make('highlight')->label('Điểm nhấn')->rows(3),
                        Textarea::make('description')->label('Mô tả')->rows(5)->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('SEO và dữ liệu mở rộng')
                    ->schema([
                        TextInput::make('meta_title')->label('Meta title')->maxLength(255),
                        Textarea::make('meta_description')->label('Meta description')->rows(3),
                        KeyValue::make('data')->label('Dữ liệu bổ sung'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
