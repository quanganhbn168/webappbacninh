<?php

namespace App\Domain\Site\Data;

final readonly class FaviconAssetsData
{
    public function __construct(
        public bool $generated,
        public string $version,
        public string $directory,
        public string $sourceUrl,
        public string $sourceMime,
        public string $applicationName,
        public string $shortName,
        public string $themeColor,
        public string $backgroundColor,
        public string $safariMaskIconUrl,
        public string $safariMaskColor,
    ) {}

    public function url(string $filename): string
    {
        if (! $this->generated) {
            return $this->sourceUrl;
        }

        return asset('storage/'.$this->directory.'/'.$filename).'?v='.$this->version;
    }

    public function path(string $filename): string
    {
        return $this->directory.'/'.$filename;
    }
}
