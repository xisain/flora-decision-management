<?php

namespace App\Repositories;

use App\Models\Criteria;
use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Models\Tanaman;

class inspeksiRepository
{
    public function all()
    {
        return Inspeksi::all();
    }

    public function stage()
    {
        $checkup = Tanaman::with(['tanamanPenerimaan', 'penyemaianTanaman'])
            ->has('penyemaianTanaman')
            ->whereDoesntHave('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'checkup');
            })
            ->get();
        $labeling = Tanaman::with(['tanamanPenerimaan', 'penyemaianTanaman'])
            ->whereDoesntHave('inspeksiTanaman', function ($q) {
                $q->where('status', 'mati');
            })
            ->has('penyemaianTanaman')->whereHas('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'checkup');
            })
            ->whereDoesntHave('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'labeling');
            })
            ->get();
        $aklimatisasi = Tanaman::with('tanamanPenerimaan')->has('penyemaianTanaman')
            ->whereHas('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'labeling');
            })
            ->whereDoesntHave('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'aklimatisasi');
            })
            ->get();
        $evaluasi = Tanaman::with('tanamanPenerimaan')->has('penyemaianTanaman')
            ->whereHas('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'aklimatisasi');
            })
            ->whereDoesntHave('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'evaluasi');
            })
            ->get();
        $criteria = Criteria::with('ordinals')->active()->get();

        return compact('checkup', 'criteria', 'labeling', 'aklimatisasi', 'evaluasi');

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
