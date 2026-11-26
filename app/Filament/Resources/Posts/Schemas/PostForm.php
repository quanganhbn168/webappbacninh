<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\FaqEditor;
use App\Filament\Forms\FullRichEditor;
use App\Filament\Forms\PermalinkInput;
use App\Filament\Forms\SeoTitleInput;
use App\Models\Post;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
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
                        PermalinkInput::make('slug')->prefix(url('/').'/')
                            ->afterStateUpdated(fn (Set $set) => $set('slug_manually_edited', true)),
                        Textarea::make('summary')->label('Mô tả ngắn')->rows(4)
                            ->helperText('Hiện ở đầu bài, trên thẻ bài viết và kết quả tìm kiếm.'),
                        FullRichEditor::make('content')->label('Nội dung')->required()
                            ->helperText('Thời gian đọc được ước tính tự động từ nội dung bài viết.'),
                    ])->columns(1),
                    Section::make('Câu hỏi thường gặp')->description('Hiện thành một mục riêng ở cuối bài.')->schema([
                        FaqEditor::make('faqs')->hiddenLabel(),
                    ])->collapsible(),
                    Section::make('SEO')->schema([
                        TextInput::make('data.focus_keyword')->label('Từ khóa chính')->maxLength(100)
                            ->helperText('Cụm từ bạn muốn bài này đứng đầu. Chỉ dùng để chấm điểm, không hiện trên web.'),
                        SeoTitleInput::make('meta_title'),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Thiết lập bài viết')->schema([
                        Select::make('category_id')->label('Danh mục')
                            ->relationship('category', 'name')->searchable()->preload(),
                        DateTimePicker::make('published_at')->label('Ngày xuất bản')->seconds(false)
                            ->helperText('Bỏ trống: tự ghi lúc xuất bản. Chọn thời điểm sau này để hẹn giờ.'),
                        Toggle::make('is_published')->label('Đã xuất bản')->default(true),
                        Toggle::make('is_featured')->label('Nổi bật'),
                        TextEntry::make('author.name')->label('Tác giả')->placeholder(fn (): string => (string) site_config('name'))->visibleOn('edit'),
                        TextEntry::make('created_at')->label('Ngày tạo')->dateTime('d/m/Y H:i')->visibleOn('edit'),
                        TextEntry::make('content_updated_at')->label('Cập nhật nội dung')->dateTime('d/m/Y H:i')
                            ->placeholder('Chưa cập nhật sau khi xuất bản')->visibleOn('edit'),
                    ])->columns(1),
                    Section::make('Hình ảnh')->schema([
                        CuratorPicker::make('featured_media_id')->label('Ảnh đại diện')->constrained(),
                        CuratorPicker::make('og_media_id')->label('Ảnh chia sẻ')->constrained()
                            ->helperText('Ảnh hiển thị khi chia sẻ liên kết. Để trống sẽ dùng ảnh đại diện.'),
                    ])->columns(1),
                    Section::make('Chấm điểm SEO')->schema([
                        View::make('filament.seo.panel'),
                    ]),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }
}
