<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    /**
     * Create a new project.
     */
    public function create(array $data): Project
    {
        $project = Project::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'link' => $data['link'] ?? null,
            'category' => $data['category'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'order' => $data['order'] ?? 0,
        ]);

        // Handle LFM path (string)
        if (!empty($data['featured_image'])) {
            $this->addMediaFromPath($project, $data['featured_image'], 'featured_image');
        }

        return $project;
    }

    /**
     * Update an existing project.
     */
    public function update(Project $project, array $data): Project
    {
        $project->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'link' => $data['link'] ?? null,
            'category' => $data['category'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'order' => $data['order'] ?? 0,
        ]);

        // Handle LFM path (string) - only update if new image provided
        if (!empty($data['featured_image']) && $data['featured_image'] !== $project->getFirstMediaUrl('featured_image')) {
            $project->clearMediaCollection('featured_image');
            $this->addMediaFromPath($project, $data['featured_image'], 'featured_image');
        }

        return $project;
    }

    /**
     * Add media from LFM path.
     */
    private function addMediaFromPath($model, string $path, string $collection): void
    {
        // Convert relative path to absolute path
        $absolutePath = public_path(ltrim($path, '/'));
        
        if (file_exists($absolutePath)) {
            $model->addMedia($absolutePath)
                ->preservingOriginal()
                ->toMediaCollection($collection);
        }
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
