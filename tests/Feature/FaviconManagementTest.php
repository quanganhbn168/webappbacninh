<?php

namespace Tests\Feature;

use App\Domain\Settings\Actions\LoadSiteSettings;
use App\Domain\Settings\Actions\SaveSiteSettings;
use App\Domain\Site\Actions\GenerateFaviconAssets;
use App\Domain\Site\Exceptions\InvalidFaviconSource;
use App\Settings\FaviconSettings;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FaviconManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_generator_creates_real_browser_apple_pwa_maskable_and_ico_assets(): void
    {
        Storage::fake('public');
        $sourcePath = $this->putRasterSource();

        $generated = app(GenerateFaviconAssets::class)->execute($sourcePath, '', '#123456');
        $disk = Storage::disk('public');

        $expectedSizes = [
            'favicon-16x16.png' => 16,
            'favicon-32x32.png' => 32,
            'favicon-48x48.png' => 48,
            'favicon-96x96.png' => 96,
            'favicon-144x144.png' => 144,
            'favicon-192x192.png' => 192,
            'favicon-512x512.png' => 512,
            'apple-touch-icon-120x120.png' => 120,
            'apple-touch-icon-152x152.png' => 152,
            'apple-touch-icon-167x167.png' => 167,
            'apple-touch-icon-180x180.png' => 180,
            'maskable-icon-192x192.png' => 192,
            'maskable-icon-512x512.png' => 512,
        ];

        foreach ($expectedSizes as $filename => $size) {
            $path = $generated->directory.'/'.$filename;
            $disk->assertExists($path);
            $dimensions = getimagesizefromstring($disk->get($path));
            $this->assertIsArray($dimensions, $filename.' must be a readable image');
            $this->assertSame($size, $dimensions[0], $filename.' width');
            $this->assertSame($size, $dimensions[1], $filename.' height');
            $this->assertSame('image/png', $dimensions['mime'], $filename.' mime');
        }

        $ico = $disk->get($generated->directory.'/favicon.ico');
        $this->assertSame("\x00\x00\x01\x00", substr($ico, 0, 4));
        $this->assertSame(3, unpack('vcount', substr($ico, 4, 2))['count']);

        $maskable = imagecreatefromstring($disk->get($generated->directory.'/maskable-icon-512x512.png'));
        $corner = imagecolorsforindex($maskable, imagecolorat($maskable, 0, 0));
        $this->assertSame(['red' => 18, 'green' => 52, 'blue' => 86, 'alpha' => 0], $corner);
        imagedestroy($maskable);
    }

    public function test_saving_settings_builds_versioned_head_manifest_and_root_fallbacks(): void
    {
        Storage::fake('public');
        $sourcePath = $this->putRasterSource();
        $maskSvgPath = 'site/branding/favicon/sources/safari-mask.svg';
        Storage::disk('public')->put($maskSvgPath, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M0 0h10v10H0z"/></svg>');

        $data = app(LoadSiteSettings::class)->execute();
        $data['website']['site_favicon'] = $sourcePath;
        $data['favicon']['short_name'] = 'WebApp BN';
        $data['favicon']['theme_color'] = '#112233';
        $data['favicon']['background_color'] = '#f8fafc';
        $data['favicon']['safari_mask_color'] = '#334455';
        $data['favicon']['safari_mask_icon'] = $maskSvgPath;

        app(SaveSiteSettings::class)->execute($data);

        $faviconSettings = app(FaviconSettings::class);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{16}$/', $faviconSettings->generated_version);

        $home = $this->get('/')->assertOk();
        $home->assertSee('sizes="48x48"', false);
        $home->assertSee('sizes="96x96"', false);
        $home->assertSee('sizes="180x180"', false);
        $home->assertSee('rel="mask-icon"', false);
        $home->assertSee('rel="manifest"', false);
        $home->assertSee('content="#112233"', false);
        $home->assertSee('?v='.$faviconSettings->generated_version, false);

        $this->get('/login')
            ->assertOk()
            ->assertSee('sizes="48x48"', false)
            ->assertSee('rel="manifest"', false);

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('favicon-48x48.png', false);

        $manifest = $this->get('/site.webmanifest')->assertOk();
        $manifest->assertHeader('Content-Type', 'application/manifest+json; charset=UTF-8');
        $manifest->assertJsonPath('id', '/');
        $manifest->assertJsonPath('start_url', '/');
        $manifest->assertJsonPath('scope', '/');
        $manifest->assertJsonPath('short_name', 'WebApp BN');
        $manifest->assertJsonPath('theme_color', '#112233');
        $manifest->assertJsonPath('background_color', '#f8fafc');
        $manifest->assertJsonPath('icons.0.sizes', '192x192');
        $manifest->assertJsonPath('icons.1.sizes', '512x512');
        $manifest->assertJsonPath('icons.2.purpose', 'maskable');
        $manifest->assertJsonPath('icons.3.purpose', 'maskable');

        $this->get('/favicon.ico')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/x-icon');
        $this->get('/apple-touch-icon.png')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
        $this->get('/apple-touch-icon-precomposed.png')->assertOk();
    }

    public function test_generator_rejects_paths_outside_managed_public_assets(): void
    {
        Storage::fake('public');
        $this->expectException(InvalidFaviconSource::class);
        $this->expectExceptionMessage('Đường dẫn favicon nguồn không hợp lệ.');

        app(GenerateFaviconAssets::class)->execute('../.env');
    }

    private function putRasterSource(): string
    {
        $path = 'site/branding/favicon/sources/favicon-source.png';
        $upload = UploadedFile::fake()->image('favicon-source.png', 1024, 1024);
        Storage::disk('public')->put($path, file_get_contents($upload->getRealPath()));

        return $path;
    }
}
