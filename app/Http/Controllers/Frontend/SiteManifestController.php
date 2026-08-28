<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Site\Actions\BuildSiteManifest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class SiteManifestController extends Controller
{
    public function __invoke(BuildSiteManifest $buildSiteManifest): JsonResponse
    {
        return response()->json(
            $buildSiteManifest->execute(),
            200,
            [
                'Content-Type' => 'application/manifest+json; charset=UTF-8',
                'Cache-Control' => 'public, max-age=3600',
            ],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
    }
}
