<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:post_categories,id',
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($this->route('post')),
            ],
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ];
    }

    // Attributes and messages now come from lang/vi/validation.php
}
