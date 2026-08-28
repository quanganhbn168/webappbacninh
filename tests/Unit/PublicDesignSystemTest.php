<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicDesignSystemTest extends TestCase
{
    public function test_public_css_uses_the_shared_type_scale_without_pixel_font_sizes(): void
    {
        $files = array_merge(
            [dirname(__DIR__, 2).'/resources/css/public-ui.css'],
            glob(dirname(__DIR__, 2).'/resources/css/frontend/*.css') ?: [],
        );

        foreach ($files as $file) {
            $css = (string) file_get_contents($file);
            $this->assertDoesNotMatchRegularExpression(
                '/font-size\s*:\s*\d+(?:\.\d+)?px/i',
                $css,
                $file.' contains a pixel font-size outside the shared type scale.',
            );
        }
    }

    public function test_public_tailwind_scan_is_isolated_from_filament_and_modules(): void
    {
        $css = (string) file_get_contents(dirname(__DIR__, 2).'/resources/css/app.css');

        $this->assertStringContainsString("@source '../views/frontend/**/*.php'", $css);
        $this->assertStringNotContainsString("@source '../../vendor/filament", $css);
        $this->assertStringNotContainsString("@source '../../Modules", $css);
    }

    public function test_public_head_uses_vite_and_has_no_remote_or_filament_font_stylesheet(): void
    {
        $head = (string) file_get_contents(dirname(__DIR__, 2).'/resources/views/frontend/site/layouts/head.php');

        $this->assertStringContainsString('resources/css/frontend/style.css', $head);
        $this->assertStringNotContainsString('frontend/assets/css/', $head);
        $this->assertStringNotContainsString('fonts.googleapis.com', $head);
        $this->assertStringNotContainsString('fonts/filament/filament/inter', $head);
    }

    public function test_deployment_runbook_builds_assets_and_laravel_caches(): void
    {
        $script = (string) file_get_contents(dirname(__DIR__, 2).'/deploy.sh');
        $environment = (string) file_get_contents(dirname(__DIR__, 2).'/.env.example');

        $this->assertStringContainsString('pnpm install --frozen-lockfile', $script);
        $this->assertStringContainsString('pnpm run build', $script);
        $this->assertStringContainsString('php artisan optimize', $script);
        $this->assertStringContainsString('CACHE_PREFIX=webappbacninh_cache_', $environment);
        $this->assertStringContainsString('SETTINGS_CACHE_ENABLED=true', $environment);
        $this->assertStringContainsString('SETTINGS_CACHE_MEMO=true', $environment);
    }
}
