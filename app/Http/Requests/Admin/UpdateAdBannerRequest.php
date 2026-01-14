<?php

namespace App\Http\Requests\Admin;

use App\Enums\BannerSlot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateAdBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slot' => ['required', new Enum(BannerSlot::class)],
            'banner_image' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
            'alt_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'open_new_tab' => 'boolean',
            'order' => 'integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên banner không được để trống.',
            'slot.required' => 'Vị trí hiển thị không được để trống.',
            'banner_image.image' => 'File phải là hình ảnh.',
            'link.url' => 'Link phải là URL hợp lệ.',
        ];
    }
}
