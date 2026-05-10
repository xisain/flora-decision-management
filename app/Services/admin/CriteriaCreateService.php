<?php

namespace App\Services\admin;
use App\Models\Criteria;
use App\Http\Requests\admin\CriteriaCreateRequest;
use Illuminate\Support\Facades\DB;

class CriteriaCreateService
{
    public function store(CriteriaCreateRequest $request): Criteria
    {
        return DB::transaction(function () use ($request) {
            $kriteria = Criteria::create([
                'nama_criteria'       => $request->nama_kriteria,
                'tipe'                => $request->tipe,
                'satuan'              => $request->satuan ?? null,
                'skala'               => $request->skala,
                'bobot'               => $request->bobot,
                'preference_function' => $request->preference_function,
                'param_q'             => $request->param_q ?? null,
                'param_p'             => $request->param_p ?? null,
                'param_sigma'         => $request->param_sigma ?? null,
            ]);


            if ($request->skala === 'ordinal') {
                foreach ($request->ordinal as $row) {
                    $kriteria->ordinals()->create([
                        'label'      => $row['label'],
                        'nilai'      => $row['nilai'],
                        'operator'   => $row['operator'],
                        'range_from' => $row['range_from'] ?? null,
                        'range_to'   => $row['range_to'] ?? null,
                        'urutan'     => $row['urutan'],
                    ]);
                }
            }

            return $kriteria;
        });
    }
}
