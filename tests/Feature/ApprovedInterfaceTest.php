<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApprovedInterfaceTest extends TestCase
{
    public function test_approved_pages_use_local_assets_and_shared_navigation(): void
    {
        foreach (['/', '/dich-vu', '/hosting-domain-email', '/giai-phap', '/san-pham', '/du-an', '/bang-gia', '/kien-thuc', '/lien-he'] as $uri) {
            $response = $this->get($uri)->assertOk();
            $response->assertSee('/build/assets/frontend-', false);
            $html = $response->getContent();
            $this->assertSame(1, substr_count($html, '<header '), $uri);
            $this->assertSame(1, substr_count($html, '<footer '), $uri);
            $this->assertStringNotContainsString('cdn.tailwindcss.com', $html);
            $this->assertStringNotContainsString('fonts.googleapis.com', $html);
            $this->assertStringNotContainsString('adminlte', strtolower($html));
            preg_match_all('~(?:src|data-image)="(/frontend/images/[^"?]+)"~', $html, $matches);
            foreach (array_unique($matches[1]) as $asset) {
                $this->assertFileExists(public_path(ltrim($asset, '/')), $uri.': '.$asset);
            }
        }
    }
}
