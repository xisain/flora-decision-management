<?php

namespace App\Services\peneliti;

use App\Models\Criteria;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Services\peneliti\algorithm\PrometheeIIService;
use Illuminate\Support\Collection;

class RankingService
{
    public function getRanking(): array
    {
        $criterion = Criteria::active()->get();

        $rows = InspeksiNilaiCriteria::with(['criteria', 'criteriaOrdinal'])
            ->get();

        $grouped = $rows->groupBy('inspeksi_tanaman_id');

        $alts = $grouped->map(function ($items, $inspeksiTanamanId) {
            $nilai = [];

            foreach ($items as $item) {
                $criteriaId = $item->criteria_id;

                if ($item->criteria->skala === 'ordinal') {
                    $nilai[$criteriaId] = $item->criteriaOrdinal->nilai ?? 0;
                } else {
                    $nilai[$criteriaId] = (float) $item->nilai_numeric;
                }
            }

            return [
                // ini sebenarnya id inspeksi_tanaman, bukan tanaman_id
                'tanaman_id' => $items->first()->inspeksi_tanaman_id,
                'inspeksi_tanaman_id' => $inspeksiTanamanId,
                'nilai' => $nilai,
            ];
        })->values();

        $service = PrometheeIIService::calculate($alts, $criterion);

        if ($service->get('status') === 'incomplete') {
            return [
                'incomplete' => true,
                'warnings' => $service->get('warnings'),
                'service' => collect(),
                'inspeksiMap' => collect(),
                'inspeksiIdMap' => collect(),
            ];
        }

        $inspeksiIds = collect($service)->pluck('tanaman_id');

        $inspeksiMap = InspeksiTanaman::with('tanaman.tanamanPenerimaan.tanamanInfo')
            ->whereIn('id', $inspeksiIds)
            ->get()
            ->keyBy('id');

        $inspeksiIdMap = InspeksiTanaman::whereIn('id', $inspeksiIds)
            ->pluck('inspeksi_id', 'id');

        return [
            'incomplete' => false,
            'warnings' => [],
            'service' => collect($service),
            'inspeksiMap' => $inspeksiMap,
            'inspeksiIdMap' => $inspeksiIdMap,
        ];
    }

    public function getTopRanking(int $limit = 3): Collection
    {
        $ranking = $this->getRanking();

        if ($ranking['incomplete']) {
            return collect();
        }

        return collect($ranking['service'])
            ->take($limit)
            ->values()
            ->map(function ($item) use ($ranking) {
                $inspeksiTanaman = $ranking['inspeksiMap']->get($item['tanaman_id']);

                return [
                    'ranking' => $item['ranking'] ?? null,
                    'tanaman_id' => $item['tanaman_id'],
                    'inspeksi_tanaman_id' => $item['inspeksi_tanaman_id'] ?? null,
                    'net_flow' => $item['net_flow'] ?? 0,
                    'leaving_flow' => $item['leaving_flow'] ?? 0,
                    'entering_flow' => $item['entering_flow'] ?? 0,
                    'nomor_akses' => $inspeksiTanaman?->tanaman?->nomor_akses ?? '-',
                    'scientific_name' => $inspeksiTanaman?->tanaman?->tanamanPenerimaan?->tanamanInfo?->scientific_name ?? 'Tanaman',
                    'author_name' => $inspeksiTanaman?->tanaman?->tanamanPenerimaan?->tanamanInfo?->author_name ?? null,
                ];
            });
    }
}
