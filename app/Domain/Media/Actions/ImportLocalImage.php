<?php

namespace App\Domain\Media\Actions;

use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Storage;

/**
 * Copies an image that already lives under public/ or storage/app/public
 * into the Curator library. The same file is only stored once.
 */
final class ImportLocalImage
{
    public function execute(?string $path, string $directory, string $title): ?Media
    {
        $source = $this->localPath($path);
        $image = $source ? @getimagesize($source) : false;

        if (! $source || ! $image) {
            return null;
        }

        $extension = image_type_to_extension($image[2], false);
        $target = trim($directory, '/').'/'.hash_file('sha256', $source).'.'.$extension;
        $disk = Storage::disk('public');

        if (! $disk->exists($target)) {
            $disk->put($target, file_get_contents($source));
        }

        return Media::query()->firstOrCreate(['disk' => 'public', 'path' => $target], [
            'directory' => trim($directory, '/'),
            'visibility' => 'public',
            'name' => pathinfo($target, PATHINFO_FILENAME),
            'ext' => $extension,
            'type' => $image['mime'],
            'width' => $image[0],
            'height' => $image[1],
            'size' => filesize($source),
            'alt' => $title,
            'title' => $title,
        ]);
    }

    public function localPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (parse_url($path, PHP_URL_SCHEME)) {
            if (parse_url($path, PHP_URL_HOST) !== parse_url(config('app.url'), PHP_URL_HOST)) {
                return null;
            }
            $path = (string) parse_url($path, PHP_URL_PATH);
        }

        $relative = ltrim($path, '/');
        $candidate = match (true) {
            str_starts_with($path, base_path()) => realpath($path),
            str_starts_with($relative, 'storage/') => realpath(storage_path('app/public/'.substr($relative, 8))),
            default => realpath(public_path($relative)),
        };

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
