<?php

namespace Tests\Feature;

use App\Domain\Media\Actions\BuildResponsiveImage;
use App\Domain\Media\Actions\ResizeContentImages;
use App\Filament\Pages\ManageSettings;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Template;
use App\Models\User;
use App\Settings\WebsiteSettings;
use Awcodes\Curator\Models\Media;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ResponsiveImageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_every_upload_is_served_as_webp_in_sizes(): void
    {
        foreach (['png', 'jpg', 'webp'] as $extension) {
            $media = $this->media($extension);
            $image = app(BuildResponsiveImage::class)->execute($media, [480, 800, 1200]);

            $this->assertStringContainsString('/curator/'.$media->path.'?', $image['src'], $extension);
            $this->assertStringContainsString('w=800', $image['src'], $extension);
            $this->assertStringContainsString('fm=webp', $image['srcset'], $extension);
            $this->assertStringContainsString(' 480w, ', $image['srcset'], $extension);

            $this->blade('<x-img :src="$image" sizes="50vw" alt="Ảnh thử" />', compact('image'))
                ->assertSee('srcset=', false)->assertSee('sizes="50vw"', false)->assertDontSee('<picture>', false);
        }
    }

    public function test_the_logo_is_picked_from_the_library_and_resized(): void
    {
        $logo = $this->media('png');

        Livewire::actingAs($this->admin(), 'admin')->test(ManageSettings::class)
            ->set('data.website.logo_wide_media_id', [$logo->toArray()])
            ->set('data.website.logo_square_media_id', [])
            ->call('save')->assertHasNoFormErrors();
        app()->forgetInstance('site.settings');

        $this->assertSame($logo->id, app(WebsiteSettings::class)->refresh()->logo_wide_media_id);
        $this->assertNull(app(WebsiteSettings::class)->logo_square_media_id);
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertSame(2, substr_count($html, 'srcset="/curator/'.$logo->path));
        $this->assertStringContainsString('"logo":"'.absolute_url((string) $logo->url).'"', $html);
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }

    public function test_pictures_inside_an_article_are_resized_and_outside_pictures_are_left_alone(): void
    {
        $media = $this->media('png');
        $html = app(ResizeContentImages::class)->execute('<p><img src="'.$media->url.'" alt="Trong bài"></p><img src="https://example.com/storage/khac.png">');

        $this->assertStringContainsString('srcset="/curator/'.$media->path, $html);
        $this->assertStringContainsString('alt="Trong bài"', $html);
        $this->assertStringContainsString('<img loading="lazy"', $html);
        $this->assertStringContainsString('<img src="https://example.com/storage/khac.png">', $html);

        $post = Post::query()->published()->firstOrFail();
        $post->update(['content' => '<img src="'.$media->url.'" alt="Trong bài">']);
        $this->get($post->url)->assertOk()->assertSee('srcset="/curator/', false);
    }

    public function test_the_heading_of_a_fixed_page_is_edited_in_the_admin(): void
    {
        $this->get('/')->assertSee('<h1>Thiết kế website tại Bắc Ninh, <span class="page-hero__highlight">phần mềm và vận hành số</span></h1>', false);

        $page = Page::query()->where('key', 'home')->firstOrFail();
        $page->update(['banner_title' => 'Tiêu đề từ admin', 'banner_highlight' => null]);
        $this->get('/')->assertSee('<h1>Tiêu đề từ admin</h1>', false);

        $page->update(['banner_title' => null]);
        $this->get('/')->assertSee('<h1>'.e($page->title).'</h1>', false);
    }

    public function test_the_project_and_theme_galleries_show_their_pictures(): void
    {
        $media = [$this->media('png'), $this->media('jpg')];
        $project = Project::query()->where('is_active', true)->firstOrFail();
        $theme = Template::query()->active()->firstOrFail();

        foreach ([route('projects.show', $project->slug) => $project, route('themes.show', $theme->slug) => $theme] as $address => $record) {
            $record->update(['gallery' => collect($media)->pluck('id')->all()]);
            $html = $this->get($address)->assertOk()->getContent();

            // The main slides hold the picture directly; the thumbnails wrap theirs in a button.
            preg_match_all('#<div class="swiper-slide"[^>]*>\s*<img [^>]*>#', $html, $slides);
            $this->assertCount(2, $slides[0], $address);
            $this->assertStringContainsString('fetchpriority="high"', $slides[0][0]);
            $this->assertStringNotContainsString('loading="lazy"', $slides[0][0]);
            $this->assertStringContainsString('loading="lazy"', $slides[0][1]);
            $this->assertStringNotContainsString('fetchpriority', $slides[0][1]);
        }
    }

    /** A directive inside a component tag makes Blade print the tag instead of rendering it. */
    public function test_no_public_page_sends_an_unrendered_component_to_the_browser(): void
    {
        Cache::forget('sitemap:v5');
        preg_match_all('#<loc>([^<]+)</loc>#', $this->get('/sitemap.xml')->assertOk()->getContent(), $addresses);
        $this->assertNotEmpty($addresses[1]);

        foreach ($addresses[1] as $address) {
            $this->assertStringNotContainsString('<x-', $this->get($address)->assertOk()->getContent(), $address);
        }
    }

    private function media(string $extension): Media
    {
        $name = 'thu-'.Str::lower(Str::random(8));

        return Media::query()->create([
            'disk' => 'public', 'directory' => 'media', 'visibility' => 'public', 'name' => $name, 'path' => "media/{$name}.{$extension}",
            'width' => 1600, 'height' => 900, 'size' => 1000, 'type' => 'image/'.$extension, 'ext' => $extension,
        ]);
    }
}
