<?php

namespace App\Http\Requests\peneliti\inspeksi;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateInspeksiRequest extends FormRequest
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
            'tanggal_inspeksi' => ['required', 'date'],
            'catatan' => ['required', 'string'],
            'stage' => ['required', 'string'],
            'plants.*' => ['required', 'array', 'min:1'],
            'plants.*.id' => ['required', 'exists:tanaman,id'],
            'plants.*.status' => ['required', 'string'],
            'plants.*.kriteria' => ['required_if:stage,evaluasi', 'array'],
            'plants.*.kriteria.*.nilai' => ['required_if:stage,evaluasi'],
            'plants.*.kriteria.*.skala' => ['required_if:stage,evaluasi', 'in:numerik,ordinal'],
        ];
    }
}
