<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin dự án')
                    ->schema([
                        TextInput::make('title')
                            ->label('Tên dự án')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label('Mã dự án')
                            ->maxLength(50),
                        TextInput::make('category')
                            ->label('Nhóm dự án')
                            ->maxLength(255),
                        TextInput::make('industry')
                            ->label('Ngành')
                            ->maxLength(255),
                        TextInput::make('year')
                            ->label('Năm')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(2100),
                        TextInput::make('client')->label('Khách hàng'),
                        TextInput::make('duration')->label('Thời gian thực hiện'),
                        TextInput::make('website_type')->label('Loại website'),
                        TextInput::make('image')
                            ->label('Ảnh đại diện')
                            ->helperText('Nhập đường dẫn asset hiện có, ví dụ: frontend/assets/images/project.webp.'),
                        TextInput::make('link')
                            ->label('Link dự án')
                            ->url(),
                        TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Đang hiển thị')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Dự án nổi bật'),
                        Textarea::make('excerpt')
                            ->label('Mô tả ngắn')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Nội dung chi tiết')
                    ->schema([
                        Textarea::make('challenge')->label('Bài toán')->rows(5),
                        Textarea::make('solution')->label('Giải pháp')->rows(5),
                        TagsInput::make('deliverables')->label('Hạng mục bàn giao'),
                        TagsInput::make('technologies')->label('Công nghệ'),
                        TagsInput::make('gallery')->label('Ảnh gallery'),
                        TagsInput::make('results')->label('Kết quả'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Dữ liệu mở rộng')
                    ->schema([
                        KeyValue::make('data')
                            ->label('Dữ liệu bổ sung')
                            ->helperText('Dùng cho các trường mở rộng của dự án.'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
