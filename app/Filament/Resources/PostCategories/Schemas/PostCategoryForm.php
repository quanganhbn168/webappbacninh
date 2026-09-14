<?php

namespace App\Filament\Resources\PostCategories\Schemas;

use App\Filament\Forms\PermalinkInput;
use App\Models\PostCategory;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Thông tin danh mục')->schema([
                        TextInput::make('name')->label('Tên danh mục')->required()->maxLength(255)->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?PostCategory $record, ?string $state): void {
                                if (! $record && ! $get('slug_manually_edited')) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        Hidden::make('slug_manually_edited')->default(false)->dehydrated(false),
                        PermalinkInput::make('slug')->prefix(url('/kien-thuc/danh-muc').'/')
                            ->afterStateUpdated(fn (Set $set) => $set('slug_manually_edited', true)),
                        Textarea::make('description')->label('Mô tả')->rows(4),
                    ])->columns(1),
                    Section::make('SEO')->schema([
                        TextInput::make('meta_title')->label('Tiêu đề SEO')->maxLength(255),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Thiết lập danh mục')->schema([
                        ColorPicker::make('color')->label('Màu nhãn')->default('#ffac00')->required(),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                    ])->columns(1),
                    Section::make('Hình ảnh')->schema([
                        CuratorPicker::make('image_id')->label('Ảnh danh mục')->constrained(),
                        CuratorPicker::make('og_media_id')->label('Ảnh chia sẻ')->constrained(),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
