<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

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
     * Cards with an icon, a title and a short text.
     */
    public static function cards(string $name, string $label): Repeater
    {
        return Repeater::make($name)->label($label)
            ->schema([
                IconPicker::make('icon'),
                TextInput::make('title')->label('Tiêu đề')->required()->maxLength(255),
                Textarea::make('text')->label('Mô tả')->rows(2)->columnSpanFull(),
            ])
            ->columns(2)->collapsible()->reorderable()->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
            ->addActionLabel('Thêm thẻ');
    }

    public static function faqs(): FaqEditor
    {
        return FaqEditor::make('faqs');
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
