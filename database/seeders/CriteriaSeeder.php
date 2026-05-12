<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\CriteriaOrdinal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    /**
     * Seed criteria dan bobot berdasarkan tabel PROMETHEE II.
     *
     * Bobot:
     *  - Kesehatan        : 0.03
     *  - Umur             : 0.05
     *  - Tinggi           : 0.04
     *  - Legalitas        : 0.02
     *  - Lingkar batang   : 0.04
     *  - Endemik          : 0.07
     *  - Prioritas        : 0.20
     *  - Tinggal satu     : 0.15
     *  - Endanger         : 0.10
     *  - Belum dikoleksi  : 0.30
     *  Total              : 1.00
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CriteriaOrdinal::truncate();
        Criteria::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $criterias = [

            // ─── 1. KESEHATAN (ordinal, benefit) ─────────────────────────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Tingkat Kesehatan',
                    'satuan'              => null,
                    'bobot'               => 0.03,
                    'tipe'                => 'benefit',
                    'skala'               => 'ordinal',
                    'preference_function' => 'usual',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [
                    // semakin tinggi urutan → semakin baik
                    ['label' => 'Tidak Sehat',   'nilai' => 1, 'urutan' => 1, 'operator' => 'eq'],
                    ['label' => 'Kurang Sehat',  'nilai' => 2, 'urutan' => 2, 'operator' => 'eq'],
                    ['label' => 'Cukup Sehat',   'nilai' => 3, 'urutan' => 3, 'operator' => 'eq'],
                    ['label' => 'Sehat',         'nilai' => 4, 'urutan' => 4, 'operator' => 'eq'],
                    ['label' => 'Sangat Sehat',  'nilai' => 5, 'urutan' => 5, 'operator' => 'eq'],
                ],
            ],

            // ─── 2. UMUR (numerik, benefit) ──────────────────────────────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Umur',
                    'satuan'              => 'Tahun',
                    'bobot'               => 0.05,
                    'tipe'                => 'benefit',
                    'skala'               => 'numerik',
                    'preference_function' => 'linear',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [],
            ],

            // ─── 3. TINGGI (numerik, benefit) ────────────────────────────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Tinggi',
                    'satuan'              => 'cm',
                    'bobot'               => 0.04,
                    'tipe'                => 'benefit',
                    'skala'               => 'numerik',
                    'preference_function' => 'linear',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [],
            ],

            // ─── 4. LEGALITAS (ordinal, benefit) ─────────────────────────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Legalitas',
                    'satuan'              => null,
                    'bobot'               => 0.02,
                    'tipe'                => 'benefit',
                    'skala'               => 'ordinal',
                    'preference_function' => 'usual',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [
                    ['label' => 'Tidak memiliki legalitas',      'nilai' => 1, 'urutan' => 1, 'operator' => 'eq'],
                    ['label' => 'Legalitas sebagian',            'nilai' => 2, 'urutan' => 2, 'operator' => 'eq'],
                    ['label' => 'Legalitas lengkap & izin resmi','nilai' => 3, 'urutan' => 3, 'operator' => 'eq'],
                ],
            ],

            // ─── 5. LINGKARAN BATANG (numerik, benefit) ──────────────────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Lingkaran Batang',
                    'satuan'              => 'cm',
                    'bobot'               => 0.04,
                    'tipe'                => 'benefit',
                    'skala'               => 'numerik',
                    'preference_function' => 'linear',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [],
            ],

            // ─── 6. LOKASI TANAM (ordinal, benefit) ──────────────────────────
            // (kolom "Lokasi Tanam" di tabel bobot tidak muncul sebagai baris
            //  tersendiri; diasumsikan sudah tercakup dalam kriteria yang ada)
            // Dimasukkan sebagai kriteria tambahan dengan bobot 0 agar tidak
            // mempengaruhi total, atau hapus baris ini jika memang tidak diperlukan.
            // Catatan: jika ingin diaktifkan, sesuaikan bobotnya.

            // ─── 7. ENDEMIK (ordinal, benefit) ───────────────────────────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Endemik',
                    'satuan'              => null,
                    'bobot'               => 0.07,
                    'tipe'                => 'benefit',
                    'skala'               => 'ordinal',
                    'preference_function' => 'usual',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [
                    ['label' => 'Tidak endemik',           'nilai' => 1, 'urutan' => 1, 'operator' => 'eq'],
                    ['label' => 'Endemik regional',        'nilai' => 2, 'urutan' => 2, 'operator' => 'eq'],
                    ['label' => 'Endemik sangat terbatas', 'nilai' => 3, 'urutan' => 3, 'operator' => 'eq'],
                ],
            ],

            // ─── 8. PRIORITAS DIPERBANYAK (ordinal, benefit) ─────────────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Prioritas Diperbanyak',
                    'satuan'              => null,
                    'bobot'               => 0.20,
                    'tipe'                => 'benefit',
                    'skala'               => 'ordinal',
                    'preference_function' => 'usual',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [
                    ['label' => 'Tidak perlu',           'nilai' => 1, 'urutan' => 1, 'operator' => 'eq'],
                    ['label' => 'Kurang',                'nilai' => 2, 'urutan' => 2, 'operator' => 'eq'],
                    ['label' => 'Cukup',                 'nilai' => 3, 'urutan' => 3, 'operator' => 'eq'],
                    ['label' => 'Perlu diperbanyak',     'nilai' => 4, 'urutan' => 4, 'operator' => 'eq'],
                    ['label' => 'Sangat perlu diperbanyak', 'nilai' => 5, 'urutan' => 5, 'operator' => 'eq'],
                ],
            ],

            // ─── 9. TINGGAL SATU (numerik, benefit) ──────────────────────────
            // Mewakili jumlah individu tersisa; semakin sedikit → semakin prioritas
            // → tipe 'cost' agar nilai rendah = lebih baik
            [
                'criteria' => [
                    'nama_criteria'       => 'Tinggal Satu',
                    'satuan'              => 'Individu',
                    'bobot'               => 0.15,
                    'tipe'                => 'cost',
                    'skala'               => 'numerik',
                    'preference_function' => 'linear',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [],
            ],

            // ─── 10. ENDANGER / STATUS KONSERVASI (ordinal, benefit) ──────────
            [
                'criteria' => [
                    'nama_criteria'       => 'Status Konservasi (Endanger)',
                    'satuan'              => null,
                    'bobot'               => 0.10,
                    'tipe'                => 'benefit',
                    'skala'               => 'ordinal',
                    'preference_function' => 'usual',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [
                    // Urutan dari ancaman terendah → tertinggi
                    ['label' => 'Least Concern',        'nilai' => 1, 'urutan' => 1, 'operator' => 'eq'],
                    ['label' => 'Near Threatened',      'nilai' => 2, 'urutan' => 2, 'operator' => 'eq'],
                    ['label' => 'Vulnerable',           'nilai' => 3, 'urutan' => 3, 'operator' => 'eq'],
                    ['label' => 'Endangered',           'nilai' => 4, 'urutan' => 4, 'operator' => 'eq'],
                    ['label' => 'Critically Endangered','nilai' => 5, 'urutan' => 5, 'operator' => 'eq'],
                ],
            ],

            // ─── 11. BELUM ADA DIKOLEKSI (ordinal, benefit) ───────────────────
            // Apakah tanaman ini belum ada di koleksi kebun → bobot tertinggi 0.30
            [
                'criteria' => [
                    'nama_criteria'       => 'Belum Ada di Koleksi',
                    'satuan'              => null,
                    'bobot'               => 0.30,
                    'tipe'                => 'benefit',
                    'skala'               => 'ordinal',
                    'preference_function' => 'usual',
                    'param_q'             => null,
                    'param_p'             => null,
                    'param_sigma'         => null,
                    'is_active'           => true,
                ],
                'ordinals' => [
                    ['label' => 'Sudah ada di koleksi',         'nilai' => 1, 'urutan' => 1, 'operator' => 'eq'],
                    ['label' => 'Belum ada di koleksi',         'nilai' => 2, 'urutan' => 2, 'operator' => 'eq'],
                ],
            ],
        ];

        foreach ($criterias as $item) {
            $criteria = Criteria::create($item['criteria']);

            foreach ($item['ordinals'] as $ordinal) {
                $criteria->ordinals()->create([
                    'label'      => $ordinal['label'],
                    'nilai'      => $ordinal['nilai'],
                    'urutan'     => $ordinal['urutan'],
                    'operator'   => $ordinal['operator'],
                    'range_from' => $ordinal['range_from'] ?? null,
                    'range_to'   => $ordinal['range_to'] ?? null,
                ]);
            }
        }
    }
}
