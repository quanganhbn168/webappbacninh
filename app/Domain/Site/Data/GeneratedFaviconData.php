<?php

namespace App\Domain\Site\Data;

final readonly class GeneratedFaviconData
{
    public function __construct(
        public string $version,
        public string $directory,
    ) {}
}
