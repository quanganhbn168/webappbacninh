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
            'image' => $data['banner_image'] ?? null, // LFM path
            'link' => $data['link'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'open_new_tab' => $data['open_new_tab'] ?? true,
            'order' => $data['order'] ?? 0,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        if (!empty($data['banner_image'])) {
            $banner->importMediaFromLegacyPath($data['banner_image'], 'featured');
        }

        return $banner;
    }

    public function update(AdBanner $banner, array $data): AdBanner
    {
        $banner->update([
            'name' => $data['name'],
            'slot' => $data['slot'],
            'image' => $data['banner_image'] ?? $banner->image, // Keep old if not provided
            'link' => $data['link'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'open_new_tab' => $data['open_new_tab'] ?? true,
            'order' => $data['order'] ?? 0,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        if (!empty($data['banner_image'])) {
            $banner->importMediaFromLegacyPath($data['banner_image'], 'featured');
        }

        return $banner;
    }

    public function delete(AdBanner $banner): bool
    {
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
        return AdBanner::whereIn('id', $ids)->delete();
    }
}
