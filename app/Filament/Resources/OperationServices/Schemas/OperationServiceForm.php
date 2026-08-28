<?php

namespace App\Filament\Resources\OperationServices\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\FileUpload;
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
                        Tab::make('Thông tin chung')
                            ->schema([
                                Section::make('Thông tin dịch vụ vận hành')
                                    ->schema([
                                        TextInput::make('title')->label('Tên dịch vụ')->required()->maxLength(255),
                                        TextInput::make('slug')->label('Slug')->required()->maxLength(255),
                                        TextInput::make('menu_key')->label('Mã menu')->maxLength(100),
                                        TextInput::make('eyebrow')->label('Nhãn phụ')->maxLength(255),
                                        TextInput::make('icon')->label('Icon Font Awesome')->maxLength(255),
                                        TextInput::make('price_from')->label('Giá từ')->maxLength(255),
                                        TextInput::make('cadence')->label('Chu kỳ / thời lượng')->maxLength(255),
                                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Hình ảnh')
                            ->schema([
                                Section::make('Tải ảnh dịch vụ')
                                    ->description('Ảnh tải mới sẽ thay thế đường dẫn ảnh tương ứng khi lưu. Ảnh cũ vẫn được giữ nếu không tải file mới.')
                                    ->schema([
                                        self::imageUpload('image_upload', 'Ảnh đại diện', 'operation-services/featured'),
                                        self::imageUpload('secondary_image_upload', 'Ảnh phụ', 'operation-services/gallery'),
                                        TextInput::make('image')->label('Đường dẫn ảnh đại diện hiện tại')->maxLength(2048),
                                        TextInput::make('secondary_image')->label('Đường dẫn ảnh phụ hiện tại')->maxLength(2048),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Nội dung')
                            ->schema([
                                Section::make('Nội dung hiển thị')
                                    ->schema([
                                        Textarea::make('highlight')->label('Điểm nhấn')->rows(3),
                                        Textarea::make('description')->label('Mô tả')->rows(6),
                                        KeyValue::make('data')->label('Dữ liệu bổ sung')->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                Section::make('SEO dịch vụ')
                                    ->schema([
                                        TextInput::make('meta_title')->label('Meta title')->maxLength(255),
                                        Textarea::make('meta_description')->label('Meta description')->rows(4),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function imageUpload(string $name, string $label, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatioOptions(['16:9', '4:3', '1:1'])
            ->maxSize(6144)
            ->openable();
    }
}
