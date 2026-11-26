<?php

namespace App\Filament\Resources\TemplateCategories;

use App\Filament\Forms\SeoTitleInput;
use App\Filament\NavigationGroup;
use App\Filament\Resources\TemplateCategories\Pages\ManageTemplateCategories;
use App\Models\TemplateCategory;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Forms\RichEditor\AttachCuratorMediaPlugin;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class TemplateCategoryResource extends Resource
{
    protected static ?string $model = TemplateCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Portfolio;

    protected static ?string $navigationLabel = 'Ngành nghề';

    protected static ?string $modelLabel = 'ngành';

    protected static ?string $pluralModelLabel = 'Ngành nghề';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Tên')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn ($set, $get, ?string $state) => blank($get('slug')) ? $set('slug', Str::slug($state ?? '')) : null),
            TextInput::make('slug')->label('Đường dẫn')->required()->maxLength(255)->alphaDash()->unique(ignoreRecord: true)
                ->prefix('/mau-website/')
                ->helperText('Chữ thường không dấu. Đổi đường dẫn thì địa chỉ cũ báo lỗi 404 và phải sửa lại link trong Menu.'),
            Section::make('Nội dung trang ngành')->description('Hiện ở trang /mau-website/… dưới danh sách giao diện của ngành.')->schema([
                Textarea::make('description')->label('Đoạn giới thiệu')->rows(3)->maxLength(500),
                RichEditor::make('content')->label('Nội dung')
                    ->plugins([AttachCuratorMediaPlugin::make()])
                    ->disableToolbarButtons(['attachFiles'])
                    ->enableToolbarButtons(['attachCuratorMedia']),
                Repeater::make('faqs')->label('Câu hỏi thường gặp')->schema([
                    TextInput::make('q')->label('Câu hỏi')->required()->maxLength(255),
                    Textarea::make('a')->label('Trả lời')->required()->rows(3)->maxLength(2000),
                ])->itemLabel(fn (array $state): ?string => $state['q'] ?? null)
                    ->collapsible()->collapsed()->reorderable()->addActionLabel('Thêm câu hỏi')->defaultItems(0),
                CuratorPicker::make('banner_image_id')->label('Ảnh đầu trang')->constrained()
                    ->helperText('Không bắt buộc. Tỷ lệ 4:3.'),
            ])->columns(1),
            Section::make('Google')->schema([
                Toggle::make('is_published')->label('Cho Google lập chỉ mục')
                    ->helperText('Bật khi đã đọc và duyệt nội dung. Trang chỉ vào Google và sitemap khi có đoạn giới thiệu, nội dung và ít nhất một giao diện đang hiện.'),
                SeoTitleInput::make('meta_title'),
                Textarea::make('meta_description')->label('Mô tả SEO')->rows(3)->maxLength(500)
                    ->helperText('Bỏ trống sẽ dùng đoạn giới thiệu.'),
            ])->columns(1),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tên')->searchable()->sortable(),
                TextColumn::make('slug')->label('Đường dẫn')->prefix('/mau-website/')->searchable(),
                TextColumn::make('templates_count')->label('Số giao diện')->counts('templates')->sortable(),
                TextColumn::make('projects_count')->label('Số dự án')->counts('projects')->sortable(),
                IconColumn::make('is_published')->label('Google')->boolean(),
            ])
            ->recordActions([
                Action::make('view')->label('Xem trang')->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (TemplateCategory $record): string => $record->url)->openUrlInNewTab()
                    ->visible(fn (TemplateCategory $record): bool => $record->templates()->where('is_active', true)->exists()),
                EditAction::make()->modalWidth(Width::FiveExtraLarge),
                DeleteAction::make()
                    ->hidden(fn (TemplateCategory $record): bool => $record->templates()->exists() || $record->projects()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTemplateCategories::route('/'),
        ];
    }
}
