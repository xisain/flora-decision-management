<?php

namespace App\Http\Requests\peneliti\penerimaan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePenerimaanRequest extends FormRequest
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
            'dokumen.*.namaSurat' => ['required', 'string', 'max:255'],
            'dokumen.*.nomorSurat' => ['nullable', 'string', 'max:255'],
            'dokumen.*.fileSurat' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            // Step 2 Validate
            'tanggal_penerimaan' => ['required', 'date', 'before_or_equal:today'],
            'tanggal_explorasi' => ['required', 'date','before:tanggal_penerimaan'],
            'jenis_form' => ['required', 'string'],
            'tempat_asal' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'native' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            // Step 3 Validate
            'tim_id' => ['nullable', 'required_without:tim_baru.nama_tim'],
            'tim_baru.nama_tim' => ['nullable', 'required_without:tim_id', 'string'],
            'tim_baru.lokasi' => ['nullable', 'required_with:tim_baru.nama_tim', 'string'],
            'tim_baru.deskripsi' => ['nullable', 'string'],
            'tim_baru.anggota' => ['nullable', 'array'],
            'tim_baru.anggota.*.id' => ['nullable', 'exists:collector_infos,id'],
            'tim_baru.anggota.*.role' => ['nullable', 'string'],

            // Step 4 Validate
            'tanaman' => ['array', 'min: 3'],
            'tanaman.*.scientific_name' => ['required', 'string', 'max:255'],
            'tanaman.*.nomor_akses' => ['nullable'],
            'tanaman.*.nama_lokal' => ['nullable', 'string', 'max:255'],
            'tanaman.*.marga' => ['nullable', 'string', 'max:255'],
            'tanaman.*.marga_jenis' => ['nullable', 'string', 'max:255'],
            'tanaman.*.suku' => ['nullable', 'string', 'max:255'],
            'tanaman.*.spesies' => ['nullable', 'string', 'max:255'],
            'tanaman.*.author_name' => ['nullable', 'string', 'max:255'],
            'tanaman.*.locality' => ['nullable', 'string', 'max:255'],
            'tanaman.*.jumlah_material' => ['nullable', 'integer', 'min:1'],
            'tanaman.*.vak_no' => ['nullable', 'string', 'max:255'],
            'tanaman.*.tipe_tanaman'=> ['required', 'string','in:tree,shrub'],
            'tanaman.*.collector_id' => ['nullable', 'exists:collector_infos,id'],
            'tanaman.*.collector_initial' => ['nullable', 'string', 'max:50'],
        ];
    }
}
