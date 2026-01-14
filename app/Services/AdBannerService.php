<?php

namespace App\Services;

use App\Models\AdBanner;
use Illuminate\Http\UploadedFile;

class AdBannerService
{
    public function create(array $data, ?UploadedFile $image = null): AdBanner
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

        if ($image) {
            $banner->addMedia($image)->toMediaCollection('banner_image');
        }

        return $banner;
    }

    public function update(AdBanner $banner, array $data, ?UploadedFile $image = null): AdBanner
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

        if ($image) {
            $banner->clearMediaCollection('banner_image');
            $banner->addMedia($image)->toMediaCollection('banner_image');
        }

        return $banner;
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
