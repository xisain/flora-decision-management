<?php

namespace App\Http\Requests\admin;
use App\Models\Criteria;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriteriaUpdateRequest extends FormRequest
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
        $rules = [
            // Informasi Kriteria
            'nama_kriteria'       => ['required', 'string', 'max:255'],
            'tipe'                => ['required', 'in:benefit,cost'],
            'satuan'              => ['nullable', 'string', 'max:100'],
            'skala'               => ['required', 'in:numerik,ordinal'],
            'bobot'               => ['required', 'numeric', 'min:0', 'max:1'],
            'preference_function' => ['required', 'in:usual,quasi,linear,level,gaussian,v_shape'],

            // Parameter preference function
            'param_q' => [
                Rule::requiredIf(in_array($this->preference_function, ['quasi', 'linear', 'level'])),
                'nullable', 'numeric', 'min:0',
            ],
            'param_p' => [
                Rule::requiredIf(in_array($this->preference_function, ['linear', 'level', 'v_shape'])),
                'nullable', 'numeric', 'min:0',
            ],
            'param_sigma' => [
                Rule::requiredIf($this->preference_function === 'gaussian'),
                'nullable', 'numeric', 'min:0',
            ],
        ];


        if ($this->skala === 'ordinal') {
            $rules = array_merge($rules, [
                'ordinal'              => ['required', 'array', 'min:1'],
                'ordinal.*.label'      => ['required', 'string', 'max:255'],
                'ordinal.*.nilai'      => ['required', 'integer', 'min:1'],
                'ordinal.*.operator'   => ['required', 'in:eq,lt,lte,gt,gte,between'],
                'ordinal.*.urutan'     => ['required', 'integer', 'min:1'],
                'ordinal.*.range_from' => ['nullable', 'numeric'],
                'ordinal.*.range_to'   => ['nullable', 'numeric'],
            ]);
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            'nama_kriteria.required'       => 'Nama kriteria wajib diisi.',
            'tipe.required'                => 'Tipe wajib dipilih.',
            'tipe.in'                      => 'Tipe harus benefit atau cost.',
            'skala.required'               => 'Skala wajib dipilih.',
            'skala.in'                     => 'Skala harus numerik atau ordinal.',
            'bobot.required'               => 'Bobot wajib diisi.',
            'bobot.min'                    => 'Bobot minimal 0.',
            'bobot.max'                    => 'Bobot tidak boleh melebihi 1.00.',
            'preference_function.required' => 'Fungsi preferensi wajib dipilih.',
            'preference_function.in'       => 'Fungsi preferensi tidak valid.',
            'param_q.required'             => 'Parameter Q wajib diisi untuk fungsi ini.',
            'param_q.min'                  => 'Parameter Q minimal 0.',
            'param_p.required'             => 'Parameter P wajib diisi untuk fungsi ini.',
            'param_p.min'                  => 'Parameter P minimal 0.',
            'param_sigma.required'         => 'Parameter Sigma wajib diisi untuk fungsi Gaussian.',
            'param_sigma.min'              => 'Parameter Sigma minimal 0.',
            'ordinal.required'             => 'Skala ordinal wajib memiliki minimal 1 baris.',
            'ordinal.min'                  => 'Skala ordinal wajib memiliki minimal 1 baris.',
            'ordinal.*.label.required'     => 'Label baris ke-:position wajib diisi.',
            'ordinal.*.nilai.required'     => 'Nilai baris ke-:position wajib diisi.',
            'ordinal.*.nilai.min'          => 'Nilai baris ke-:position minimal 1.',
            'ordinal.*.operator.required'  => 'Operator baris ke-:position wajib dipilih.',
            'ordinal.*.operator.in'        => 'Operator baris ke-:position tidak valid.',
        ];
    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            // Validasi range per baris ordinal
            if ($this->skala === 'ordinal' && is_array($this->ordinal)) {
                foreach ($this->ordinal as $index => $row) {
                    $no = $index + 1;

                    // range_from wajib jika operator != eq
                    if (($row['operator'] ?? 'eq') !== 'eq' && empty($row['range_from'])) {
                        $validator->errors()->add(
                            "ordinal.{$index}.range_from",
                            "Range From baris ke-{$no} wajib diisi untuk operator {$row['operator']}."
                        );
                    }

                    // range_to wajib jika operator = between
                    if (($row['operator'] ?? '') === 'between' && empty($row['range_to'])) {
                        $validator->errors()->add(
                            "ordinal.{$index}.range_to",
                            "Range To baris ke-{$no} wajib diisi untuk operator Between."
                        );
                    }

                    // range_to harus lebih besar dari range_from jika between
                    if (
                        ($row['operator'] ?? '') === 'between' &&
                        !empty($row['range_from']) &&
                        !empty($row['range_to']) &&
                        (float) $row['range_to'] <= (float) $row['range_from']
                    ) {
                        $validator->errors()->add(
                            "ordinal.{$index}.range_to",
                            "Range To baris ke-{$no} harus lebih besar dari Range From."
                        );
                    }
                }
            }

            // Validasi total bobot tidak melebihi 1.00
            // Kecualikan criteria yang sedang diedit agar bobotnya tidak dihitung dua kali
            $criteriaId = $this->route('criterion');
            $totalBobot = Criteria::where('is_active', true)
                ->when($criteriaId, fn($q) => $q->where('id', '!=', $criteriaId))
                ->sum('bobot');
            if (round($totalBobot + (float) $this->bobot, 2) > 1.00) {
                $sisaBobot = round(1 - $totalBobot, 2);
                $validator->errors()->add(
                    'bobot',
                    "Total bobot akan melebihi 1.00. Sisa bobot tersedia: {$sisaBobot}."
                );
            }
        });
    }
}
