<?php

namespace App\Http\Requests\admin\collector;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CollectorCreateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'initial_collector_name' => [
                'required',
                'unique:collector_infos,initial_collector_name,'.$this->route('id') ?? 'NULL',
            ],
            'is_manual' => ['required'],
            'last_sequence' => ['required'],
        ];

    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->user_id ?? null,
        ]);
    }
}
