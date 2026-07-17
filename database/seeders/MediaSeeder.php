<?php

namespace Database\Seeders;

use App\Models\OperationService;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Template;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $this->attach(Template::all(), fn (Template $item) => public_path($item->image));
        $this->attach(Project::all(), fn (Project $item) => public_path($item->image));
        $this->attach(Post::all(), fn (Post $item) => public_path($item->featured_image));
        $this->attach(Service::all(), fn (Service $item) => public_path($item->image));
        $this->attach(OperationService::all(), fn (OperationService $item) => public_path($item->image));
    }

    private function attach(iterable $models, callable $pathResolver): void
    {
        foreach ($models as $model) {
            $path = $pathResolver($model);
            if (! is_file($path)) {
                continue;
            }
            $model->clearMediaCollection('featured');
            $model->addMedia($path)->preservingOriginal()->toMediaCollection('featured');
        }
    }
}
