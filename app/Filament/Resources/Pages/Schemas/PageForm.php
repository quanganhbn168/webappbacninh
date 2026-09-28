<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Domain\Pages\Rules\AvailablePageSlug;
use App\Enums\PageTemplate;
use App\Filament\Forms\PermalinkInput;
use App\Models\Page;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
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

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        $usesBlocks = fn (Get $get): bool => self::template($get)->usesBlocks();

        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make('Thông tin trang')->schema([
                        TextInput::make('title')->label('Tiêu đề')->required()->maxLength(255)->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?Page $record, ?string $state): void {
                                if (! $record && ! $get('slug_manually_edited')) {
                                    $set('slug', Str::slug($state ?? ''));
                                }
                            }),
                        Hidden::make('slug_manually_edited')->default(false)->dehydrated(false),
                        PermalinkInput::make('slug')->prefix(url('/').'/')->required()
                            ->helperText('Tự tạo từ tiêu đề. Không được trùng với trang, bài viết hay dịch vụ khác.')
                            ->rule(fn (?Page $record): AvailablePageSlug => new AvailablePageSlug($record))
                            ->afterStateUpdated(fn (Set $set) => $set('slug_manually_edited', true)),
                        TextInput::make('short_title')->label('Tên ngắn')->maxLength(255)
                            ->helperText('Hiển thị ở breadcrumb và menu. Bỏ trống sẽ dùng tiêu đề.'),
                    ])->columns(1),
                    Section::make('Phần đầu trang')->visible($usesBlocks)->schema([
                        TextInput::make('eyebrow')->label('Dòng chữ nhỏ trên tiêu đề')->maxLength(255)
                            ->placeholder('THÔNG TIN VÀ CHÍNH SÁCH'),
                        Textarea::make('summary')->label('Đoạn mở đầu')->rows(3),
                        Textarea::make('notice')->label('Hộp lưu ý')->rows(2)
                            ->helperText('Hiển thị nổi bật ngay trên nội dung chính.'),
                    ])->columns(1),
                    Section::make('Nội dung')->visible($usesBlocks)->schema([
                        Builder::make('content')->hiddenLabel()->collapsible()->blockNumbers(false)
                            ->addActionLabel('Thêm khối nội dung')
                            ->blocks([
                                Block::make('section')->label(fn (?array $state): string => filled($state['heading'] ?? null) ? $state['heading'] : 'Mục nội dung')
                                    ->icon('heroicon-o-bars-3-bottom-left')
                                    ->schema([
                                        TextInput::make('heading')->label('Tiêu đề mục')->required()
                                            ->helperText('Tự động xuất hiện trong mục lục bên trái.'),
                                        Textarea::make('content')->label('Đoạn văn')->rows(3),
                                        Textarea::make('items')->label('Danh sách gạch đầu dòng')->rows(4)
                                            ->helperText('Mỗi dòng là một ý.'),
                                    ]),
                                Block::make('rich_text')->label('Văn bản tự do')->icon('heroicon-o-document-text')
                                    ->schema([
                                        RichEditor::make('body')->hiddenLabel()->required(),
                                    ]),
                                Block::make('callout')->label('Hộp lưu ý')->icon('heroicon-o-information-circle')
                                    ->schema([
                                        Textarea::make('text')->label('Nội dung')->rows(2)->required(),
                                    ]),
                            ]),
                    ]),
                    Section::make('SEO')->schema([
                        TextInput::make('meta_title')->label('Tiêu đề SEO')->maxLength(255)
                            ->helperText('Bỏ trống sẽ dùng "Tiêu đề | Tên website".'),
                        Textarea::make('meta_description')->label('Mô tả SEO')->rows(3),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                Group::make([
                    Section::make('Thiết lập')->schema([
                        Select::make('template')->label('Kiểu trang')->options(PageTemplate::options())
                            ->default(PageTemplate::Document->value)->required()->native(false)->live()
                            ->helperText(fn (Get $get): ?string => self::template($get)->usesBlocks()
                                ? null
                                : 'Giao diện thiết kế sẵn: nội dung nằm trong code, ở đây chỉ sửa được tiêu đề, đường dẫn và SEO.'),
                        TextInput::make('icon')->label('Biểu tượng')->placeholder('fa-solid fa-shield-halved')
                            ->helperText('Tên class Font Awesome.')->visible($usesBlocks),
                        DatePicker::make('content_updated_at')->label('Ngày cập nhật nội dung')
                            ->native(false)->displayFormat('d/m/Y')->visible($usesBlocks),
                        TextInput::make('order')->label('Thứ tự')->numeric()->default(0),
                        Toggle::make('is_active')->label('Đang hiển thị')->default(true),
                    ])->columns(1),
                ])->columnSpan(['default' => 1, 'lg' => 1]),
            ]);
    }

    private static function template(Get $get): PageTemplate
    {
        $state = $get('template');

        return $state instanceof PageTemplate ? $state : (PageTemplate::tryFrom((string) $state) ?? PageTemplate::Document);
    }
}
