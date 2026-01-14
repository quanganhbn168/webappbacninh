<?php

namespace App\Services;

use App\Models\AdBanner;

class AdBannerService
{
    public function create(array $data): AdBanner
    {
        $banner = AdBanner::create([
            'name' => $data['name'],
            'slot' => $data['slot'],
            'link' => $data['link'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'open_new_tab' => $data['open_new_tab'] ?? true,
            'order' => $data['order'] ?? 0,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        // Handle LFM path (string)
        if (!empty($data['banner_image'])) {
            $this->addMediaFromPath($banner, $data['banner_image'], 'banner_image');
        }

        return $banner;
    }

    public function update(AdBanner $banner, array $data): AdBanner
    {
        $banner->update([
            'name' => $data['name'],
            'slot' => $data['slot'],
            'link' => $data['link'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'open_new_tab' => $data['open_new_tab'] ?? true,
            'order' => $data['order'] ?? 0,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        // Handle LFM path (string) - only update if new image provided
        if (!empty($data['banner_image']) && $data['banner_image'] !== $banner->getFirstMediaUrl('banner_image')) {
            $banner->clearMediaCollection('banner_image');
            $this->addMediaFromPath($banner, $data['banner_image'], 'banner_image');
        }

        return $banner;
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

    public function delete(AdBanner $banner): bool
    {
        $banner->clearMediaCollection('banner_image');
        return $banner->delete();
    }

    public function updateOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            AdBanner::where('id', $id)->update(['order' => $index]);
        }
    }

    public function bulkDelete(array $ids): int
    {
        $banners = AdBanner::whereIn('id', $ids)->get();
        
        foreach ($banners as $banner) {
            $banner->clearMediaCollection('banner_image');
            $banner->delete();
        }

        return count($banners);
    }
}
