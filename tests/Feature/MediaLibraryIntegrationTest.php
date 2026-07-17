<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_legacy_file_manager_path_is_imported_into_spatie_media_library(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/test-project.jpg', 'legacy-image');

        $project = Project::create([
            'title' => 'Dự án kiểm thử media '.uniqid(),
        ]);

        $project->importMediaFromLegacyPath('/storage/photos/test-project.jpg', 'featured');

        $project->refresh();
        $media = $project->getFirstMedia('featured');

        $this->assertNotNull($media);
        $this->assertSame('storage/photos/test-project.jpg', $media->getCustomProperty('legacy_source'));
        $this->assertTrue($project->hasMedia('featured'));
        $this->assertSame($media->getUrl(), $project->image_url);
    }
}
