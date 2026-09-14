<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\BlogRichEditor as RichEditor;
use App\Filament\Forms\PermalinkInput;
use App\Models\Post;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Forms\RichEditor\AttachCuratorMediaPlugin;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin bài viết')
                    ->schema([
                        TextInput::make('title')->label('Tiêu đề')->required()->maxLength(255)->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?Post $record, ?string $state): void {
                                if (! $record && ! $get('slug_manually_edited')) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        Hidden::make('slug_manually_edited')->default(false)->dehydrated(false),
                        PermalinkInput::make('slug')->prefix(url('/kien-thuc').'/')->afterStateUpdated(fn (Set $set) => $set('slug_manually_edited', true)),
                        Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('read_time')->label('Thời gian đọc (phút)')->numeric()->minValue(1),
                        CuratorPicker::make('featured_media_id')->label('Ảnh đại diện'),
                        DateTimePicker::make('published_at')->label('Ngày xuất bản')->seconds(false)->default(now()),
                        Toggle::make('is_published')->label('Đã xuất bản')->default(true),
                        Toggle::make('is_featured')->label('Nổi bật'),
                        Textarea::make('summary')->label('Tóm tắt')->rows(4)->columnSpanFull(),
                        RichEditor::make('content')->label('Nội dung')->required()->columnSpanFull()
                            ->plugins([AttachCuratorMediaPlugin::make()])
                            ->toolbarButtons([['bold', 'italic', 'link'], ['h2', 'h3'], ['bulletList', 'orderedList', 'blockquote'], ['attachCuratorMedia'], ['undo', 'redo']]),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('meta_title')->label('Meta title')->maxLength(255),
                        Textarea::make('meta_description')->label('Meta description')->rows(3),
                        CuratorPicker::make('og_media_id')->label('og:image')->helperText('Ảnh khi chia sẻ bài viết. Để trống sẽ dùng ảnh đại diện.')->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
