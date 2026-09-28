<?php

namespace Tests\Feature;

use App\Enums\TemplateType;
use App\Filament\Resources\Templates\Pages\CreateTemplate;
use App\Models\Slug;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\ThemeFeature;
use App\Models\User;
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

    public function test_hidden_template_is_not_public(): void
    {
        $template = Template::query()->active()->firstOrFail();
        $template->update(['is_active' => false]);

        $this->get(route('themes.show', $template->slug))->assertNotFound();
        $this->get(route('themes.index'))->assertDontSee($template->url);
    }

    public function test_root_slug_of_a_template_redirects_to_its_detail_page(): void
    {
        $template = Template::query()->active()->firstOrFail();
        Slug::query()->updateOrCreate(['reference_type' => $template->getMorphClass(), 'reference_id' => $template->id], ['key' => $template->slug]);

        $this->get('/'.$template->slug)->assertRedirect(route('themes.show', $template->slug));
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
