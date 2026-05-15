<?php

namespace App\Http\Requests\admin\collector;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CollectorUpdateRequest extends FormRequest
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
        $id = $this->route('collector');
        return [
            'user_id'                => ['nullable', 'exists:users,id'],
            'full_name'              => ['required', 'string', 'max:255'],
            'initial_collector_name' => [
                'required',
                'max:3',
                Rule::unique('collector_infos', 'initial_collector_name')->ignore($id),
            ],
            'last_sequence'          => ['required'],
            'is_manual'              => ['required'],
        ];
    }
}
