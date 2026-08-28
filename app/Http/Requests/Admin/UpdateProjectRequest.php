<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|string', // Stored media path
            'link' => 'nullable|url',
            'category' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề không được để trống.',
            'featured_image.image' => 'File phải là hình ảnh.',
            'link.url' => 'Link phải là URL hợp lệ.',
        ];
    }
}
