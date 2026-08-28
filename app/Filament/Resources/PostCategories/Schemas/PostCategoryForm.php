<?php

namespace App\Filament\Resources\PostCategories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin danh mục')
                    ->schema([
                        TextInput::make('name')->label('Tên danh mục')->required()->maxLength(255),
                        TextInput::make('slug')->label('Slug')->required()->maxLength(255),
                        ColorPicker::make('color')->label('Màu nhãn'),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                        Textarea::make('description')->label('Mô tả')->rows(4)->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
