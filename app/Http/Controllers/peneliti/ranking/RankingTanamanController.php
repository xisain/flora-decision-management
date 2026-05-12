<?php

namespace App\Http\Controllers\peneliti\ranking;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Services\peneliti\algorithm\PrometheeIIService;
use Illuminate\Http\Request;

class RankingTanamanController extends Controller
{
    public function __construct(private PrometheeIIService $piis) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criterion = Criteria::active()->get();
        $rows = InspeksiNilaiCriteria::with(['criteria', 'criteriaOrdinal'])->get();
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
                'tanaman_id' => $items->first()->inspeksi_tanaman_id,
                'inspeksi_tanaman_id' => $inspeksiTanamanId,
                'nilai' => $nilai,
            ];
        })->values();

        $service = PrometheeIIService::calculate($alts, $criterion);

        // Handle incomplete data
        if ($service->get('status') === 'incomplete') {
            return view('peneliti.ranking.index', [
                'incomplete' => true,
                'warnings' => $service->get('warnings'),
                'service' => collect(),
                'inspeksiMap' => collect(),
                'inspeksiIdMap' => collect(),
            ]);
        }

        $inspeksiIds = collect($service)->pluck('tanaman_id');
        $inspeksiMap = InspeksiTanaman::with('tanaman.tanamanPenerimaan.tanamanInfo')
            ->whereIn('id', $inspeksiIds)
            ->get()
            ->keyBy('id');
        $inspeksiIdMap = InspeksiTanaman::whereIn('id', $inspeksiIds)
            ->pluck('inspeksi_id', 'id');

        return view('peneliti.ranking.index', [
            'incomplete' => false,
            'warnings' => [],
            'service' => $service,
            'inspeksiMap' => $inspeksiMap,
            'inspeksiIdMap' => $inspeksiIdMap,
        ]);
    }

    public function findByInspeksiTanamanId(string $id)
    {
        $inspeksiTanaman = InspeksiTanaman::find($id);
        dd($inspeksiTanaman);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
