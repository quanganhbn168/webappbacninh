<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'image'                => 'nullable|string|max:2048', // Legacy File Manager path; imported to Spatie Media Library.
            'template_category_id' => 'nullable|exists:template_categories,id',
            'category'             => 'nullable|string', // Legacy
            'demo_url'             => 'nullable|url',
            'is_premium'           => 'nullable',
            'is_active'            => 'nullable',
            'tags'                 => 'nullable', // Converted to array/string
            'images'               => 'nullable|array',
            'images.*'             => 'nullable|string',
            'content'              => 'nullable|string',
            'price'                => 'nullable|numeric|min:0',
            'sale_price'           => 'nullable|numeric|min:0',
            'is_free'              => 'nullable|boolean',
            'order'                => 'nullable|integer|min:0',
        ];
    }
}
