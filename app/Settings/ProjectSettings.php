<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/** Project code numbering and the fixed deliverables offered in the project form. */
class ProjectSettings extends Settings
{
    public string $code_prefix;

    public string $code_suffix;

    public int $code_digits;

    public int $code_next;

    /** @var array<int, string> */
    public array $deliverable_presets;

    public static function group(): string
    {
        return 'project';
    }

    public static function formatCode(string $prefix, int $number, int $digits, string $suffix): string
    {
        return $prefix.str_pad((string) $number, $digits, '0', STR_PAD_LEFT).$suffix;
    }
}
