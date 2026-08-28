<?php

namespace Tests\Feature;

use App\Domain\Services\Actions\MapServiceUploadData;
use App\Domain\Settings\Actions\RenderTrackingCode;
use App\Domain\Settings\Actions\SaveSiteSettings;
use App\Domain\Settings\Rules\ValidPublicLink;
use App\Domain\Settings\Rules\ValidSocialLink;
use App\Filament\Pages\ManageSettings;
use App\Models\User;
use App\Settings\TrackingSettings;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class SettingsManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_settings_page_uses_native_tabs_and_mod_s_save_action(): void
    {
        $page = app(ManageSettings::class);
        $formSchema = $page->form(Schema::make($page));
        $tabs = $formSchema->getComponents()[0];
        $tabLabels = collect($tabs->getChildSchema()->getComponents())
            ->map(fn ($tab): string => (string) $tab->getLabel())
            ->all();

        $this->assertSame([
            'Thương hiệu',
            'SEO',
            'Liên hệ',
            'Mạng xã hội',
            'Tracking & mã nhúng',
            'Dịch vụ',
        ], $tabLabels);

        $contentSchema = $page->content(Schema::make($page));
        $form = $contentSchema->getComponents()[0];
        $actions = $form->getChildSchema(Form::FOOTER_SCHEMA_KEY)->getComponents()[0];
        $saveAction = $actions->getChildSchema()->getComponents()[0];

        $this->assertSame('save', $form->getLivewireSubmitHandler());
        $this->assertSame(['mod+s'], $saveAction->getKeyBindings());
    }

    public function test_super_admin_can_open_the_settings_and_service_management_pages(): void
    {
        $admin = User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->get('/admin/settings')
            ->assertOk()
            ->assertSee('Tracking &amp; mã nhúng', false)
            ->assertSee('Dịch vụ thiết kế website');

        $this->actingAs($admin, 'admin')->get('/admin/services')->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/operation-services')->assertOk();
    }

    public function test_google_tag_is_rendered_from_tracking_settings_on_public_layouts(): void
    {
        $settings = app(TrackingSettings::class);
        $settings->head_code = '<meta name="tracking-head-test" content="ok">';
        $settings->body_start_code = '<div id="tracking-body-start-test"></div>';
        $settings->body_end_code = '<div id="tracking-body-end-test"></div>';
        $settings->save();

        $tracking = app(RenderTrackingCode::class)->execute();

        $this->assertStringContainsString('G-ZEKMT39KKJ', $tracking->head);
        $this->assertStringContainsString('googletagmanager.com/gtag/js', $tracking->head);

        $response = $this->get('/')->assertOk();
        $response->assertSee('googletagmanager.com/gtag/js?id=G-ZEKMT39KKJ', false);
        $html = $response->getContent();

        $this->assertLessThan(strpos($html, '</head>'), strpos($html, 'tracking-head-test'));
        $this->assertGreaterThan(strpos($html, '<body'), strpos($html, 'tracking-body-start-test'));
        $this->assertLessThan(strpos($html, '</body>'), strpos($html, 'tracking-body-end-test'));

        $this->get('/login')
            ->assertOk()
            ->assertSee('googletagmanager.com/gtag/js?id=G-ZEKMT39KKJ', false);
    }

    public function test_service_upload_paths_are_mapped_without_overwriting_existing_images(): void
    {
        $action = app(MapServiceUploadData::class);

        $this->assertSame([
            'image' => 'services/featured/new.webp',
            'secondary_image' => 'legacy/secondary.webp',
        ], $action->execute([
            'image' => 'legacy/featured.webp',
            'secondary_image' => 'legacy/secondary.webp',
            'image_upload' => 'services/featured/new.webp',
            'secondary_image_upload' => null,
        ]));
    }

    public function test_standard_validation_opens_the_error_flow_and_shows_a_danger_notification(): void
    {
        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->set('data.social.facebook', '')
            ->set('data.social.youtube', '')
            ->set('data.website.site_url', 'not-a-url')
            ->call('save')
            ->assertHasFormErrors(['website.site_url' => 'url'])
            ->assertDispatched('form-validation-error')
            ->assertNotified('Chưa thể lưu cấu hình');
    }

    public function test_domain_validation_is_returned_to_the_field_and_reported_to_the_user(): void
    {
        $this->app->instance(SaveSiteSettings::class, new class
        {
            /** @param array<string, mixed> $data */
            public function execute(array $data): void
            {
                throw ValidationException::withMessages([
                    'data.website.site_favicon' => 'Không đọc được file favicon nguồn trên public disk.',
                ]);
            }
        });

        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->set('data.social.facebook', '')
            ->set('data.social.youtube', '')
            ->call('save')
            ->assertHasErrors(['data.website.site_favicon'])
            ->assertDispatched('form-validation-error')
            ->assertNotified('Chưa thể lưu cấu hình');
    }

    public function test_unexpected_save_failures_show_a_safe_persistent_error(): void
    {
        $this->app->instance(SaveSiteSettings::class, new class
        {
            /** @param array<string, mixed> $data */
            public function execute(array $data): void
            {
                throw new RuntimeException('Sensitive infrastructure detail.');
            }
        });

        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->set('data.social.facebook', '')
            ->set('data.social.youtube', '')
            ->call('save')
            ->assertNotified('Không thể lưu cấu hình')
            ->assertNotNotified('Đã lưu cấu hình website');
    }

    public function test_hardened_settings_fields_reject_invalid_public_values(): void
    {
        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->set('data.general.default_language', 'vietnamese')
            ->set('data.favicon.theme_color', 'navy')
            ->set('data.contact.phone_href', 'call-me')
            ->set('data.social.facebook', 'javascript:alert(1)')
            ->set('data.social.youtube', '')
            ->call('save')
            ->assertHasFormErrors([
                'general.default_language' => 'regex',
                'favicon.theme_color' => 'regex',
                'contact.phone_href' => 'regex',
                'social.facebook' => ValidSocialLink::class,
            ])
            ->assertNotified('Chưa thể lưu cấu hình');
    }

    public function test_wechat_profile_requires_both_an_id_and_a_qr_image(): void
    {
        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->set('data.social.wechat_id', 'webappbacninh')
            ->set('data.social.wechat_qr', null)
            ->call('save')
            ->assertHasFormErrors(['social.wechat_qr' => 'required_with'])
            ->assertNotified('Chưa thể lưu cấu hình');
    }

    public function test_secondary_phone_requires_a_complete_display_and_dial_pair(): void
    {
        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->set('data.contact.phone_secondary', '0222 333 444')
            ->set('data.contact.phone_secondary_href', '')
            ->call('save')
            ->assertHasFormErrors(['contact.phone_secondary_href' => 'required_with'])
            ->assertNotified('Chưa thể lưu cấu hình');

        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->set('data.contact.phone_secondary', '')
            ->set('data.contact.phone_secondary_href', '0222333444')
            ->call('save')
            ->assertHasFormErrors(['contact.phone_secondary' => 'required_with'])
            ->assertNotified('Chưa thể lưu cấu hình');
    }

    public function test_favicon_source_must_be_a_square_image_of_at_least_512_pixels(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->superAdmin(), 'admin')
            ->test(ManageSettings::class)
            ->fillForm([
                'website.site_favicon' => UploadedFile::fake()->image('rectangular-favicon.png', 1024, 512),
                'social.facebook' => '',
                'social.youtube' => '',
            ])
            ->call('save')
            ->assertHasFormErrors([
                'website.site_favicon' => 'Ảnh favicon nguồn phải vuông và có kích thước tối thiểu 512x512 px.',
            ])
            ->assertNotified('Chưa thể lưu cấu hình');
    }

    public function test_public_link_rule_accepts_safe_links_and_rejects_placeholders_or_script_schemes(): void
    {
        foreach (['', '#contact', '/lien-he', 'https://facebook.com/webappbacninh', 'tel:+84986123168', 'mailto:info@example.com'] as $value) {
            $this->assertFalse(Validator::make(['link' => $value], ['link' => [new ValidPublicLink]])->fails(), $value);
        }

        foreach (['#', 'facebook.com/page', '//evil.example', 'javascript:alert(1)', 'data:text/html,test'] as $value) {
            $this->assertTrue(Validator::make(['link' => $value], ['link' => [new ValidPublicLink]])->fails(), $value);
        }
    }

    public function test_social_link_rule_only_accepts_secure_public_urls(): void
    {
        foreach (['', 'https://wa.me/84986123168', 'https://zalo.me/84986123168', 'https://t.me/webappbacninh'] as $value) {
            $this->assertFalse(Validator::make(['link' => $value], ['link' => [new ValidSocialLink]])->fails(), $value);
        }

        foreach (['#contact', '/lien-he', 'http://example.com', 'tel:+84986123168', 'javascript:alert(1)'] as $value) {
            $this->assertTrue(Validator::make(['link' => $value], ['link' => [new ValidSocialLink]])->fails(), $value);
        }
    }

    private function superAdmin(): User
    {
        return User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))
            ->firstOrFail();
    }
}
