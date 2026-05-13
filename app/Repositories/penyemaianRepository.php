<?php

namespace App\Repositories;

use App\Models\Tanaman;
use App\Models\penyemaian;
class penyemaianRepository
{
    public function index(){
        $data = penyemaian::with('penyemaianTanaman.Tanaman')->get();
        return $data;
    }
    public function show($id){
        $data = penyemaian::with('penyemaianTanaman.Tanaman.tanamanPenerimaan.TanamanInfo')->find($id);
        return $data;

    }
    public function create()
    {
        $tanaman = Tanaman::with(['tanamanPenerimaan.tanamanInfo'])->whereDoesntHave('penyemaianTanaman')->get();
        $grouped = $tanaman->groupBy('tanamanPenerimaan.nomor_akses');
        $groupedForAlpine = $grouped
            ->map(fn ($group, $key) => [
                'nomor_akses' => $key,
                'scientific_name' => $group->first()->tanamanPenerimaan->tanamanInfo->scientific_name,
                'author_name' => $group->first()->tanamanPenerimaan->tanamanInfo->author_name,
                'ids' => $group->pluck('id')->values(),
                'items' => $group->map(fn ($item) => [
                    'id' => $item->id,
                    'nomor_urut' => str_pad($item->nomor_urut, 3, '0', STR_PAD_LEFT),
                ])->values(),
            ])
            ->values();
        return [
            'grouped' => $grouped,
            'groupedForAlpine' => $groupedForAlpine,
        ];

    }
}
