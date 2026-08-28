<?php

namespace App\Domain\Settings\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidSocialLink implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (! is_string($value)) {
            $fail('Liên kết mạng xã hội phải là URL https:// hợp lệ.');

            return;
        }

        $value = trim($value);

        if ($value === '') {
            return;
        }

        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('Liên kết mạng xã hội phải là URL https:// hợp lệ.');

            return;
        }

        if (strtolower((string) parse_url($value, PHP_URL_SCHEME)) !== 'https') {
            $fail('Liên kết mạng xã hội phải bắt đầu bằng https://.');
        }
    }
}
