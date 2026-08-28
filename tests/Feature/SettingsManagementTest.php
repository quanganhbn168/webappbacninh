<?php

namespace Tests\Feature;

use App\Domain\Services\Actions\MapServiceUploadData;
use App\Domain\Settings\Actions\RenderTrackingCode;
use App\Filament\Pages\ManageSettings;
use App\Models\User;
use App\Settings\TrackingSettings;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
}
