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
            'name'       => 'required|string|max:255',
            'image_file' => 'nullable|image|max:2048', // 2MB
            'category'   => 'nullable|string',
            'demo_url'   => 'nullable|url',
            'is_premium' => 'nullable',
            'is_active'  => 'nullable',
        ];
    }
}
