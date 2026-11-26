<?php

namespace Tests\Feature;

use App\Enums\TemplateType;
use App\Filament\Resources\Templates\Pages\CreateTemplate;
use App\Models\Slug;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\ThemeFeature;
use App\Models\User;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Facades\Curator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class TemplateManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_created_template_appears_in_the_catalog_and_detail_page(): void
    {
        $suffix = Str::lower(Str::random(6));
        $category = TemplateCategory::query()->create(['name' => 'Nhà hàng thử '.$suffix, 'slug' => 'nha-hang-thu-'.$suffix]);
        $feature = ThemeFeature::query()->create(['name' => 'Đặt bàn online '.$suffix, 'slug' => 'dat-ban-'.$suffix]);

        Livewire::actingAs($this->admin(), 'admin')->test(CreateTemplate::class)
            ->fillForm([
                'name' => 'Food House '.$suffix,
                'slug' => 'food-house-'.$suffix,
                'code' => 'TEST-'.$suffix,
                'type' => TemplateType::Service->value,
                'template_category_id' => $category->id,
                'description' => 'Mẫu nhà hàng có đặt bàn.',
                'price' => 6900000,
                'duration' => '5 - 7 ngày',
                'tags' => ['Thực đơn'],
                'pages' => [['value' => 'Trang chủ'], ['value' => 'Thực đơn'], ['value' => 'Đặt bàn']],
                'included_features' => [['value' => 'Đặt bàn online']],
                'features' => [$feature->id],
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get(route('themes.index'))->assertOk()
            ->assertSee('Food House '.$suffix)
            ->assertSee('<option value="nha-hang-thu-'.$suffix.'">', false)
            ->assertSee('value="dat-ban-'.$suffix.'"', false);

        $this->get(route('themes.show', 'food-house-'.$suffix))->assertOk()
            ->assertSee('TEST-'.$suffix)
            ->assertSee('Đặt bàn')
            ->assertSee('6.900.000đ');
    }

    public function test_visitors_get_resized_copies_of_a_large_screenshot_and_uploads_allow_it(): void
    {
        $template = Template::query()->active()->whereNotNull('image_id')->firstOrFail();

        $this->assertStringContainsString('fm=webp', $template->cover['src']);
        $this->assertNotSame($template->image->url, $template->slides[0]['src']);
        $this->get(route('themes.index'))->assertOk()
            ->assertSee('theme-card__window', false)
            ->assertSee(e($template->cover['src']), false)
            ->assertSee($template->url.'#chon-mau', false);

        $this->assertSame(30720, Curator::getMaxSize());
        $this->assertSame(30720, CuratorPicker::make('image_id')->getMaxSize());
        $this->assertContains('max:30720', config('livewire.temporary_file_upload.rules'));
    }

    public function test_detail_page_links_the_previous_and_next_design(): void
    {
        [$previous, $template, $next] = Template::query()->active()->ordered()->take(3)->get()->all();

        $this->get($template->url)->assertOk()
            ->assertSee('href="'.$previous->url.'" rel="prev"', false)
            ->assertSee('href="'.$next->url.'" rel="next"', false);
    }

    public function test_price_shows_a_discount_or_asks_to_get_in_touch(): void
    {
        $template = Template::query()->active()->ordered()->firstOrFail();

        $template->update(['price' => 2900000, 'sale_price' => 2320000]);
        $card = $this->blade('<x-card.theme :theme="$theme" />', ['theme' => $template->fresh()]);
        $card->assertSee('2.900.000đ')->assertSee('theme-price__sale', false)->assertSee('2.320.000đ')
            ->assertSee('data-price="2320000"', false)->assertDontSee('Giá từ');
        $this->get($template->url)->assertSee('theme-price__sale', false)->assertSee('"price":"2320000"', false);

        // A "sale" price that is not lower is no discount.
        $template->update(['sale_price' => 2900000]);
        $this->blade('<x-card.theme :theme="$theme" />', ['theme' => $template->fresh()])
            ->assertSee('Giá từ')->assertSee('2.900.000đ')->assertDontSee('<del>', false);

        $template->update(['price' => null, 'sale_price' => null]);
        $this->blade('<x-card.theme :theme="$theme" />', ['theme' => $template->fresh()])
            ->assertSee('Liên hệ')->assertDontSee('Giá từ');
        $this->get($template->url)->assertDontSee('Chi phí triển khai tham khảo từ')->assertDontSee('"offers"', false)->assertDontSee('"@type":"Product"', false);
    }

    public function test_hidden_template_is_not_public(): void
    {
        $template = Template::query()->active()->firstOrFail();
        $template->update(['is_active' => false]);

        $this->get(route('themes.show', $template->slug))->assertNotFound();
        $this->get(route('themes.index'))->assertDontSee($template->url);
    }

    public function test_a_template_has_no_page_at_the_site_root(): void
    {
        $template = Template::query()->active()->firstOrFail();
        Slug::query()->updateOrCreate(['reference_type' => $template->getMorphClass(), 'reference_id' => $template->id], ['key' => $template->slug]);

        $this->get('/'.$template->slug)->assertNotFound();
    }

    public function test_admin_lists_render(): void
    {
        foreach (['templates', 'template-categories', 'theme-features'] as $path) {
            $this->actingAs($this->admin(), 'admin')->get('/admin/'.$path)->assertOk();
        }
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
