<?php

namespace Tests\Feature;

use App\Filament\Resources\TemplateCategories\Pages\ManageTemplateCategories;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class IndustryPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_an_industry_page_shows_its_designs_text_questions_and_links(): void
    {
        [$industry, $template] = $this->industry();

        $html = $this->get('/mau-website/'.$industry->slug)->assertOk()
            ->assertSeeInOrder(['<h1>Mẫu website '.mb_strtolower($industry->name).'</h1>', $template->url, 'Mục nội dung', 'Câu hỏi thử?', 'Trả lời thử.', 'id="tu-van-mau"'], false)
            ->assertSee('<link rel="canonical" href="'.$industry->url.'">', false)
            ->assertDontSee('Thiếu trả lời')
            ->assertDontSee('<script>alert', false)
            ->getContent();

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $match);
        $graph = collect(json_decode($match[1], true)['@graph'])->keyBy('@type');
        $this->assertSame(['Trang chủ', 'Kho giao diện', $industry->name], array_column($graph['BreadcrumbList']['itemListElement'], 'name'));
        $this->assertSame(['Câu hỏi thử?'], array_column($graph['FAQPage']['mainEntity'], 'name'));

        // The catalog and the design both lead to the industry page.
        $this->get(route('themes.index'))->assertSee('href="'.$industry->url.'"', false);
        $this->get($template->url)->assertSee('href="'.$industry->url.'"', false);
    }

    public function test_google_lists_the_page_only_when_it_is_turned_on_and_has_its_own_text(): void
    {
        [$industry] = $this->industry();
        $listed = function () use ($industry): bool {
            Cache::forget('sitemap:v5');
            $inSitemap = str_contains($this->get('/sitemap.xml')->getContent(), '<loc>'.$industry->url.'</loc>');
            $indexable = ! str_contains($this->get($industry->url)->assertOk()->getContent(), 'content="noindex, follow"');
            $this->assertSame($inSitemap, $indexable);

            return $indexable;
        };

        $this->assertTrue($listed());

        $industry->update(['is_published' => false]);
        $this->assertFalse($listed());

        $industry->update(['is_published' => true, 'content' => null]);
        $this->assertFalse($listed());
    }

    public function test_an_industry_without_a_visible_design_has_no_page(): void
    {
        [$industry, $template] = $this->industry();
        $template->update(['is_active' => false]);

        $this->get($industry->url)->assertNotFound();
        $this->get('/mau-website/khong-co-nganh-nay')->assertNotFound();
        $this->get('/mau-website')->assertNotFound();
    }

    public function test_duplicate_industries_are_merged_and_drafts_wait_for_review(): void
    {
        $this->assertDatabaseMissing('template_categories', ['slug' => 'ky-thuat']);
        $this->assertDatabaseMissing('template_categories', ['slug' => 'dich-vu-ky-thuat']);
        $this->assertDatabaseMissing('menu_items', ['url' => '/kho-giao-dien?nganh=spa']);

        foreach (array_keys(require database_path('data/industry-pages.php')) as $slug) {
            $industry = TemplateCategory::query()->where('slug', $slug)->first();

            if ($industry) {
                $this->assertNotEmpty($industry->faqItems(), $slug);
                $this->assertStringContainsString('<h2>', $industry->content, $slug);
            }
        }
    }

    public function test_the_admin_edits_the_page_of_an_industry(): void
    {
        [$industry] = $this->industry();
        $industry->update(['faqs' => [['q' => 'Một?', 'a' => 'Có.'], ['q' => 'Hai?', 'a' => 'Có.']]]);

        Livewire::actingAs($this->admin(), 'admin')->test(ManageTemplateCategories::class)
            ->callTableAction('edit', $industry, data: ['description' => 'Giới thiệu mới', 'is_published' => false])
            ->assertHasNoTableActionErrors();

        $this->assertSame('Giới thiệu mới', $industry->fresh()->description);
        $this->assertFalse($industry->fresh()->is_published);
        $this->assertCount(2, $industry->fresh()->faqs);
    }

    /** @return array{TemplateCategory, Template} */
    private function industry(): array
    {
        $suffix = Str::lower(Str::random(8));
        $industry = TemplateCategory::create([
            'name' => 'Ngành thử '.$suffix,
            'slug' => 'nganh-thu-'.$suffix,
            'description' => 'Giới thiệu ngành thử.',
            'content' => '<h2>Mục nội dung</h2><p>Chi tiết.</p><script>alert(1)</script>',
            'faqs' => [['q' => 'Câu hỏi thử?', 'a' => 'Trả lời thử.'], ['q' => 'Thiếu trả lời', 'a' => '']],
            'is_published' => true,
        ]);

        $template = Template::query()->active()->firstOrFail()->replicate();
        $template->forceFill(['code' => 'T-'.$suffix, 'slug' => 'mau-thu-'.$suffix, 'name' => 'Mẫu thử '.$suffix, 'template_category_id' => $industry->id])->save();

        return [$industry, $template->fresh()];
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}
