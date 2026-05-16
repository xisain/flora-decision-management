<?php

namespace App\Services\peneliti\inspeksi;

use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Services\peneliti\TanamanLoggingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class inspeksiService
{
    public function __construct(private TanamanLoggingService $tls) {}

    public function store(array $validated)
    {
        // dump($validated);
        DB::transaction(function () use ($validated) {
            $inspeksi = Inspeksi::create([
                'tanggal_inspeksi' => $validated['tanggal_inspeksi'],
                'catatan' => $validated['catatan'],
                'stage' => $validated['stage'],
                'user_id' => Auth::user()->id,
            ]);
            $statusLogs = [];
            foreach ($validated['plants'] as $plant) {
                $inspeksiTanaman = InspeksiTanaman::create([
                    'tanaman_id' => $plant['id'],
                    'inspeksi_id' => $inspeksi->id,
                    'status' => $plant['status'],
                    'tanggal_mati' => $plant['status'] === 'mati' ? ($plant['tanggal_mati'] ?? null) : null,
                    'labeling' => $plant['label'] ?? null,
                    'catatan' => $validated['catatan'],
                ]);
                $statusLogs[] = [
                    'tanaman_id' => $plant['id'],
                    'stage' => $validated['stage'],
                    'status' => $plant['status'],
                    'user_id' => Auth::id(),
                    'catatan' => $validated['catatan'] ?? null,
                    'tanggal_proses' => ($plant['status'] === 'mati' && ! empty($plant['tanggal_mati'])) ? $plant['tanggal_mati'] : $validated['tanggal_inspeksi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if ($validated['stage'] === 'evaluasi' && isset($plant['kriteria'])) {
                    foreach ($plant['kriteria'] as $kriteriaId => $kriteriaData) {
                        InspeksiNilaiCriteria::create([
                            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
                            'criteria_id' => $kriteriaId,
                            'nilai_numeric' => $kriteriaData['skala'] === 'numerik' ? $kriteriaData['nilai'] : null,
                            'criteria_ordinal_id' => $kriteriaData['skala'] === 'ordinal' ? $kriteriaData['nilai'] : null,
                        ]);
                    }
                }
            }
            if (! empty($statusLogs)) {
                $this->tls->store($statusLogs);
            }
        });
    }

    public function updateEvaluasi(array $nilaiList, string $id)
    {
        return DB::transaction(function () use ($nilaiList, $id) {
            $inspeksi = InspeksiTanaman::findOrFail($id);
            $inspeksiId = $inspeksi->inspeksi_id;
            foreach ($nilaiList as $item) {
                InspeksiNilaiCriteria::updateOrCreate(
                    [
                        'inspeksi_tanaman_id' => $inspeksi->id,
                        'criteria_id' => $item['criteria_id'],
                    ],
                    [
                        'nilai_numeric' => $item['nilai_numeric'] ?: null,
                        'criteria_ordinal_id' => $item['criteria_ordinal_id'] ?: null,
                    ]
                );
            }

            return $inspeksi->inspeksi_id;
        });
    }
}
