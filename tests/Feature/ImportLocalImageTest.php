<?php

namespace Tests\Feature;

use App\Domain\Media\Actions\ImportLocalImage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportLocalImageTest extends TestCase
{
    use DatabaseTransactions;

    private string $source;

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = storage_path('app/public/import-test-'.uniqid().'.png');
        File::ensureDirectoryExists(dirname($this->source));
        $image = imagecreatetruecolor(4, 3);
        imagepng($image, $this->source);
        imagedestroy($image);
    }

    protected function tearDown(): void
    {
        File::delete($this->source);

        parent::tearDown();
    }

    public function test_a_local_image_becomes_one_curator_media_record(): void
    {
        Storage::fake('public');
        $action = app(ImportLocalImage::class);

        $media = $action->execute('/storage/'.basename($this->source), 'tests', 'Ảnh kiểm thử');
        $again = $action->execute('storage/'.basename($this->source), 'tests', 'Ảnh kiểm thử');

        $this->assertNotNull($media);
        $this->assertSame($media->id, $again->id);
        $this->assertSame([4, 3, 'image/png'], [$media->width, $media->height, $media->type]);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_missing_or_outside_files_are_ignored(): void
    {
        $action = app(ImportLocalImage::class);

        $this->assertNull($action->execute('frontend/does-not-exist.webp', 'tests', 'x'));
        $this->assertNull($action->execute('../.env', 'tests', 'x'));
        $this->assertNull($action->execute('https://example.com/a.png', 'tests', 'x'));
        $this->assertNull($action->execute(null, 'tests', 'x'));
    }
}
