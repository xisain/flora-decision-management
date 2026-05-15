<?php

namespace App\Services\peneliti\inspeksi;
use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class inspeksiService
{
    public function store(array $validated)
    {
        DB::transaction(function () use ($validated){
            $inspeksi = Inspeksi::create([
                'tanggal_inspeksi' => $validated['tanggal_inspeksi'],
                'catatan' => $validated['catatan'],
                'stage' => $validated['stage'],
                'user_id' => Auth::user()->id,
            ]);
            foreach ($validated['plants'] as $plant) {
                $inspeksiTanaman = InspeksiTanaman::create([
                    'tanaman_id' => $plant['id'],
                    'inspeksi_id' => $inspeksi->id,
                    'status' => $plant['status'],
                    'catatan' => $validated['catatan'],
                ]);
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
        });
    }
    public function updateEvaluasi(array $nilaiList, string $id){
        return DB::transaction(function () use ($nilaiList,$id){
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
