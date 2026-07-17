<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

/**
 * Imports images selected from the legacy Laravel File Manager into Spatie Media Library.
 *
 * Existing path columns remain untouched as a backwards-compatible fallback. New media is
 * copied to the model's Media Library collection and is therefore owned by that model.
 */
trait ImportsLegacyMedia
{
    public function importMediaFromLegacyPath(?string $source, string $collection): void
    {
        $mediaSource = $this->resolveLegacyMediaSource($source);

        if ($mediaSource === null || $this->hasImportedMediaSource($mediaSource['key'], $collection)) {
            return;
        }

        $adder = $mediaSource['disk'] !== null
            ? $this->addMediaFromDisk($mediaSource['path'], $mediaSource['disk'])
            : $this->addMedia($mediaSource['path']);

        $adder
            ->preservingOriginal()
            ->withCustomProperties(['legacy_source' => $mediaSource['key']])
            ->toMediaCollection($collection);
    }

    /**
     * @param  array<int, string|null>  $sources
     */
    public function importMediaCollectionFromLegacyPaths(array $sources, string $collection): void
    {
        foreach (array_unique(array_filter($sources)) as $source) {
            $this->importMediaFromLegacyPath($source, $collection);
        }
    }

    /**
     * @return array{key: string, path: string, disk: string|null}|null
     */
    private function resolveLegacyMediaSource(?string $source): ?array
    {
        $source = trim((string) $source);

        if ($source === '') {
            return null;
        }

        $path = parse_url($source, PHP_URL_PATH) ?: $source;
        $path = ltrim(rawurldecode($path), '/');

        if ($path === '' || str_contains($path, '..')) {
            return null;
        }

        $key = $path;
        $publicDiskPath = str_starts_with($path, 'storage/') ? substr($path, 8) : $path;

        if ($publicDiskPath !== '' && Storage::disk('public')->exists($publicDiskPath)) {
            return ['key' => $key, 'path' => $publicDiskPath, 'disk' => 'public'];
        }

        $publicPath = public_path($path);

        if (is_file($publicPath)) {
            return ['key' => $key, 'path' => $publicPath, 'disk' => null];
        }

        return null;
    }

    private function hasImportedMediaSource(string $source, string $collection): bool
    {
        $media = $this->getMedia($collection);

        return $media->contains(function ($item) use ($source): bool {
            if ($item->getCustomProperty('legacy_source') === $source) {
                return true;
            }

            return ltrim((string) parse_url($item->getUrl(), PHP_URL_PATH), '/') === $source;
        });
    }
}
