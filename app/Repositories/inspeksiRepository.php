<?php

namespace App\Repositories;

use App\Models\Criteria;
use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Models\Tanaman;

class inspeksiRepository
{
    public function all($request)
    {
        $data = Inspeksi::query()
            ->with(['user', 'inspeksiTanaman'])
            ->when($request->filled('tanggal_inspeksi_dari'), function ($query) use ($request) {
                $query->whereDate('tanggal_inspeksi', '>=', $request->tanggal_inspeksi_dari);
            })
            ->when($request->filled('tanggal_inspeksi_sampai'), function ($query) use ($request) {
                $query->whereDate('tanggal_inspeksi', '<=', $request->tanggal_inspeksi_sampai);
            })
            ->when($request->filled('stage'), function ($query) use ($request) {
                $query->where('stage', $request->stage);
            })
            ->latest('tanggal_inspeksi')
            ->paginate(10)
            ->withQueryString();

        return compact('data');
    }

    public function stage()
    {
        // Helper: status inspeksi TERAKHIR di stage tertentu = 'hidup'
        $lulusStage = function (string $stage) {
            return function ($q) use ($stage) {
                $q->whereHas('inspeksiTanaman', function ($it) use ($stage) {
                    $it->where('status', 'hidup')
                        ->whereHas('inspeksi', function ($i) use ($stage) {
                            $i->where('stage', $stage)
                                ->whereRaw('tanggal_inspeksi = (
                                SELECT MAX(i2.tanggal_inspeksi)
                                FROM inspeksis i2
                                INNER JOIN inspeksi_tanaman it2 ON it2.inspeksi_id = i2.id
                                WHERE it2.tanaman_id = inspeksi_tanaman.tanaman_id
                                  AND i2.stage = ?
                            )', [$stage]);
                        });
                });
            };
        };

        // Helper: status inspeksi TERAKHIR di stage tertentu != 'hidup' (atau belum pernah)
        $belumLulusStage = function (string $stage) {
            return function ($q) use ($stage) {
                $q->whereDoesntHave('inspeksiTanaman.inspeksi', fn ($i) => $i->where('stage', $stage))
                    ->orWhere(function ($q) use ($stage) {
                        $q->whereHas('inspeksiTanaman', function ($it) use ($stage) {
                            $it->where('status', '!=', 'hidup')
                                ->whereHas('inspeksi', function ($i) use ($stage) {
                                    $i->where('stage', $stage)
                                        ->whereRaw('tanggal_inspeksi = (
                                  SELECT MAX(i2.tanggal_inspeksi)
                                  FROM inspeksis i2
                                  INNER JOIN inspeksi_tanaman it2 ON it2.inspeksi_id = i2.id
                                  WHERE it2.tanaman_id = inspeksi_tanaman.tanaman_id
                                    AND i2.stage = ?
                              )', [$stage]);
                                });
                        })
                            ->whereDoesntHave('inspeksiTanaman', function ($it) use ($stage) {
                                $it->where('status', 'hidup')
                                    ->whereHas('inspeksi', function ($i) use ($stage) {
                                        $i->where('stage', $stage)
                                            ->whereRaw('tanggal_inspeksi = (
                                  SELECT MAX(i2.tanggal_inspeksi)
                                  FROM inspeksis i2
                                  INNER JOIN inspeksi_tanaman it2 ON it2.inspeksi_id = i2.id
                                  WHERE it2.tanaman_id = inspeksi_tanaman.tanaman_id
                                    AND i2.stage = ?
                              )', [$stage]);
                                    });
                            });
                    });
            };
        };

        $with = [
            'tanamanPenerimaan.tanamanInfo',
            'penyemaianTanaman',
            'inspeksiTanaman.inspeksi',
        ];

        $base = fn () => Tanaman::with($with)->has('penyemaianTanaman');

        $checkup = $base()->whereNot($lulusStage('checkup'))->get();
        $labeling = $base()->where($lulusStage('checkup'))->whereNot($lulusStage('labeling'))->get();
        $aklimatisasi = $base()->where($lulusStage('labeling'))->whereNot($lulusStage('aklimatisasi'))->get();
        $evaluasi = $base()->where($lulusStage('aklimatisasi'))->whereDoesntHave('inspeksiTanaman.nilaiCriteria')->get();

        $criteria = Criteria::with('ordinals')->active()->get();
        $oldPlants = old('plants', []);
        $oldStage = old('stage', 'checkup');

        return compact('checkup', 'criteria', 'labeling', 'aklimatisasi', 'evaluasi', 'oldPlants', 'oldStage');
    }

    public function findInspeksiDetail(string $id)
    {

        $data = Inspeksi::with('user')->findOrFail($id);
        $inspeksiTanamanIds = InspeksiTanaman::where('inspeksi_id', $data->id)->pluck('id')->toArray();
        $inspeksiTanaman = InspeksiTanaman::with('tanaman.tanamanPenerimaan.TanamanInfo')->where('inspeksi_id', $data->id)->paginate(10);
        $inspeksiCriteria = InspeksiNilaiCriteria::with('criteriaOrdinal')->whereIn('inspeksi_tanaman_id', $inspeksiTanamanIds)->get()->groupBy('inspeksi_tanaman_id');
        // dd($inspeksiCriteria);
        $criteria = Criteria::active()->get();

        return compact('data', 'inspeksiCriteria', 'inspeksiTanaman', 'criteria');
    }

    public function findInspeksiTanamanWithCriteria(string $id)
    {
        $inspeksi = InspeksiTanaman::with([
            'tanaman.tanamanPenerimaan.tanamanInfo',
            'nilaiCriteria.criteria.ordinals',
        ])->findOrFail($id);

        $criteria = Criteria::active()->with('ordinals')->get();

        $nilaiMap = $inspeksi->nilaiCriteria->keyBy('criteria_id');

        return compact('inspeksi', 'criteria', 'nilaiMap');
    }
}
