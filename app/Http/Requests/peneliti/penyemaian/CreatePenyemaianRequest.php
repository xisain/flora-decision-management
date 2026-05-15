<?php

namespace App\Http\Requests\peneliti\penyemaian;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePenyemaianRequest extends FormRequest
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
            'tanggal_penyemaian' => ['required', 'date'],
            'lokasi_semai' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:255'],
            'tanaman' => ['required', 'array', 'min:1'],
            'tanaman.*' => ['exists:tanaman,id'],
        ];
    }
}
