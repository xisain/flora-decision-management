<?php

namespace App\Services\admin;

use App\Http\Requests\admin\CriteriaUpdateRequest;
use App\Models\Criteria;
use Illuminate\Support\Facades\DB;

class CriteriaUpdateService
{
    public function __construct()
    {
        //
    }

    public function update(CriteriaUpdateRequest $request, Criteria $criteria): Criteria
    {
        return DB::transaction(function () use ($request, $criteria) {
            $criteria->update([
                'nama_criteria' => $request->nama_kriteria,
                'tipe' => $request->tipe,
                'satuan' => $request->satuan ?? null,
                'skala' => $request->skala,
                'bobot' => $request->bobot,
                'preference_function' => $request->preference_function,
                'param_q' => $request->param_q ?? null,
                'param_p' => $request->param_p ?? null,
                'param_sigma' => $request->param_sigma ?? null,
            ]);
            if ($request->skala === 'ordinal') {
                // Kumpulkan ID yang tetap ada (untuk delete yg tidak terpakai nanti)
                $incomingIds = [];

                foreach ($request->ordinal as $index => $row) {
                    if (!empty($row['id'])) {
                        // UPDATE — row lama yang sudah punya id
                        $criteria->ordinals()->where('id', $row['id'])->update([
                            'label'      => $row['label'],
                            'nilai'      => $row['nilai'],
                            'operator'   => $row['operator'],
                            'range_from' => $row['range_from'] ?? null,
                            'range_to'   => $row['range_to'] ?? null,
                            'urutan'     => $index + 1,
                        ]);
                        $incomingIds[] = (int) $row['id'];
                    } else {
                        // CREATE — row baru tanpa id; catat ID hasil create agar tidak ikut dihapus
                        $new = $criteria->ordinals()->create([
                            'label'      => $row['label'],
                            'nilai'      => $row['nilai'],
                            'operator'   => $row['operator'],
                            'range_from' => $row['range_from'] ?? null,
                            'range_to'   => $row['range_to'] ?? null,
                            'urutan'     => $index + 1,
                        ]);
                        $incomingIds[] = $new->id;
                    }
                }

                // Hapus ordinal lama yang sudah tidak ada di request
                if (!empty($incomingIds)) {
                    $criteria->ordinals()->whereNotIn('id', $incomingIds)->delete();
                } else {
                    $criteria->ordinals()->delete();
                }
            } else {
                $criteria->ordinals()->delete();
            }

            return $criteria;
        });
    }
}
