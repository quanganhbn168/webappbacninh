<?php

namespace App\Console\Commands;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Models\Post;
use Illuminate\Console\Command;

class ImportBlogMedia extends Command
{
    protected $signature = 'blog:import-media';

    protected $description = 'Copy legacy local blog images to Curator without replacing managed selections';

    public function handle(): int
    {
        $imported = 0;
        Post::where('curator_managed', false)->with('media')->chunkById(100, function ($posts) use (&$imported): void {
            foreach ($posts as $post) {
                $attributes = [];
                $missing = false;
                foreach (['featured' => 'featured_media_id', 'og' => 'og_media_id'] as $collection => $field) {
                    if ($post->{$field}) {
                        continue;
                    }
                    $legacy = $collection === 'featured' ? $post->featured_image : $post->og_image;
                    $media = $post->getFirstMedia($collection);
                    if (! $legacy && ! $media) {
                        continue;
                    }
                    $record = app(ImportLocalImage::class)->execute($media?->getPath() ?: $legacy, 'blog/imports', $post->title);
                    if (! $record) {
                        $this->warn("Post #{$post->id}: missing local {$collection} image; keeping legacy fallback.");
                        $missing = true;

                        continue;
                    }
                    $attributes[$field] = $record->id;
                    $imported++;
                }
                $post->forceFill($attributes + ['curator_managed' => ! $missing])->saveQuietly();
            }
        });
        $this->info("Imported {$imported} blog image selections. Original files and existing Curator selections preserved.");

        return self::SUCCESS;
    }
}
