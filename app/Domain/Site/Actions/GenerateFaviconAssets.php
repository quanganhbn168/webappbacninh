<?php

namespace App\Domain\Site\Actions;

use App\Domain\Site\Data\GeneratedFaviconData;
use App\Domain\Site\Exceptions\InvalidFaviconSource;
use GdImage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

final class GenerateFaviconAssets
{
    private const GENERATOR_VERSION = 'favicon-set-v1';

    /** @var array<int, int> */
    private const BROWSER_SIZES = [16, 32, 48, 96, 144, 192, 512];

    /** @var array<int, int> */
    private const APPLE_SIZES = [120, 152, 167, 180];

    /** @var array<int, int> */
    private const MASKABLE_SIZES = [192, 512];

    public function execute(
        string $sourcePath,
        string $maskableSourcePath = '',
        string $backgroundColor = '#ffffff',
        bool $force = false,
    ): GeneratedFaviconData {
        if (! extension_loaded('gd') || ! function_exists('imagecreatefromstring')) {
            throw new InvalidFaviconSource('Server cần bật PHP GD để tự động tạo bộ favicon.');
        }

        $disk = Storage::disk('public');
        $sourceBytes = $this->readSource($disk, $sourcePath);
        $maskableBytes = trim($maskableSourcePath) === '' ? '' : $this->readSource($disk, $maskableSourcePath);
        $backgroundColor = $this->normalizeColor($backgroundColor, '#ffffff');

        $this->assertRasterSource($sourceBytes, 'Favicon nguồn');

        if ($maskableBytes !== '') {
            $this->assertRasterSource($maskableBytes, 'Maskable icon');
        }

        $version = substr(hash('sha256', implode("\0", [
            self::GENERATOR_VERSION,
            $sourceBytes,
            $maskableBytes,
            $backgroundColor,
        ])), 0, 16);
        $directory = 'site/branding/favicon/generated/'.$version;
        $result = new GeneratedFaviconData($version, $directory);

        if (! $force && $this->isComplete($disk, $directory)) {
            return $result;
        }

        $source = $this->decode($sourceBytes, 'Favicon nguồn');
        $maskableSource = $maskableBytes === '' ? null : $this->decode($maskableBytes, 'Maskable icon');

        try {
            $pngsForIco = [];

            foreach (self::BROWSER_SIZES as $size) {
                $png = $this->renderPng($source, $size);
                $this->putOrFail($disk, $directory."/favicon-{$size}x{$size}.png", $png);

                if (in_array($size, [16, 32, 48], true)) {
                    $pngsForIco[$size] = $png;
                }
            }

            foreach (self::APPLE_SIZES as $size) {
                $png = $this->renderPng($source, $size, $backgroundColor);
                $this->putOrFail($disk, $directory."/apple-touch-icon-{$size}x{$size}.png", $png);
            }

            foreach (self::MASKABLE_SIZES as $size) {
                $png = $this->renderPng(
                    $maskableSource ?? $source,
                    $size,
                    $backgroundColor,
                    $maskableSource === null ? 0.56 : 1.0,
                );
                $this->putOrFail($disk, $directory."/maskable-icon-{$size}x{$size}.png", $png);
            }

            $this->putOrFail($disk, $directory.'/favicon.ico', $this->buildIco($pngsForIco));
        } finally {
            imagedestroy($source);

            if ($maskableSource instanceof GdImage) {
                imagedestroy($maskableSource);
            }
        }

        if (! $this->isComplete($disk, $directory)) {
            throw new InvalidFaviconSource('Không thể tạo đủ bộ favicon. Vui lòng kiểm tra quyền ghi storage/app/public.');
        }

        return $result;
    }

