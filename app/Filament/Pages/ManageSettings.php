<?php

namespace App\Filament\Pages;

use App\Domain\Settings\Actions\LoadSiteSettings;
use App\Domain\Settings\Actions\SaveSiteSettings;
use App\Filament\Resources\OperationServices\OperationServiceResource;
use App\Filament\Resources\ServiceCategories\ServiceCategoryResource;
use App\Filament\Resources\Services\ServiceResource;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Hệ thống';

    protected static ?string $navigationLabel = 'Cài đặt website';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Cài đặt website';

    protected static ?string $slug = 'settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    protected string $view = 'filament.pages.manage-settings';

    public function mount(): void
    {
        $this->form->fill(app(LoadSiteSettings::class)->execute());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Cài đặt website')
                    ->id('website-settings-tabs')
                    ->persistTab()
                    ->persistTabInQueryString('tab')
                    ->tabs([
                        Tab::make('Thương hiệu')
                            ->icon(Heroicon::OutlinedBuildingOffice)
                            ->schema([
                                Section::make('Thông tin chung')
                                    ->description('Thông tin nền tảng được dùng xuyên suốt website, SEO và dữ liệu cấu trúc.')
                                    ->schema([
                                        TextInput::make('general.name')->label('Tên hiển thị website')->required()->maxLength(255),
                                        TextInput::make('general.company_name')->label('Tên pháp lý / doanh nghiệp')->maxLength(255),
                                        TextInput::make('general.default_language')->label('Ngôn ngữ mặc định')->required()->maxLength(10),
                                        TextInput::make('website.site_url')->label('URL chính thức')->required()->url()->maxLength(255),
                                    ])
                                    ->columns(2),
                                Section::make('Logo và favicon')
                                    ->description('Ảnh được lưu trên public disk. Server cần có liên kết storage để hiển thị ngoài website.')
                                    ->schema([
                                        self::brandImage('website.site_logo_wide', 'Logo ngang', 'site/branding/logos', ['3:1', '4:1', '16:5']),
                                        self::brandImage('website.site_logo_white', 'Logo trắng / nền tối', 'site/branding/logos', ['3:1', '4:1', '16:5']),
                                        self::brandImage('website.site_logo_square', 'Logo vuông', 'site/branding/logos', ['1:1']),
                                        FileUpload::make('website.site_favicon')
                                            ->label('Favicon')
                                            ->disk('public')
                                            ->directory('site/branding/favicon')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/png', 'image/webp', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'])
                                            ->maxSize(2048)
                                            ->imagePreviewHeight('96')
                                            ->openable()
                                            ->downloadable()
                                            ->helperText('Nên dùng PNG/WebP vuông hoặc SVG; dung lượng tối đa 2 MB.'),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('SEO')
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                Section::make('SEO mặc định')
                                    ->schema([
                                        TextInput::make('seo.default_meta_title')->label('Meta title mặc định')->maxLength(255),
                                        TextInput::make('seo.default_meta_keywords')->label('Từ khóa mặc định')->maxLength(500),
                                        Textarea::make('seo.default_meta_description')->label('Meta description mặc định')->rows(3)->columnSpanFull(),
                                        self::brandImage('seo.default_og_image', 'Ảnh chia sẻ mặc định (OG)', 'site/seo', ['1.91:1', '16:9']),
                                        TextInput::make('seo.google_site_verification')->label('Google Search Console verification')->maxLength(255),
                                        CodeEditor::make('seo.page_meta_json')
                                            ->label('SEO theo từng trang (JSON)')
                                            ->language(Language::Json)
                                            ->json()
                                            ->wrap()
                                            ->columnSpanFull()
                                            ->helperText('Các key hiện dùng: home, about, contact, pricing, agency, services, themes, projects, articles, operations.'),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Liên hệ')
                            ->icon(Heroicon::OutlinedPhone)
                            ->schema([
                                Section::make('Thông tin liên hệ')
                                    ->schema([
                                        TextInput::make('contact.phone')->label('Số điện thoại hiển thị')->required()->maxLength(50),
                                        TextInput::make('contact.phone_href')->label('Số gọi')->required()->maxLength(50),
                                        TextInput::make('contact.email')->label('Email')->required()->email()->maxLength(255),
                                        TextInput::make('contact.working_time')->label('Thời gian làm việc')->maxLength(255),
                                        Textarea::make('contact.address')->label('Địa chỉ')->rows(3)->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Mạng xã hội')
                            ->icon(Heroicon::OutlinedShare)
                            ->schema([
                                Section::make('Kênh liên hệ và mạng xã hội')
                                    ->schema([
                                        TextInput::make('social.facebook')->label('Facebook')->url()->maxLength(2048),
                                        TextInput::make('social.messenger')->label('Messenger')->maxLength(2048),
                                        TextInput::make('social.zalo')->label('Zalo')->maxLength(2048),
                                        TextInput::make('social.telegram')->label('Telegram')->maxLength(2048),
                                        TextInput::make('social.wechat')->label('WeChat')->maxLength(2048),
                                        TextInput::make('social.whatsapp')->label('WhatsApp')->maxLength(2048),
                                        TextInput::make('social.youtube')->label('YouTube')->url()->maxLength(2048),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Tracking & mã nhúng')
                            ->icon(Heroicon::OutlinedCodeBracket)
                            ->schema([
                                Section::make('Google tag')
                                    ->description('Chỉ cần nhập Measurement/Tag ID; hệ thống tự sinh đoạn gtag.js đúng vị trí trong head.')
                                    ->schema([
                                        Toggle::make('tracking.enabled')->label('Bật tracking và mã nhúng')->default(true),
                                        TextInput::make('tracking.google_tag_id')
                                            ->label('Google tag ID')
                                            ->placeholder('G-XXXXXXXXXX')
                                            ->regex('/^(G|GT|GTM|AW|DC)-[A-Z0-9-]+$/i')
                                            ->maxLength(50),
                                    ])
                                    ->columns(2),
                                Section::make('Mã chèn theo vị trí')
                                    ->description('Chỉ dán mã từ nguồn tin cậy. Các đoạn này được render nguyên bản trên toàn bộ trang public.')
                                    ->schema([
                                        CodeEditor::make('tracking.head_code')
                                            ->label('Cuối thẻ <head>')
                                            ->language(Language::Html)
                                            ->wrap()
                                            ->helperText('Dùng cho verification, pixels hoặc script cần nằm trong head. Không cần dán lại Google tag nếu đã nhập ID ở trên.'),
                                        CodeEditor::make('tracking.body_start_code')
                                            ->label('Ngay sau thẻ <body>')
                                            ->language(Language::Html)
                                            ->wrap()
                                            ->helperText('Phù hợp với phần noscript của Google Tag Manager hoặc widget yêu cầu đầu body.'),
                                        CodeEditor::make('tracking.body_end_code')
                                            ->label('Trước thẻ đóng </body>')
                                            ->language(Language::Html)
                                            ->wrap()
                                            ->helperText('Phù hợp với widget chat và script tải cuối trang.'),
                                    ])
                                    ->columns(1),
                            ]),
                        Tab::make('Dịch vụ')
                            ->icon(Heroicon::OutlinedRectangleStack)
                            ->schema([
                                Section::make('Quản trị dịch vụ')
                                    ->description('Dịch vụ là nội dung có cấu trúc riêng, không lưu lẫn trong settings. Mở đúng Resource để quản lý dữ liệu, hình ảnh, SEO và thứ tự hiển thị.')
                                    ->schema([
                                        Actions::make([
                                            Action::make('manageServices')
                                                ->label('Dịch vụ thiết kế website')
                                                ->icon(Heroicon::OutlinedGlobeAlt)
                                                ->url(ServiceResource::getUrl('index')),
                                            Action::make('manageOperationServices')
                                                ->label('Dịch vụ vận hành')
                                                ->icon(Heroicon::OutlinedChartBarSquare)
                                                ->url(OperationServiceResource::getUrl('index')),
                                            Action::make('manageServiceCategories')
                                                ->label('Danh mục dịch vụ')
                                                ->icon(Heroicon::OutlinedRectangleStack)
                                                ->url(ServiceCategoryResource::getUrl('index')),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('settings-form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Lưu toàn bộ cấu hình')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        app(SaveSiteSettings::class)->execute($this->form->getState());

        Notification::make()
            ->title('Đã lưu cấu hình website')
            ->success()
            ->send();
    }

    /** @param array<int, string> $aspectRatios */
    private static function brandImage(string $name, string $label, string $directory, array $aspectRatios): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatioOptions($aspectRatios)
            ->maxSize(4096)
            ->imagePreviewHeight('140')
            ->openable()
            ->downloadable();
    }
}
