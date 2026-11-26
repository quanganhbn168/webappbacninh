<?php

namespace Tests\Feature;

use App\Enums\LandingTemplate;
use App\Filament\Resources\LandingPages\Pages\CreateLandingPage;
use App\Filament\Resources\LandingPages\Pages\EditLandingPage;
use App\Filament\Resources\LandingPages\Pages\ListLandingPages;
use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Filament\Resources\Templates\Pages\EditTemplate;
use App\Models\LandingPage;
use App\Models\Lead;
use App\Models\Template;
use App\Models\User;
use App\Settings\TrackingSettings;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_a_page_renders_from_the_database(): void
    {
        $this->get($this->page(LandingTemplate::Purehome)->url())->assertOk()
            ->assertSee('PureHome A3')
            ->assertSee('3.990.000')
            ->assertSee('Tiết kiệm 33%')
            ->assertSee('rel="canonical" href="'.url('/landing/trang-thu-purehome').'"', false);

        $this->get($this->page(LandingTemplate::Freshair)->url())->assertOk()
            ->assertSee('FreshAir S8')
            ->assertSee('3.590.000đ')
            ->assertSee('Giảm 40%')
            ->assertSee('FRESH300');
    }

    public function test_the_former_samples_are_demos_in_the_theme_catalog(): void
    {
        $this->assertDatabaseMissing('landing_pages', ['slug' => 'mau-may-loc-khong-khi-mau-1']);

        foreach ([1 => 'landing-purehome-may-loc-khong-khi', 2 => 'landing-freshair-may-loc-khong-khi'] as $number => $slug) {
            $this->get('/landing/mau-may-loc-khong-khi-mau-'.$number)->assertNotFound();
            $this->get(route('themes.show', $slug))->assertOk()->assertSee(route('themes.demo', $slug))->assertSee('Liên hệ');
            $this->get(route('themes.demo', $slug))->assertOk()
                ->assertSee('data-device="tablet"', false)
                ->assertSee('data-device="mobile"', false)
                ->assertSee('src="'.route('themes.demo.frame', $slug).'"', false)
                ->assertSee('noindex', false);
        }
    }

    public function test_a_demo_is_hidden_from_search_and_sends_and_measures_nothing(): void
    {
        $this->trackingSetting('<script>window.SITE_TRACKING=1</script>');

        foreach (['landing-purehome-may-loc-khong-khi', 'landing-freshair-may-loc-khong-khi'] as $slug) {
            $this->get(route('themes.demo.frame', $slug))->assertOk()
                ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
                ->assertSee('data-demo', false)
                ->assertSee('method="dialog"', false)
                ->assertDontSee('action="'.route('leads.store').'"', false)
                ->assertDontSee('SITE_TRACKING', false)
                ->assertDontSee('1900 8686')
                ->assertDontSee('1900 636 888');
        }
    }

    public function test_a_design_without_a_demo_has_no_preview(): void
    {
        $template = Template::query()->active()->whereNull('demo_layout')->firstOrFail();
        $template->update(['demo_url' => null]);

        $this->get(route('themes.demo', $template->slug))->assertNotFound();
        $this->get(route('themes.demo.frame', $template->slug))->assertNotFound();

        $template->update(['demo_url' => 'https://demo.example.com']);
        $this->get(route('themes.demo', $template->slug))->assertOk()->assertSee('src="https://demo.example.com"', false);
    }

    public function test_admin_edits_the_demo_inside_the_theme_catalog(): void
    {
        $template = Template::query()->where('slug', 'landing-purehome-may-loc-khong-khi')->firstOrFail();

        Livewire::actingAs($this->admin(), 'admin')->test(EditTemplate::class, ['record' => $template->getRouteKey()])
            ->fillForm(['demo.price' => 1234000, 'demo.content.hero_subtitle' => 'Dòng phụ trong kho'])
            ->call('save')
            ->assertHasNoFormErrors();

        $template->refresh();
        $this->assertSame(LandingTemplate::Purehome, $template->demo_layout);
        $this->assertNotEmpty($template->demo['content']['faqs']);
        $this->get(route('themes.demo.frame', $template->slug))->assertOk()
            ->assertSee('1.234.000')
            ->assertSee('Dòng phụ trong kho');
    }

    public function test_choosing_a_layout_fills_the_demo_with_its_sample(): void
    {
        $template = Template::query()->active()->where('type', 'landing-page')->whereNull('demo_layout')->firstOrFail();

        Livewire::actingAs($this->admin(), 'admin')->test(EditTemplate::class, ['record' => $template->getRouteKey()])
            ->fillForm(['demo_layout' => 'freshair'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('themes.demo.frame', $template->slug))->assertOk()->assertSee('FreshAir S8')->assertSee('3.590.000đ');
    }

    public function test_an_ad_page_carries_the_site_tracking_and_its_own_code(): void
    {
        $this->trackingSetting('<script>window.SITE_TRACKING=1</script>');
        $landing = $this->page(LandingTemplate::Purehome);
        $landing->update(['head_code' => '<script>window.CAMPAIGN_PIXEL=1</script>']);

        $this->get($landing->url())->assertOk()
            ->assertSee('window.SITE_TRACKING=1', false)
            ->assertSee('window.CAMPAIGN_PIXEL=1', false);

        $this->actingAs($this->admin(), 'admin')->get($landing->url())->assertOk()
            ->assertDontSee('SITE_TRACKING', false)
            ->assertDontSee('CAMPAIGN_PIXEL', false);
    }

    public function test_a_lead_remembers_its_landing_page_and_ad_campaign(): void
    {
        $landing = $this->page(LandingTemplate::Freshair);

        $this->postJson('/lien-he', [
            'name' => 'Khách quảng cáo',
            'phone' => '0911222444',
            'source' => '/landing/'.$landing->slug,
            'campaign' => ['utm_source' => 'google', 'utm_campaign' => 'khuyen-mai', 'gclid' => str_repeat('a', 900), 'khac' => 'bo', 'utm_term' => ['mảng']],
        ])->assertCreated();

        $lead = Lead::query()->where('phone', '0911222444')->firstOrFail();
        $this->assertSame($landing->id, $lead->landing_page_id);
        $this->assertSame('google · khuyen-mai', $lead->campaignLabel());
        $this->assertEqualsCanonicalizing(['utm_source', 'utm_campaign', 'gclid'], array_keys($lead->meta['campaign']));
        $this->assertSame(500, strlen($lead->meta['campaign']['gclid']));
        $this->assertSame(1, $landing->leads()->count());

        Livewire::actingAs($this->admin(), 'admin')->test(ListLeads::class)
            ->assertSee('google · khuyen-mai')
            ->mountTableAction('view', $lead)
            ->assertMountedActionModalSee('utm_campaign: khuyen-mai');
        Livewire::actingAs($this->admin(), 'admin')->test(ListLandingPages::class)->assertCanSeeTableRecords([$landing]);
    }

    public function test_content_edited_in_the_admin_changes_the_public_page(): void
    {
        $landing = $this->page(LandingTemplate::Purehome);

        Livewire::actingAs($this->admin(), 'admin')->test(EditLandingPage::class, ['record' => $landing->getRouteKey()])
            ->fillForm([
                'price' => 2500000,
                'compare_price' => 5000000,
                'meta_title' => 'Tiêu đề SEO kiểm thử',
                'content.hotline' => '0900 111 222',
                'content.hero_subtitle' => 'Dòng phụ kiểm thử',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get($landing->url())->assertOk()
            ->assertSee('<title>Tiêu đề SEO kiểm thử</title>', false)
            ->assertSee('2.500.000')
            ->assertSee('Tiết kiệm 50%')
            ->assertSee('0900 111 222')
            ->assertSee('Dòng phụ kiểm thử')
            ->assertSee('tel:0900111222', false);
    }

    public function test_an_inactive_or_unknown_landing_page_is_not_found(): void
    {
        $landing = $this->page(LandingTemplate::Freshair);
        $landing->update(['is_active' => false]);

        $this->get($landing->url())->assertNotFound();
        $this->get('/landing/khong-ton-tai')->assertNotFound();
    }

    public function test_noindex_is_written_to_the_page(): void
    {
        $landing = $this->page(LandingTemplate::Purehome);
        $landing->update(['noindex' => false]);

        $this->get($landing->url())->assertDontSee('noindex', false);

        $landing->update(['noindex' => true]);

        $this->get($landing->url())->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_a_field_added_to_a_template_later_still_has_a_value(): void
    {
        $landing = $this->page(LandingTemplate::Purehome);
        $content = $landing->content;
        unset($content['hero_title'], $content['faqs']);
        $landing->update(['content' => $content]);

        $this->get($landing->url())->assertOk()
            ->assertSee('Máy lọc không khí')
            ->assertSee('Câu hỏi thường gặp');
    }

    public function test_admin_creates_a_page_from_a_template_and_it_goes_live(): void
    {
        Livewire::actingAs($this->admin(), 'admin')->test(CreateLandingPage::class)
            ->assertFormSet(['template' => 'purehome'])
            ->fillForm([
                'template' => 'freshair',
                'name' => 'Máy lọc phòng ngủ',
                'slug' => 'may-loc-phong-ngu',
                'price' => 1990000,
                'compare_price' => 2490000,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $landing = LandingPage::query()->where('slug', 'may-loc-phong-ngu')->firstOrFail();
        $this->assertSame(LandingTemplate::Freshair, $landing->template);
        $this->assertArrayHasKey('specs', $landing->content);

        $this->get('/landing/may-loc-phong-ngu')->assertOk()
            ->assertSee('1.990.000đ')
            ->assertSee('Giảm 20%');
    }

    public function test_slug_must_be_unique_and_url_safe(): void
    {
        $this->page(LandingTemplate::Purehome);

        Livewire::actingAs($this->admin(), 'admin')->test(CreateLandingPage::class)
            ->fillForm(['name' => 'Trùng', 'slug' => 'trang-thu-purehome', 'price' => 1000])
            ->call('create')
            ->assertHasFormErrors(['slug']);

        Livewire::actingAs($this->admin(), 'admin')->test(CreateLandingPage::class)
            ->fillForm(['name' => 'Sai', 'slug' => 'co dau cach/và dấu', 'price' => 1000])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }

    public function test_admin_can_list_and_duplicate_a_page(): void
    {
        $landing = $this->page(LandingTemplate::Purehome);

        Livewire::actingAs($this->admin(), 'admin')->test(ListLandingPages::class)
            ->assertCanSeeTableRecords(LandingPage::query()->get())
            ->callTableAction('replicate', $landing)
            ->assertHasNoTableActionErrors();

        $copy = LandingPage::query()->where('name', $landing->name.' (bản sao)')->firstOrFail();
        $this->assertFalse($copy->is_active);
        $this->assertNotSame($landing->slug, $copy->slug);
        $this->assertSame($landing->content, $copy->content);
    }

    public function test_the_consult_form_on_a_landing_page_becomes_a_lead(): void
    {
        $landing = $this->page(LandingTemplate::Freshair);

        $this->get($landing->url())->assertSee('data-landing-lead', false)->assertSee('name="website"', false);

        $this->postJson('/lien-he', [
            'name' => 'Khách landing',
            'phone' => '0911222333',
            'need' => 'Tư vấn FreshAir S8',
            'message' => 'Nhu cầu sử dụng: Phòng ngủ',
            'source' => '/landing/trang-thu-freshair',
        ])->assertCreated();

        $this->assertDatabaseHas('leads', ['phone' => '0911222333', 'need' => 'Tư vấn FreshAir S8', 'source' => '/landing/trang-thu-freshair']);
    }

    private function page(LandingTemplate $template): LandingPage
    {
        return LandingPage::query()->firstOrCreate(['slug' => 'trang-thu-'.$template->value], $template->sample() + [
            'template' => $template,
            'content' => $template->defaults(),
        ])->fresh();
    }

    private function trackingSetting(string $head): void
    {
        $settings = app(TrackingSettings::class);
        $settings->enabled = true;
        $settings->head_code = $head;
        $settings->save();
        app()->forgetInstance(TrackingSettings::class);
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