    private function readSource(FilesystemAdapter $disk, string $path): string
    {
        $path = ltrim(trim($path), '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if ($path === '' || str_contains($path, '..') || preg_match('~^(?:[a-z]:|\\\\|https?://)~i', $path)) {
            throw new InvalidFaviconSource('Đường dẫn favicon nguồn không hợp lệ.');
        }

        if ($disk->exists($path)) {
            $contents = $disk->get($path);

            if ($contents !== '') {
                return $contents;
            }
        }

        $publicPath = public_path($path);

        if (is_file($publicPath)) {
            $contents = file_get_contents($publicPath);

            if (is_string($contents) && $contents !== '') {
                return $contents;
            }
        }

        throw new InvalidFaviconSource('Không đọc được file favicon nguồn trên public disk.');
    }

    private function assertRasterSource(string $contents, string $label): void
    {
        $dimensions = @getimagesizefromstring($contents);

        if (! is_array($dimensions)) {
            throw new InvalidFaviconSource($label.' phải là PNG, WebP hoặc JPEG hợp lệ.');
        }

        [$width, $height] = $dimensions;

        if ($width < 512 || $height < 512) {
            throw new InvalidFaviconSource($label.' phải có kích thước tối thiểu 512x512 px.');
        }
    }

    private function decode(string $contents, string $label): GdImage
    {
        $image = @imagecreatefromstring($contents);

        if (! $image instanceof GdImage) {
            throw new InvalidFaviconSource('Không thể xử lý '.$label.'. Server cần bật PHP GD.');
        }

        return $image;
    }

    private function renderPng(
        GdImage $source,
        int $size,
        ?string $backgroundColor = null,
        float $contentScale = 1.0,
    ): string {
        $canvas = imagecreatetruecolor($size, $size);

        if (! $canvas instanceof GdImage) {
            throw new InvalidFaviconSource("Không thể tạo favicon {$size}x{$size}.");
        }

        $opaque = $backgroundColor !== null;

        if ($opaque) {
            [$red, $green, $blue] = $this->hexToRgb($backgroundColor);
            $background = imagecolorallocate($canvas, $red, $green, $blue);
            imagefill($canvas, 0, 0, $background);
            imagealphablending($canvas, true);
        } else {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefill($canvas, 0, 0, $transparent);
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $sourceSquare = min($sourceWidth, $sourceHeight);
        $sourceX = (int) floor(($sourceWidth - $sourceSquare) / 2);
        $sourceY = (int) floor(($sourceHeight - $sourceSquare) / 2);
        $targetSize = max(1, (int) floor($size * $contentScale));
        $targetX = (int) floor(($size - $targetSize) / 2);
        $targetY = (int) floor(($size - $targetSize) / 2);

        imagecopyresampled(
            $canvas,
            $source,
            $targetX,
            $targetY,
            $sourceX,
            $sourceY,
            $targetSize,
            $targetSize,
            $sourceSquare,
            $sourceSquare,
        );

        ob_start();
        imagepng($canvas, null, 9);
        $png = ob_get_clean();
        imagedestroy($canvas);

        if (! is_string($png) || $png === '') {
            throw new InvalidFaviconSource("Không thể mã hóa favicon {$size}x{$size}.");
        }

        return $png;
    }

    /** @param array<int, string> $pngs */
    private function buildIco(array $pngs): string
    {
        ksort($pngs);
        $header = pack('vvv', 0, 1, count($pngs));
        $directory = '';
        $images = '';
        $offset = 6 + (16 * count($pngs));

        foreach ($pngs as $size => $png) {
            $dimension = $size >= 256 ? 0 : $size;
            $directory .= pack('CCCCvvVV', $dimension, $dimension, 0, 0, 1, 32, strlen($png), $offset);
            $images .= $png;
            $offset += strlen($png);
        }

        return $header.$directory.$images;
    }

    private function putOrFail(FilesystemAdapter $disk, string $path, string $contents): void
    {
        if (! $disk->put($path, $contents, ['visibility' => 'public'])) {
            throw new InvalidFaviconSource('Không thể ghi file favicon vào '.$path.'.');
        }
    }

    private function isComplete(FilesystemAdapter $disk, string $directory): bool
    {
        $required = [
            'favicon.ico',
            'favicon-16x16.png',
            'favicon-32x32.png',
            'favicon-48x48.png',
            'favicon-96x96.png',
            'favicon-144x144.png',
            'favicon-192x192.png',
            'favicon-512x512.png',
            'apple-touch-icon-120x120.png',
            'apple-touch-icon-152x152.png',
            'apple-touch-icon-167x167.png',
            'apple-touch-icon-180x180.png',
            'maskable-icon-192x192.png',
            'maskable-icon-512x512.png',
        ];

        foreach ($required as $filename) {
            if (! $disk->exists($directory.'/'.$filename)) {
                return false;
            }
        }

        return true;
    }

    private function normalizeColor(string $color, string $fallback): string
    {
        $color = trim($color);

        return preg_match('/^#[0-9a-f]{6}$/i', $color) ? strtolower($color) : $fallback;
    }

    /** @return array{0: int, 1: int, 2: int} */
    private function hexToRgb(string $color): array
    {
        $color = ltrim($this->normalizeColor($color, '#ffffff'), '#');

        return [
            hexdec(substr($color, 0, 2)),
            hexdec(substr($color, 2, 2)),
            hexdec(substr($color, 4, 2)),
        ];
    }
}
