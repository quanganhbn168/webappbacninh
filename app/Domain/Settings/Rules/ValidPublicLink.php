<?php

namespace App\Domain\Settings\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidPublicLink implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (! is_string($value)) {
            $fail('Liên kết phải là một chuỗi hợp lệ.');

            return;
        }

        $value = trim($value);

        if ($value === '') {
            return;
        }

        if (preg_match('/^#[A-Za-z][A-Za-z0-9_-]*$/', $value)) {
            return;
        }

        if (str_starts_with($value, '/') && ! str_starts_with($value, '//')) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));

            if (in_array($scheme, ['http', 'https'], true)) {
                return;
            }
        }

        if (preg_match('/^tel:\+?[0-9][0-9\s().-]{6,24}$/i', $value)) {
            return;
        }

        if (str_starts_with(strtolower($value), 'mailto:') && filter_var(substr($value, 7), FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $fail('Liên kết phải là URL http(s), đường dẫn nội bộ, số tel: hoặc email mailto: hợp lệ.');
    }
}
