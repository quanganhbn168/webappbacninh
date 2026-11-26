<?php

namespace App\Domain\Seo;

/** A group of rules shown together in the admin. */
interface SeoChecks
{
    public function title(): string;

    /** @return array<int, SeoCheck> */
    public function run(SeoContext $context): array;
}
