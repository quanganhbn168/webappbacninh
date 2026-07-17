<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($this->route('service'))],
            'service_category_id' => ['nullable', 'integer', Rule::exists('service_categories', 'id')],
            'icon'        => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'content'     => 'nullable|string',
            'is_active'   => 'nullable',
        ];
    }
}
