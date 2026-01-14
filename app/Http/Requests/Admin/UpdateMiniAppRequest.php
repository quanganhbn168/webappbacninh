<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMiniAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'sometimes|boolean',
            'order' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên ứng dụng',
            'icon.required' => 'Vui lòng nhập mã icon FontAwesome (VD: fas fa-home)',
        ];
    }
}
