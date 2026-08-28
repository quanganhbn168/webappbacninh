<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Site\Actions\ResolveFaviconAssets;
use App\Domain\Site\Data\FaviconAssetsData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class SiteIconController extends Controller
{
    public function favicon(ResolveFaviconAssets $resolveFaviconAssets): Response
    {
        return $this->respond($resolveFaviconAssets->execute(), 'favicon.ico', 'image/x-icon');
    }

    public function appleTouchIcon(ResolveFaviconAssets $resolveFaviconAssets): Response
    {
        return $this->respond(
            $resolveFaviconAssets->execute(),
            'apple-touch-icon-180x180.png',
            'image/png',
        );
    }

    private function respond(FaviconAssetsData $assets, string $filename, string $mime): Response
    {
        if (! $assets->generated) {
            return redirect()->to($assets->sourceUrl, 302, [
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        return Storage::disk('public')->response(
            $assets->path($filename),
            $filename,
            [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=3600',
            ],
        );
    }
}
