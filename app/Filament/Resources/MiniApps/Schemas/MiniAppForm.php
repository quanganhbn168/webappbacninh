<?php

namespace App\Filament\Resources\MiniApps\Schemas;

use App\Filament\Forms\IconPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MiniAppForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Tên công cụ')->required()->maxLength(255),
                TextInput::make('link')->label('Đường dẫn')->required()->maxLength(255)->placeholder('/tinh-thue-tncn')
                    ->helperText('Trang công cụ trong website hoặc link ngoài (https://…).'),
                Textarea::make('description')->label('Mô tả ngắn')->rows(2)->columnSpanFull(),
                IconPicker::make('icon'),
                TextInput::make('badge')->label('Nhãn')->maxLength(30)->placeholder('Mới'),
                Toggle::make('is_active')->label('Hiện ở trang Công cụ')->default(true),
                TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
            ]);
    }
}
