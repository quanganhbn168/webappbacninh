<?php

namespace App\Filament\Resources\LandingPages\Schemas;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

/** Small builders so the two templates' field lists read as plain lists of what the page contains. */
final class LandingFields
{
    public static function text(string $key, string $label, int $max = 255): TextInput
    {
        return TextInput::make('content.'.$key)->label($label)->maxLength($max);
    }

    public static function area(string $key, string $label, int $rows = 2, int $max = 2000): Textarea
    {
        return Textarea::make('content.'.$key)->label($label)->rows($rows)->maxLength($max)->columnSpanFull();
    }

    public static function image(string $key, string $label, string $hint): CuratorPicker
    {
        return CuratorPicker::make('content.'.$key)->label($label)->helperText($hint.' Để trống thì dùng ảnh mẫu của giao diện.')->constrained();
    }

    /**
     * @param  array<int, \Filament\Schemas\Components\Component|\Filament\Forms\Components\Field>  $schema
     */
    public static function list(string $key, string $label, array $schema, string $itemLabel, string $add = 'Thêm mục'): Repeater
    {
        return Repeater::make('content.'.$key)->label($label)
            ->schema($schema)->columns(2)
            ->itemLabel(fn (array $state): ?string => $state[$itemLabel] ?? null)
            ->collapsible()->collapsed()->reorderable()->addActionLabel($add)->defaultItems(0)->columnSpanFull();
    }

    /** A repeater of items that have a single line of text. */
    public static function lines(string $key, string $label, string $add = 'Thêm dòng', string $field = 'text'): Repeater
    {
        return Repeater::make('content.'.$key)->label($label)
            ->schema([TextInput::make($field)->label('Nội dung')->required()->maxLength(255)])
            ->itemLabel(fn (array $state): ?string => $state[$field] ?? null)
            ->reorderable()->addActionLabel($add)->defaultItems(0)->columnSpanFull();
    }

    public static function title(string $key = 'title', string $label = 'Tiêu đề'): TextInput
    {
        return TextInput::make($key)->label($label)->required()->maxLength(255);
    }

    public static function text2(string $key, string $label): Textarea
    {
        return Textarea::make($key)->label($label)->rows(2)->maxLength(1000)->columnSpanFull();
    }

    public static function avatar(): Select
    {
        return Select::make('avatar')->label('Ảnh chân dung (minh họa)')
            ->options(['lan' => 'Nữ — kiểu 1', 'hoang' => 'Nam — kiểu 1', 'huong' => 'Nữ — kiểu 2'])->default('lan')->native(false);
    }

    public static function section(string $heading, array $schema, bool $collapsed = true): Section
    {
        return Section::make($heading)->schema($schema)->columns(2)->collapsible()->collapsed($collapsed);
    }
}
