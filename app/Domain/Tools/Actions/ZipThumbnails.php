<?php

namespace App\Domain\Tools\Actions;

use App\Domain\Tools\Exceptions\RemoteMediaRefused;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ZipArchive;

/**
 * Downloads several thumbnails into one ZIP for the bulk cover tool.
 * Links that are refused or fail are skipped.
 */
final class ZipThumbnails
{
    public const MAX_ITEMS = 50;

    /** Total size of the images put into one ZIP. */
    public const MAX_TOTAL_BYTES = 100 * 1024 * 1024;

    public function __construct(private readonly FetchRemoteMedia $fetch) {}

    /**
     * @param  array<int, array{image_url: string, filename?: ?string, provider: string}>  $items
     * @return string|null path of the temporary ZIP, or null when no image could be added
     */
    public function execute(array $items): ?string
    {
        $path = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new ZipArchive;
        if ($path === false || $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RemoteMediaRefused('Không thể tạo file ZIP.');
        }

        $added = 0;
        $total = 0;
        $names = [];
        foreach (array_slice($items, 0, self::MAX_ITEMS) as $index => $item) {
            try {
                $image = $this->fetch->read($item['image_url'], min(FetchRemoteMedia::IMAGE_MAX_BYTES, self::MAX_TOTAL_BYTES - $total));
            } catch (RemoteMediaRefused $exception) {
                Log::info('Bulk cover: skipped '.$item['image_url'].': '.$exception->getMessage());

                continue;
            }

            $total += strlen($image);
            $zip->addFromString($this->uniqueName($item, $index, $names), $image);
            $added++;
        }
        $zip->close();

        if ($added === 0) {
            @unlink($path);

            return null;
        }

        return $path;
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, true>  $names
     */
    private function uniqueName(array $item, int $index, array &$names): string
    {
        $base = Str::slug(Str::limit(trim((string) ($item['filename'] ?? '')), 150, '')) ?: Str::slug($item['provider']).'-'.($index + 1);
        $name = $base.'.jpg';
        for ($n = 2; isset($names[$name]); $n++) {
            $name = $base.'-'.$n.'.jpg';
        }
        $names[$name] = true;

        return $name;
    }
}
