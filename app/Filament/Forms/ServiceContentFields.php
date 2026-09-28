<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

/**
 * Repeaters for the structured blocks of service landing pages, shared by
 * website services and operation services.
 */
final class ServiceContentFields
{
    public static function textList(string $name, string $label, string $placeholder = ''): Repeater
    {
        return Repeater::make($name)->label($label)
            ->simple(TextInput::make('value')->required()->maxLength(255)->placeholder($placeholder))
            ->addActionLabel('Thêm dòng')->reorderable()->defaultItems(0);
    }

    /**
     * Cards with a Font Awesome icon, a title and a short text.
     */
    public static function cards(string $name, string $label): Repeater
    {
        return Repeater::make($name)->label($label)
            ->schema([
                TextInput::make('icon')->label('Biểu tượng')->placeholder('fa-solid fa-check')->maxLength(100),
                TextInput::make('title')->label('Tiêu đề')->required()->maxLength(255),
                Textarea::make('text')->label('Mô tả')->rows(2)->columnSpanFull(),
            ])
            ->columns(2)->collapsible()->reorderable()->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
            ->addActionLabel('Thêm thẻ');
    }

    public static function packages(): Repeater
    {
        return Repeater::make('packages')->label('Gói dịch vụ')
            ->schema([
                TextInput::make('name')->label('Tên gói')->required()->maxLength(100),
                TextInput::make('price')->label('Giá')->required()->maxLength(100)->placeholder('Từ 8 triệu'),
                Textarea::make('desc')->label('Mô tả ngắn')->rows(2)->columnSpanFull(),
                TagsInput::make('items')->label('Hạng mục trong gói')->placeholder('Thêm hạng mục rồi nhấn Enter')->columnSpanFull(),
                Toggle::make('featured')->label('Gói nổi bật ("Được quan tâm")'),
            ])
            ->columns(2)->collapsible()->reorderable()->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
            ->addActionLabel('Thêm gói');
    }

    public static function faqs(): Repeater
    {
        return Repeater::make('faqs')->label('Câu hỏi thường gặp')
            ->schema([
                TextInput::make('q')->label('Câu hỏi')->required()->maxLength(255),
                Textarea::make('a')->label('Trả lời')->required()->rows(3),
            ])
            ->collapsible()->reorderable()->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['q'] ?? null)
            ->addActionLabel('Thêm câu hỏi');
    }

    public static function process(): Repeater
    {
        return Repeater::make('process')->label('Quy trình')
            ->schema([
                TextInput::make('step')->label('Bước')->maxLength(10)->placeholder('01'),
                TextInput::make('title')->label('Tiêu đề')->required()->maxLength(255),
                Textarea::make('text')->label('Mô tả')->rows(2)->columnSpanFull(),
            ])
            ->columns(2)->collapsible()->reorderable()->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
            ->addActionLabel('Thêm bước');
    }
}
