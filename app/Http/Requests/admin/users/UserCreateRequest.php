<?php

namespace App\Http\Requests\admin\users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles_id' => 'required|exists:roles,id',
            'phone_number' => 'nullable|string|max:13',
            'account_status' => 'required|in:active,inactive',
            'is_collector' => 'nullable|boolean|',
            'collector_initial_name' => 'required_if:is_collector,true|max:3|unique:collector_infos,initial_collector_name'
        ];
    }
    public function toServiceData(): array {
        return $this->validated();
    }
}
