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
use Filament\Schemas\Components\Group;
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
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Nội dung bài viết')->schema([
                        TextInput::make('title')->label('Tiêu đề')->required()->maxLength(255)->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?Post $record, ?string $state): void {
                                if (! $record && ! $get('slug_manually_edited')) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        Hidden::make('slug_manually_edited')->default(false)->dehydrated(false),
                        PermalinkInput::make('slug')->prefix(url('/kien-thuc').'/')
                            ->afterStateUpdated(fn (Set $set) => $set('slug_manually_edited', true)),
                        Textarea::make('summary')->label('Mô tả ngắn')->rows(4),
                        RichEditor::make('content')->label('Nội dung')->required()
                            ->plugins([AttachCuratorMediaPlugin::make()])
                            ->disableToolbarButtons(['attachFiles'])
                            ->enableToolbarButtons(['attachCuratorMedia'])
                            ->extraAttributes(['class' => 'blog-content-editor'])
                            ->helperText('Thời gian đọc được ước tính tự động từ nội dung bài viết.'),
                    ])->columns(1),
                    Section::make('SEO')->schema([
                        TextInput::make('meta_title')->label('Tiêu đề SEO')->maxLength(255),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Thiết lập bài viết')->schema([
                        Select::make('category_id')->label('Danh mục')
                            ->relationship('category', 'name')->searchable()->preload(),
                        DateTimePicker::make('published_at')->label('Ngày xuất bản')->seconds(false)->default(now()),
                        Toggle::make('is_published')->label('Đã xuất bản')->default(true),
                        Toggle::make('is_featured')->label('Nổi bật'),
                    ])->columns(1),
                    Section::make('Hình ảnh')->schema([
                        CuratorPicker::make('featured_media_id')->label('Ảnh đại diện')->constrained(),
                        CuratorPicker::make('og_media_id')->label('Ảnh chia sẻ')->constrained()
                            ->helperText('Ảnh hiển thị khi chia sẻ liên kết. Để trống sẽ dùng ảnh đại diện.'),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
