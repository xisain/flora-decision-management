<?php

namespace App\Http\Requests\berita;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class beritaCreateRequest extends FormRequest
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
            'judul' => [
                'required',
                'string',
                'max:255',
            ],
    
            'slugs' => [
                'required',
                'string',
                'max:255',
                'unique:beritas,slugs',
            ],
    
            'kategori_berita_id' => [
                'required',
                'string',
                'max:255',
            ],
    
            'content' => [
                'required',
                'string',
            ],
    
            'image_url' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
    
            'status' => [
                'required',
                'in:draft,published',
            ],
        ];
    }
}
