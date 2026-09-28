<?php

namespace App\Filament\Resources\Pages\Schemas;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('SEO')->description('Hiển thị trên Google và khi chia sẻ lên mạng xã hội.')->schema([
                        TextInput::make('title')->label('Tên trang')->required()->maxLength(255)
                            ->helperText('Dùng ở breadcrumb và tiêu đề mặc định.'),
                        TextInput::make('meta_title')->label('Tiêu đề SEO')->maxLength(255)
                            ->helperText('Bỏ trống sẽ dùng "Tên trang | Tên website". Nên dưới 60 ký tự.'),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3)->maxLength(500)
                            ->helperText('Bỏ trống sẽ dùng mô tả mặc định của trang. Nên 120 – 160 ký tự.'),
                        TextInput::make('meta_keywords')->label('Từ khóa')->maxLength(255),
                        Toggle::make('noindex')->label('Ẩn trang khỏi công cụ tìm kiếm (noindex)'),
                    ])->columns(1),
                    Section::make('Banner đầu trang')->description('Bỏ trống để dùng nội dung đang có trong giao diện.')->schema([
                        TextInput::make('banner_eyebrow')->label('Dòng chữ nhỏ trên tiêu đề')->maxLength(255),
                        TextInput::make('banner_title')->label('Tiêu đề banner')->maxLength(255),
                        TextInput::make('banner_highlight')->label('Dòng nhấn màu vàng')->maxLength(255)
                            ->helperText('Dòng thứ hai của tiêu đề, chỉ dùng khi có tiêu đề banner.'),
                        Textarea::make('banner_subtitle')->label('Đoạn mô tả')->rows(3),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Hình ảnh')->schema([
                        CuratorPicker::make('banner_image_id')->label('Ảnh banner')->constrained()
                            ->helperText('Ảnh bên phải tiêu đề. Nên dùng ảnh nền sáng, tỷ lệ 4:3.'),
                        CuratorPicker::make('og_image_id')->label('Ảnh chia sẻ (OG)')->constrained()
                            ->helperText('Tỷ lệ 1.91:1. Bỏ trống sẽ dùng ảnh banner hoặc ảnh mặc định.'),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
