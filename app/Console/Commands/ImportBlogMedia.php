<?php

namespace App\Console\Commands;

use App\Models\Post;
use Awcodes\Curator\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
                    $source = $media?->getPath() ?: $this->localPath($legacy);
                    if (! $source || ! is_file($source)) {
                        $this->warn("Post #{$post->id}: missing local {$collection} image; keeping legacy fallback.");
                        $missing = true;

                        continue;
                    }
                    $image = @getimagesize($source);
                    if (! $image) {
                        $missing = true;

                        continue;
                    }
                    $ext = image_type_to_extension($image[2], false);
                    $path = 'blog/imports/'.hash_file('sha256', $source).'.'.$ext;
                    $disk = Storage::disk('public');
                    if (! $disk->exists($path)) {
                        $disk->put($path, file_get_contents($source));
                    }
                    $record = Media::firstOrCreate(['disk' => 'public', 'path' => $path], [
                        'directory' => 'blog/imports', 'visibility' => 'public',
                        'name' => pathinfo($path, PATHINFO_FILENAME), 'ext' => $ext,
                        'type' => $image['mime'], 'width' => $image[0], 'height' => $image[1],
                        'size' => filesize($source), 'alt' => $post->title, 'title' => $post->title,
                    ]);
                    $attributes[$field] = $record->id;
                    $imported++;
                }
                $post->forceFill($attributes + ['curator_managed' => ! $missing])->saveQuietly();
            }
        });
        $this->info("Imported {$imported} blog image selections. Original files and existing Curator selections preserved.");

        return self::SUCCESS;
    }

    private function localPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (parse_url($path, PHP_URL_SCHEME)) {
            if (parse_url($path, PHP_URL_HOST) !== parse_url(config('app.url'), PHP_URL_HOST)) {
                return null;
            }
            $path = parse_url($path, PHP_URL_PATH);
        }
        $candidate = realpath(public_path(ltrim($path, '/')));
        if (! $candidate) {
            return null;
        }
        foreach ([public_path(), storage_path('app/public')] as $allowed) {
            $root = realpath($allowed);
            if ($root && str_starts_with(strtolower($candidate), strtolower($root.DIRECTORY_SEPARATOR))) {
                return $candidate;
            }
        }

        return null;
    }
}
