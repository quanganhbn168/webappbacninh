<?php

namespace App\Traits;

use App\Models\Image;

trait HasImages
{
    /**
     * Get all of the model's images.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('order');
    }

    /**
     * Sync images from an array of URLs.
     * 
     * @param array $urls
     * @return void
     */
    public function syncImages(array $urls)
    {
        // 1. Delete existing images
        $this->images()->delete();

        // 2. Insert new images
        foreach ($urls as $index => $url) {
            if (!empty($url)) {
                $this->images()->create([
                    'url' => $url,
                    'order' => $index,
                ]);
            }
        }
    }
}
