<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\UploadedFile;

class ProjectService
{
    /**
     * Create a new project.
     */
    public function create(array $data, ?UploadedFile $image = null): Project
    {
        $project = Project::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'link' => $data['link'] ?? null,
            'category' => $data['category'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'order' => $data['order'] ?? 0,
        ]);

        if ($image) {
            $project->addMedia($image)->toMediaCollection('featured_image');
        }

        return $project;
    }

    /**
     * Update an existing project.
     */
    public function update(Project $project, array $data, ?UploadedFile $image = null): Project
    {
        $project->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'link' => $data['link'] ?? null,
            'category' => $data['category'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'order' => $data['order'] ?? 0,
        ]);

        if ($image) {
            $project->clearMediaCollection('featured_image');
            $project->addMedia($image)->toMediaCollection('featured_image');
        }

        return $project;
    }

    /**
     * Delete a project.
     */
    public function delete(Project $project): bool
    {
        $project->clearMediaCollection('featured_image');
        return $project->delete();
    }

    /**
     * Update sorting order.
     */
    public function updateOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Project::where('id', $id)->update(['order' => $index]);
        }
    }

    /**
     * Bulk delete projects.
     */
    public function bulkDelete(array $ids): int
    {
        $projects = Project::whereIn('id', $ids)->get();
        
        foreach ($projects as $project) {
            $project->clearMediaCollection('featured_image');
            $project->delete();
        }

        return count($projects);
    }
}
