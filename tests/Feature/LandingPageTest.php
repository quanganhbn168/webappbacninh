<?php

namespace Tests\Feature;

use App\Enums\LandingTemplate;
use App\Filament\Resources\LandingPages\Pages\CreateLandingPage;
use App\Filament\Resources\LandingPages\Pages\EditLandingPage;
use App\Filament\Resources\LandingPages\Pages\ListLandingPages;
use App\Models\LandingPage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_the_two_sample_pages_keep_their_addresses_and_render_from_the_database(): void
    {
        $this->get('/landing/mau-may-loc-khong-khi-mau-1')->assertOk()
            ->assertSee('PureHome A3')
            ->assertSee('3.990.000')
            ->assertSee('Tiết kiệm 33%')
            ->assertSee('1900 8686')
            ->assertSee('rel="canonical" href="'.url('/landing/mau-may-loc-khong-khi-mau-1').'"', false);

        $this->get('/landing/mau-may-loc-khong-khi-mau-2')->assertOk()
            ->assertSee('FreshAir S8')
            ->assertSee('3.590.000đ')
            ->assertSee('Giảm 40%')
            ->assertSee('FRESH300');
    }

    public function test_content_edited_in_the_admin_changes_the_public_page(): void
    {
        $landing = LandingPage::query()->where('template', 'purehome')->firstOrFail();

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
        $landing = LandingPage::query()->where('template', 'freshair')->firstOrFail();
        $landing->update(['is_active' => false]);

        $this->get($landing->url())->assertNotFound();
        $this->get('/landing/khong-ton-tai')->assertNotFound();
    }

    public function test_noindex_is_written_to_the_page(): void
    {
        $landing = LandingPage::query()->where('template', 'purehome')->firstOrFail();

        $this->get($landing->url())->assertDontSee('noindex', false);

        $landing->update(['noindex' => true]);

        $this->get($landing->url())->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_a_field_added_to_a_template_later_still_has_a_value(): void
    {
        $landing = LandingPage::query()->where('template', 'purehome')->firstOrFail();
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
        Livewire::actingAs($this->admin(), 'admin')->test(CreateLandingPage::class)
            ->fillForm(['name' => 'Trùng', 'slug' => 'mau-may-loc-khong-khi-mau-1', 'price' => 1000])
            ->call('create')
            ->assertHasFormErrors(['slug']);

        Livewire::actingAs($this->admin(), 'admin')->test(CreateLandingPage::class)
            ->fillForm(['name' => 'Sai', 'slug' => 'co dau cach/và dấu', 'price' => 1000])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }

    public function test_admin_can_list_and_duplicate_a_page(): void
    {
        $landing = LandingPage::query()->where('template', 'purehome')->firstOrFail();

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
        $landing = LandingPage::query()->where('template', 'freshair')->firstOrFail();

        $this->get($landing->url())->assertSee('data-landing-lead', false)->assertSee('name="website"', false);

        $this->postJson('/lien-he', [
            'name' => 'Khách landing',
            'phone' => '0911222333',
            'need' => 'Tư vấn FreshAir S8',
            'message' => 'Nhu cầu sử dụng: Phòng ngủ',
            'source' => '/landing/mau-may-loc-khong-khi-mau-2',
        ])->assertCreated();

        $this->assertDatabaseHas('leads', ['phone' => '0911222333', 'need' => 'Tư vấn FreshAir S8', 'source' => '/landing/mau-may-loc-khong-khi-mau-2']);
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
