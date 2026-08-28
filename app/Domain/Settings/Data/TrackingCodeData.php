<?php

namespace App\Domain\Settings\Data;

final readonly class TrackingCodeData
{
    public function __construct(
        public string $head,
        public string $bodyStart,
        public string $bodyEnd,
    ) {}
}
