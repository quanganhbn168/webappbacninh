<?php

namespace App\Domain\Settings\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final class SquareRasterImage implements ValidationRule
{
    public function __construct(
        private readonly string $label,
        private readonly int $minimumSize = 512,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            return;
        }

        $path = $value->getRealPath();

        if (! is_string($path) || $path === '') {
            $fail("{$this->label} không thể đọc được.");

            return;
        }

        $dimensions = @getimagesize($path);

        if (! is_array($dimensions)) {
            $fail("{$this->label} phải là ảnh raster hợp lệ.");

            return;
        }

        [$width, $height] = $dimensions;

        if ($width !== $height || $width < $this->minimumSize) {
            $fail("{$this->label} phải vuông và có kích thước tối thiểu {$this->minimumSize}x{$this->minimumSize} px.");
        }
    }
}
