<?php

namespace App\Repositories;

use App\Models\Penyemaian;
use App\Models\Tanaman;
use Illuminate\Pagination\LengthAwarePaginator;

class penyemaianRepository
{
    public function index()
    {
        $data = Penyemaian::with('penyemaianTanaman.Tanaman')->get();

        return $data;
    }

    public function show($id)
    {
        $data = Penyemaian::with([
            'penyemaianTanaman.Tanaman.tanamanPenerimaan.TanamanInfo',
            'user',
        ])->findOrFail($id);

        $groupedTanaman = $data->penyemaianTanaman->groupBy(function ($item) {
            return $item->Tanaman?->tanamanPenerimaan?->TanamanInfo?->scientific_name ?? 'Tanpa Nama';
        });

        $perPage = 10;
        $currentPage = request()->get('page', 1);

        $groupedTanamanPaginated = new LengthAwarePaginator(
            $groupedTanaman->forPage($currentPage, $perPage),
            $groupedTanaman->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return compact('data', 'groupedTanaman', 'groupedTanamanPaginated');
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
