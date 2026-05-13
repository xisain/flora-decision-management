<?php

namespace App\Http\Requests\admin\explorationTeam;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExplorationTeamCreateRequest extends FormRequest
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
            'nama_tim' => ['required', 'string', 'max:255'],
            'lokasi_explorasi' => ['required', 'string', 'max:255'],
            'deskripsi_explorasi' => ['required', 'string', 'max:255'],
            'anggota' => ['required', 'array', 'min:3'],
            'anggota.*.collector_id' => ['required', 'exists:collector_infos,id'],
            'anggota.*.peran' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_tim' => 'Harap Isi Nama Tim Terlebih dahulu',
            'lokasi_explorasi' => 'Harap Isi Tempat Explorasi',
            'deskripsi_explorasi' => 'Harap Isi deskripsi Tim Terlebih dahulu',
            'anggota.min' => 'Untuk Tim Minimal 3 Orang Terdiri dari 1 Ketua dan 2 Anggota',
            'anggota.*.peran' => 'Mohon isi Peran dari Anggota',
            'anggota.*.collector_id.required' => 'Mohon Isi Kolektor',
            'anggota.*.collector_id.exists' => 'Collector yang dipilih tidak valid.',
        ];
    }
}
