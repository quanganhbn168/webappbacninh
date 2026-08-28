<?php

namespace App\Filament\Pages;

use App\Settings\ContactSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use App\Settings\WebsiteSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
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
        $general = app(GeneralSettings::class);
        $website = app(WebsiteSettings::class);
        $seo = app(SeoSettings::class);
        $contact = app(ContactSettings::class);
        $social = app(SocialSettings::class);

        $this->form->fill([
            'general' => [
                'name' => $general->name,
                'company_name' => $general->company_name,
                'default_language' => $general->default_language,
            ],
            'website' => [
                'site_url' => $website->site_url,
                'site_logo_wide' => $website->site_logo_wide,
                'site_logo_white' => $website->site_logo_white,
                'site_logo_square' => $website->site_logo_square,
                'site_favicon' => $website->site_favicon,
            ],
            'seo' => [
                'default_meta_title' => $seo->default_meta_title,
                'default_meta_description' => $seo->default_meta_description,
                'default_meta_keywords' => $seo->default_meta_keywords,
                'default_og_image' => $seo->default_og_image,
                'google_site_verification' => $seo->google_site_verification,
                'google_analytics_id' => $seo->google_analytics_id,
                'page_meta_json' => json_encode($seo->page_meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ],
            'contact' => [
                'phone' => $contact->phone,
                'phone_href' => $contact->phone_href,
                'email' => $contact->email,
                'address' => $contact->address,
                'working_time' => $contact->working_time,
            ],
            'social' => [
                'facebook' => $social->facebook,
                'messenger' => $social->messenger,
                'zalo' => $social->zalo,
                'telegram' => $social->telegram,
                'wechat' => $social->wechat,
                'whatsapp' => $social->whatsapp,
                'youtube' => $social->youtube,
            ],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cài đặt chung')
                    ->schema([
                        TextInput::make('general.name')->label('Tên hiển thị website')->required()->maxLength(255),
                        TextInput::make('general.company_name')->label('Tên pháp lý / doanh nghiệp')->maxLength(255),
                        TextInput::make('general.default_language')->label('Ngôn ngữ mặc định')->required()->maxLength(10),
                    ])
                    ->columns(3),
                Section::make('Website và nhận diện')
                    ->schema([
                        TextInput::make('website.site_url')->label('URL chính thức')->required()->url()->maxLength(255),
                        TextInput::make('website.site_logo_wide')->label('Logo ngang')->maxLength(2048),
                        TextInput::make('website.site_logo_white')->label('Logo trắng')->maxLength(2048),
                        TextInput::make('website.site_logo_square')->label('Logo vuông')->maxLength(2048),
                        TextInput::make('website.site_favicon')->label('Favicon')->maxLength(2048),
                    ])
                    ->columns(2),
                Section::make('SEO mặc định')
                    ->schema([
                        TextInput::make('seo.default_meta_title')->label('Meta title mặc định')->maxLength(255),
                        TextInput::make('seo.default_meta_keywords')->label('Từ khóa mặc định')->maxLength(500),
                        Textarea::make('seo.default_meta_description')->label('Meta description mặc định')->rows(3)->columnSpanFull(),
                        TextInput::make('seo.default_og_image')->label('Ảnh OG mặc định')->maxLength(2048),
                        TextInput::make('seo.google_site_verification')->label('Google Search Console verification')->maxLength(255),
                        TextInput::make('seo.google_analytics_id')->label('Google Analytics Measurement ID')->placeholder('G-XXXXXXXXXX')->maxLength(255),
                        Textarea::make('seo.page_meta_json')
                            ->label('SEO theo từng trang (JSON)')
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('Các key hiện dùng: home, about, contact, pricing, agency, services, themes, projects, articles, operations.'),
                    ])
                    ->columns(2),
                Section::make('Thông tin liên hệ')
                    ->schema([
                        TextInput::make('contact.phone')->label('Số điện thoại hiển thị')->required()->maxLength(50),
                        TextInput::make('contact.phone_href')->label('Số gọi')->required()->maxLength(50),
                        TextInput::make('contact.email')->label('Email')->required()->email()->maxLength(255),
                        TextInput::make('contact.address')->label('Địa chỉ')->maxLength(500),
                        TextInput::make('contact.working_time')->label('Thời gian làm việc')->maxLength(255),
                    ])
                    ->columns(2),
                Section::make('Mạng xã hội')
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
        $data = $this->form->getState();

        $this->saveSettings(app(GeneralSettings::class), Arr::get($data, 'general', []), ['name', 'company_name', 'default_language']);
        $this->saveSettings(app(WebsiteSettings::class), Arr::get($data, 'website', []), ['site_url', 'site_logo_wide', 'site_logo_white', 'site_logo_square', 'site_favicon']);

        $seo = Arr::get($data, 'seo', []);
        $seo['page_meta'] = json_decode((string) ($seo['page_meta_json'] ?? '{}'), true) ?: [];
        unset($seo['page_meta_json']);
        $this->saveSettings(app(SeoSettings::class), $seo, ['default_meta_title', 'default_meta_description', 'default_meta_keywords', 'default_og_image', 'google_site_verification', 'google_analytics_id', 'page_meta']);

        $this->saveSettings(app(ContactSettings::class), Arr::get($data, 'contact', []), ['phone', 'phone_href', 'email', 'address', 'working_time']);
        $this->saveSettings(app(SocialSettings::class), Arr::get($data, 'social', []), ['facebook', 'messenger', 'zalo', 'telegram', 'wechat', 'whatsapp', 'youtube']);

        Notification::make()
            ->title('Đã lưu cấu hình website')
            ->success()
            ->send();
    }

    /** @param array<string, mixed> $values */
    private function saveSettings(object $settings, array $values, array $fields): void
    {
        foreach ($fields as $field) {
            $value = $values[$field] ?? '';
            $settings->{$field} = is_array($value) ? $value : trim((string) $value);
        }

        $settings->save();
    }
}
