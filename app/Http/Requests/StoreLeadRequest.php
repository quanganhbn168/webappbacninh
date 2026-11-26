<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->filled('need') && $this->filled('service')) {
            $this->merge(['need' => $this->input('service')]);
        }

        // Ad parameters are trimmed rather than validated: a long or odd one must never cost a lead.
        if ($this->has('campaign')) {
            $this->merge(['campaign' => collect((array) $this->input('campaign'))->only(Lead::CAMPAIGN_KEYS)
                ->filter(fn (mixed $value): bool => is_string($value) && $value !== '')
                ->map(fn (string $value): string => mb_substr($value, 0, 500))
                ->all()]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return ['phone.regex' => 'Số điện thoại chưa đúng, anh/chị kiểm tra lại giúp em.'];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9 .()\-]{8,22}$/'],
            'email' => ['nullable', 'email', 'not_regex:/[\r\n]/', 'max:190'],
            'company' => ['nullable', 'string', 'max:190'],
            'business' => ['nullable', 'string', 'max:190'],
            'need' => ['nullable', 'string', 'max:255'],
            'budget' => ['nullable', 'string', 'max:100'],
            'timeline' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:5000'],
            'source' => ['nullable', 'string', 'max:255'],
            'campaign' => ['nullable', 'array'],
        ];
    }
}
