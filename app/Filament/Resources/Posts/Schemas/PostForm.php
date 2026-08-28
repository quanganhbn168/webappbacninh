<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin bài viết')
                    ->schema([
                        TextInput::make('title')->label('Tiêu đề')->required()->maxLength(255),
                        TextInput::make('slug')->label('Slug')->required()->maxLength(255),
                        Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('read_time')->label('Thời gian đọc (phút)')->numeric()->minValue(1),
                        TextInput::make('featured_image')->label('Ảnh đại diện'),
                        TextInput::make('og_image')->label('Ảnh Open Graph'),
                        DateTimePicker::make('published_at')->label('Ngày xuất bản')->seconds(false),
                        Toggle::make('is_published')->label('Đã xuất bản')->default(true),
                        Toggle::make('is_featured')->label('Nổi bật'),
                        Textarea::make('summary')->label('Tóm tắt')->rows(4)->columnSpanFull(),
                        Textarea::make('content')
                            ->label('Nội dung')
                            ->rows(18)
                            ->columnSpanFull()
                            ->helperText('Có thể nhập HTML hoặc JSON sections hiện có của website.'),
                        TextInput::make('meta_keywords')->label('Từ khóa SEO')->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('SEO')
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
