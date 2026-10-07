<?php

namespace App\Http\Requests;

use App\Models\TempatMenarik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTempatMenarikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->roles?->name === 'admin';
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:20000'],
            'foto' => [$this->isMethod('POST') ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:2048'],
            'kategori' => ['required', Rule::in(array_keys(TempatMenarik::CATEGORIES))],
            'is_featured' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'urutan' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ];
    }

    public function attributes(): array
    {
        return ['nama' => 'nama tempat', 'deskripsi' => 'deskripsi', 'foto' => 'foto tempat', 'kategori' => 'kategori', 'urutan' => 'urutan', 'is_featured' => 'pilihan tampil di landing', 'is_active' => 'status aktif'];
    }
}
