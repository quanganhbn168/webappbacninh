<?php

namespace App\Domain\Site\Actions;

use Illuminate\Support\Facades\Storage;

final class ResolvePublicAssetUrl
{
    public function execute(?string $path, string $fallback = ''): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return $fallback === '' ? '' : asset(ltrim($fallback, '/'));
        }

        if (preg_match('~^(https?:)?//|^data:~i', $path)) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'storage/')) {
            return asset($normalized);
        }

        if (Storage::disk('public')->exists($normalized)) {
            return asset('storage/'.$normalized);
        }

        return asset($normalized);
    }
}
