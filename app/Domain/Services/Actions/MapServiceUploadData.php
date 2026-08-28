<?php

namespace App\Domain\Services\Actions;

final class MapServiceUploadData
{
    /** @param array<string, mixed> $data
     *  @return array<string, mixed>
     */
    public function execute(array $data): array
    {
        foreach (['image_upload' => 'image', 'secondary_image_upload' => 'secondary_image'] as $upload => $attribute) {
            $path = $data[$upload] ?? null;

            if (is_string($path) && trim($path) !== '') {
                $data[$attribute] = trim($path);
            }

            unset($data[$upload]);
        }

        return $data;
    }
}
