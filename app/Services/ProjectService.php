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
        return Project::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'image' => $data['featured_image'] ?? null, // LFM path
            'link' => $data['link'] ?? null,
            'category' => $data['category'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'order' => $data['order'] ?? 0,
        ]);
    }

    /**
     * Update an existing project.
     */
    public function update(Project $project, array $data): Project
    {
        $project->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'image' => $data['featured_image'] ?? $project->image, // Keep old if not provided
            'link' => $data['link'] ?? null,
            'category' => $data['category'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'order' => $data['order'] ?? 0,
        ]);

        return $project;
    }

    /**
     * Delete a project.
     */
    public function delete(Project $project): bool
    {
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
        return Project::whereIn('id', $ids)->delete();
    }
}
