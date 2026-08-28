<?php

namespace App\Filament\Pages;

use App\Domain\Settings\Actions\LoadSiteSettings;
use App\Domain\Settings\Actions\SaveSiteSettings;
use App\Domain\Settings\Rules\SquareRasterImage;
use App\Domain\Settings\Rules\ValidPublicLink;
use App\Filament\Resources\OperationServices\OperationServiceResource;
use App\Filament\Resources\ServiceCategories\ServiceCategoryResource;
use App\Filament\Resources\Services\ServiceResource;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Validation\ValidationException;
use Throwable;
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
                                        TextInput::make('general.default_language')
                                            ->label('Ngôn ngữ mặc định')
                                            ->required()
                                            ->regex('/^[a-z]{2,3}(?:-[A-Z]{2})?$/')
                                            ->maxLength(10)
                                            ->validationMessages([
                                                'regex' => 'Ngôn ngữ mặc định phải có dạng vi, en hoặc en-US.',
                                            ]),
                                        TextInput::make('website.site_url')
                                            ->label('URL chính thức')
                                            ->required()
                                            ->rules(['url:http,https'])
                                            ->maxLength(255)
                                            ->validationMessages([
                                                'url' => 'URL chính thức phải là địa chỉ http:// hoặc https:// hợp lệ.',
                                            ]),
                                    ])
                                    ->columns(2),
                                Section::make('Logo website')
                                    ->description('Ảnh được lưu trên public disk. Server cần có liên kết storage để hiển thị ngoài website.')
                                    ->schema([
                                        self::brandImage('website.site_logo_wide', 'Logo ngang', 'site/branding/logos', ['3:1', '4:1', '16:5']),
                                        self::brandImage('website.site_logo_white', 'Logo trắng / nền tối', 'site/branding/logos', ['3:1', '4:1', '16:5']),
                                        self::brandImage('website.site_logo_square', 'Logo vuông', 'site/branding/logos', ['1:1']),
                                    ])
                                    ->columns(3),
                                Section::make('Bộ favicon đa nền tảng')
                                    ->description('Khi lưu, hệ thống tự sinh ICO và PNG cho browser, Apple, Windows và PWA; URL có version để tránh cache favicon cũ.')
                                    ->schema([
                                        FileUpload::make('website.site_favicon')
                                            ->label('Ảnh favicon nguồn')
                                            ->disk('public')
                                            ->directory('site/branding/favicon/sources')
                                            ->visibility('public')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions(['1:1'])
                                            ->imageResizeTargetWidth('1024')
                                            ->imageResizeTargetHeight('1024')
                                            ->imageResizeUpscale(false)
                                            ->acceptedFileTypes(['image/png', 'image/webp', 'image/jpeg'])
                                            ->rules([new SquareRasterImage('Ảnh favicon nguồn')])
                                            ->maxSize(4096)
                                            ->imagePreviewHeight('160')
                                            ->openable()
                                            ->downloadable()
                                            ->helperText('Bắt buộc ảnh vuông tối thiểu 512x512; tốt nhất 1024x1024 PNG/WebP. Hệ thống sinh 16, 32, 48, 96, 120, 144, 152, 167, 180, 192 và 512 px.'),
                                        FileUpload::make('favicon.maskable_icon')
                                            ->label('Maskable icon riêng (không bắt buộc)')
                                            ->disk('public')
                                            ->directory('site/branding/favicon/sources')
                                            ->visibility('public')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions(['1:1'])
                                            ->imageResizeTargetWidth('1024')
                                            ->imageResizeTargetHeight('1024')
                                            ->imageResizeUpscale(false)
                                            ->acceptedFileTypes(['image/png', 'image/webp', 'image/jpeg'])
                                            ->rules([new SquareRasterImage('Maskable icon')])
                                            ->maxSize(4096)
                                            ->imagePreviewHeight('160')
                                            ->openable()
                                            ->downloadable()
                                            ->helperText('Dùng khi có thiết kế Android adaptive riêng. Nếu để trống, hệ thống tự tạo bản nền kín với logo nằm an toàn trong vùng 80%.'),
                                        TextInput::make('favicon.short_name')
                                            ->label('Tên ngắn khi cài lên màn hình')
                                            ->maxLength(30)
                                            ->placeholder('WebApp Bắc Ninh'),
                                        ColorPicker::make('favicon.theme_color')
                                            ->label('Màu giao diện trình duyệt')
                                            ->required()
                                            ->regex('/^#[0-9A-Fa-f]{6}$/')
                                            ->validationMessages(['regex' => 'Màu giao diện phải là mã HEX 6 ký tự, ví dụ #0f172a.']),
                                        ColorPicker::make('favicon.background_color')
                                            ->label('Màu nền Apple/PWA/maskable')
                                            ->required()
                                            ->regex('/^#[0-9A-Fa-f]{6}$/')
                                            ->validationMessages(['regex' => 'Màu nền phải là mã HEX 6 ký tự, ví dụ #ffffff.']),
                                        ColorPicker::make('favicon.safari_mask_color')
                                            ->label('Màu Safari pinned tab')
                                            ->required()
                                            ->regex('/^#[0-9A-Fa-f]{6}$/')
                                            ->validationMessages(['regex' => 'Màu Safari phải là mã HEX 6 ký tự, ví dụ #0f172a.']),
                                        FileUpload::make('favicon.safari_mask_icon')
                                            ->label('Safari pinned tab SVG (không bắt buộc)')
                                            ->disk('public')
                                            ->directory('site/branding/favicon/sources')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/svg+xml'])
                                            ->maxSize(512)
                                            ->openable()
                                            ->downloadable()
                                            ->helperText('SVG đơn sắc dành riêng cho rel="mask-icon" trên Safari macOS.'),
                                        TextInput::make('favicon.generated_version')
                                            ->label('Phiên bản favicon đang phát hành')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->placeholder('Chưa sinh bộ favicon')
                                            ->helperText('Mã này thay đổi theo nội dung ảnh/màu và được gắn vào URL để phá cache trình duyệt.')
                                            ->columnSpanFull(),
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
                                        Textarea::make('seo.default_meta_description')->label('Meta description mặc định')->rows(3)->maxLength(500)->columnSpanFull(),
                                        self::brandImage('seo.default_og_image', 'Ảnh chia sẻ mặc định (OG)', 'site/seo', ['1.91:1', '16:9']),
                                        TextInput::make('seo.google_site_verification')->label('Google Search Console verification')->maxLength(255),
                                        CodeEditor::make('seo.page_meta_json')
                                            ->label('SEO theo từng trang (JSON)')
                                            ->language(Language::Json)
                                            ->json()
                                            ->rules(['max:30000'])
                                            ->validationMessages([
                                                'json' => 'SEO theo từng trang phải là chuỗi JSON hợp lệ.',
                                                'max' => 'SEO theo từng trang không được vượt quá 30.000 ký tự.',
                                            ])
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
                                        TextInput::make('contact.phone_href')
                                            ->label('Số gọi')
                                            ->required()
                                            ->regex('/^\+?[0-9][0-9\s().-]{6,24}$/')
                                            ->maxLength(26)
                                            ->validationMessages([
                                                'regex' => 'Số gọi chỉ được chứa số, dấu +, khoảng trắng, dấu chấm, ngoặc hoặc gạch ngang.',
                                            ]),
                                        TextInput::make('contact.email')
                                            ->label('Email')
                                            ->required()
                                            ->email()
                                            ->maxLength(255)
                                            ->validationMessages(['email' => 'Email liên hệ chưa đúng định dạng.']),
                                        TextInput::make('contact.working_time')->label('Thời gian làm việc')->maxLength(255),
                                        Textarea::make('contact.address')->label('Địa chỉ')->rows(3)->maxLength(500)->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Mạng xã hội')
                            ->icon(Heroicon::OutlinedShare)
                            ->schema([
                                Section::make('Kênh liên hệ và mạng xã hội')
                                    ->schema([
                                        self::publicLink('social.facebook', 'Facebook'),
                                        self::publicLink('social.messenger', 'Messenger'),
                                        self::publicLink('social.zalo', 'Zalo'),
                                        self::publicLink('social.telegram', 'Telegram'),
                                        self::publicLink('social.wechat', 'WeChat'),
                                        self::publicLink('social.whatsapp', 'WhatsApp'),
                                        self::publicLink('social.youtube', 'YouTube'),
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
                                            ->maxLength(50)
                                            ->validationMessages([
                                                'regex' => 'Google tag ID phải có dạng G-, GT-, GTM-, AW- hoặc DC-.',
                                            ]),
                                    ])
                                    ->columns(2),
                                Section::make('Mã chèn theo vị trí')
                                    ->description('Chỉ dán mã từ nguồn tin cậy. Các đoạn này được render nguyên bản trên toàn bộ trang public.')
                                    ->schema([
                                        CodeEditor::make('tracking.head_code')
                                            ->label('Cuối thẻ <head>')
                                            ->language(Language::Html)
                                            ->rules(['max:100000'])
                                            ->validationMessages(['max' => 'Mã cuối thẻ <head> không được vượt quá 100.000 ký tự.'])
                                            ->wrap()
                                            ->helperText('Dùng cho verification, pixels hoặc script cần nằm trong head. Không cần dán lại Google tag nếu đã nhập ID ở trên.'),
                                        CodeEditor::make('tracking.body_start_code')
                                            ->label('Ngay sau thẻ <body>')
                                            ->language(Language::Html)
                                            ->rules(['max:100000'])
                                            ->validationMessages(['max' => 'Mã đầu thẻ <body> không được vượt quá 100.000 ký tự.'])
                                            ->wrap()
                                            ->helperText('Phù hợp với phần noscript của Google Tag Manager hoặc widget yêu cầu đầu body.'),
                                        CodeEditor::make('tracking.body_end_code')
                                            ->label('Trước thẻ đóng </body>')
                                            ->language(Language::Html)
                                            ->rules(['max:100000'])
                                            ->validationMessages(['max' => 'Mã cuối thẻ <body> không được vượt quá 100.000 ký tự.'])
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
        $state = $this->form->getState();

        try {
            app(SaveSiteSettings::class)->execute($state);
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $key => $messages) {
                foreach ($messages as $message) {
                    $this->addError($key, $message);
                }
            }

            $this->onValidationError($exception);
            $this->dispatch('form-validation-error', livewireId: $this->getId());

            return;
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Không thể lưu cấu hình')
                ->body('Hệ thống gặp lỗi khi ghi dữ liệu. Vui lòng thử lại; nếu lỗi lặp lại, hãy kiểm tra log máy chủ.')
                ->danger()
                ->persistent()
                ->send();

            return;
        }

        try {
            $this->form->fill(app(LoadSiteSettings::class)->execute());
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Đã lưu nhưng chưa thể tải lại dữ liệu')
                ->body('Cấu hình đã được ghi nhận. Hãy tải lại trang trước khi chỉnh sửa tiếp.')
                ->warning()
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->title('Đã lưu cấu hình website')
            ->success()
            ->send();
    }

    protected function onValidationError(ValidationException $exception): void
    {
        parent::onValidationError($exception);

        Notification::make()
            ->title('Chưa thể lưu cấu hình')
            ->body('Vui lòng kiểm tra các trường đang được đánh dấu lỗi. Hệ thống sẽ mở đúng tab có lỗi đầu tiên.')
            ->danger()
            ->persistent()
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

    private static function publicLink(string $name, string $label): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->rules([new ValidPublicLink])
            ->maxLength(2048)
            ->placeholder('https://... hoặc #contact');
    }
}
