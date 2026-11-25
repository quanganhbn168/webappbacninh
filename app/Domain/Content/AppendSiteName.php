<?php

namespace App\Domain\Content;

use Illuminate\Support\Str;

/** Page titles end with "| Tên website" without anyone typing it. */
class AppendSiteName
{
    public function execute(string $title): string
    {
        $title = trim($title);
        $name = trim((string) site_config('name'));

        // A title that already carries the name is left as written.
        if ($title === '' || $name === '' || str_contains(Str::lower($title), Str::lower($name))) {
            return $title ?: $name;
        }

        return rtrim($title, ' |').' | '.$name;
    }
}
