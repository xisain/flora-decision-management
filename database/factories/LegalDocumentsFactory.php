<?php

namespace Database\Factories;

use App\Models\LegalDocuments;
use App\Models\Penerimaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LegalDocuments>
 */
class LegalDocumentsFactory extends Factory
{
    protected $model = LegalDocuments::class;

    /**
     * @return array{penerimaan_id: PenerimaanFactory, nama_surat: string, nomor_surat: string, path_file: string}
     */
    public function definition(): array
    {
        return [
            'penerimaan_id' => Penerimaan::factory(),
            'nama_surat' => fake()->randomElement([
                'Surat Izin Pengambilan',
                'Surat Keterangan Asal',
                'SATS-DN',
                'Surat Rekomendasi',
            ]),
            'nomor_surat' => fake()->bothify('###/??/????/####'),
            'path_file' => 'dokumen/penerimaan/'.fake()->uuid().'.pdf',
        ];
    }
}
