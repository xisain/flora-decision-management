<?php

namespace App\Http\Requests\admin\users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserUpdateRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'roles_id' => ['required', 'exists:roles,id'],
            'account_status' => ['required', 'in:active,inactive'],

            // collector (conditional)
            'is_collector' => ['nullable', 'boolean'],
            'collector_initial_name' => [
                'required_if:is_collector,true',
                'max:3',
                Rule::unique('collector_infos', 'initial_collector_name')->ignore($user->id, 'user_id'),
            ],
            'collector_display_name' => ['nullable', 'max:255'],
        ];
    }
}
