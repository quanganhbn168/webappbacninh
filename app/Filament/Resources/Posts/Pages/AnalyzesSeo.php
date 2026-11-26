<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Domain\Content\ResolveUniqueSlug;
use App\Domain\Seo\AnalyzePost;
use App\Domain\Seo\StoreSeoScore;
use App\Models\Post;
use Awcodes\Curator\Components\Forms\RichEditor\AttachCuratorMediaPlugin;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\Arr;
use Livewire\Attributes\Renderless;

/** The SEO score panel of the article form (resources/views/filament/seo/panel.blade.php). */
trait AnalyzesSeo
{
    /**
     * Scores what is in the form now, saved or not. The form is not redrawn, so typing is
     * never interrupted.
     *
     * @return array<string, mixed>
     */
    #[Renderless]
    public function analyzeSeo(): array
    {
        return app(AnalyzePost::class)->execute($this->articleInForm());
    }

    /** A failure here must not undo the save it follows. */
    protected function storeSeoScore(): void
    {
        rescue(fn () => app(StoreSeoScore::class)->execute($this->getRecord()));
    }

    /** The article as the form holds it; never saved. */
    private function articleInForm(): Post
    {
        $state = $this->data ?? [];
        $record = $this->record instanceof Post ? $this->record : null;
        $post = $record ? (new Post)->newFromBuilder($record->getAttributes()) : new Post;
        $content = $state['content'] ?? '';

        // While editing, the editor holds a document tree rather than HTML.
        if (is_array($content)) {
            $content = RichContentRenderer::make($content)->plugins([AttachCuratorMediaPlugin::make()])->toUnsafeHtml();
        }

        $post->forceFill([
            ...Arr::only($state, ['title', 'summary', 'meta_title', 'meta_description', 'category_id', 'is_published', 'published_at']),
            'content' => (string) $content,
            'faqs' => array_values($state['faqs'] ?? []),
            'data' => array_merge($record?->data ?? [], $state['data'] ?? []),
            // The media picker holds the chosen picture as a list with its details.
            'featured_media_id' => data_get(Arr::first(Arr::wrap($state['featured_media_id'] ?? [])), 'id'),
            'og_media_id' => data_get(Arr::first(Arr::wrap($state['og_media_id'] ?? [])), 'id'),
        ]);
        // A new article will be signed by whoever saves it.
        if (! $record) {
            $post->user_id = auth('admin')->id();
        }
        $post->slug = app(ResolveUniqueSlug::class)->execute($post, (string) (($state['slug'] ?? '') ?: ($state['title'] ?? '')));

        return $post;
    }
}
