<?php

namespace App\Services\peneliti\algorithm;

use Illuminate\Support\Collection;

class PrometheeIIService
{
    /**
     * Hitung ranking PROMETHEE II.
     *
     * @param  Collection  $alternatives
     *                                    [{tanaman_id, inspeksi_tanaman_id, nilai: [criteria_id => float]}]
     * @param  Collection  $criterion
     *                                 Criteria models dengan bobot, tipe, preference_function, params
     * @return Collection
     *                    [{tanaman_id, inspeksi_tanaman_id, leaving_flow, entering_flow, net_flow, ranking}]
     */
    public static function calculate(Collection $alternatives, Collection $criterion): Collection
    {
        $n = $alternatives->count();
        if ($n === 1) {
            $alt = $alternatives->first();

            return collect([
                'tanaman_id' => $alt['tanaman_id'],
                'inspeksi_tanaman_id' => $alt['inspeksi_tanaman_id'],
                'leaving_flow' => 0.0,
                'entering_flow' => 0.0,
                'net_flow' => 0.0,
                'ranking' => 1,
            ]);
        }

        $alts = $alternatives->values();
        $crts = $criterion->values();
        $criteriaIds = $crts->pluck('id')->all();
        $warnings = [];
        foreach ($alts as $alt) {
            $missingIds = array_diff($criteriaIds, array_keys($alt['nilai']));
            if (! empty($missingIds)) {
                $missingNames = $crts
                    ->whereIn('id', $missingIds)
                    ->pluck('nama_criteria', 'id')
                    ->all();

                $warnings[] = [
                    'tanaman_id' => $alt['tanaman_id'],
                    'missing_criteria' => $missingNames,
                ];
            }
        }

        if (! empty($warnings)) {
            return collect([
                'status' => 'incomplete',
                'warnings' => $warnings,
            ]);
        }
        // dump($alts);
        // dump($crts);
        $matrix = [];  // debug
        $pi = [];
        foreach ($alts as $i => $a) {
            foreach ($alts as $j => $b) {
                if ($i === $j) {
                    $piValue = '-';

                    // dump("[$i][$j]: $piValue");
                    continue;
                }
                // $row = [];
                $piValue = 0.0;
                foreach ($crts as $criteria) {
                    $id = $criteria->id;
                    $fA = (float) ($a['nilai'][$id] ?? 0);
                    $fB = (float) ($b['nilai'][$id] ?? 0);
                    $d = ($criteria->tipe === 'benefit') ? ($fA - $fB) : ($fB - $fA);
                    // dump("[$i][$j]:$d");
                    $h = self::preferenceFunction($criteria, $d);
                    $piValue += (float) $criteria->bobot * $h;

                }
                $pi[$i][$j] = $piValue;
            }
        }
        $results = [];
        $factor = 1.0 / ($n - 1);
        foreach ($alts as $i => $a) {
            $leaving = 0.0;
            $entering = 0.0;
            foreach ($alts as $j => $b) {
                if ($i === $j) {
                    continue;
                }
                $leaving += $pi[$i][$j];
                $entering += $pi[$j][$i];
            }
            $results[] = [
                'tanaman_id' => $a['tanaman_id'],
                'inspeksi_tanaman_id' => $a['inspeksi_tanaman_id'],
                'leaving_flow' => round($factor * $leaving, 6),
                'entering_flow' => round($factor * $entering, 6),
                'net_flow' => round($factor * ($leaving - $entering), 6),
            ];
        }
        usort($results, fn ($x, $y) => $y['net_flow'] <=> $x['net_flow']);

        foreach ($results as $rank => &$row) {
            $row['ranking'] = $rank + 1;
            // dump($row);
        }
        unset($row);

        return collect($results);

    }

    /**
     * Hitung nilai fungsi preferensi berdasarkan tipe dan selisih d.
     *
     * @param  object  $criteria  Criteria model
     * @param  float  $d  Selisih f_j(a) − f_j(b) (sudah memperhatikan tipe cost/benefit)
     * @return float|int Nilai H ∈ [0, 1]
     */
    private static function preferenceFunction(object $criteria, float $d): float
    {
        $q = (float) ($criteria->param_q ?? 0);
        $p = (float) ($criteria->param_p ?? 0);
        $s = (float) ($criteria->param_sigma ?? 0);

        return match ($criteria->preference_function) {
            'usual' => $d > 0 ? 1.0 : 0.0, // selisih > 0 lalu preferensi penuh
            'quasi' => $d > $q ? 1.0 : 0.0, // toleransi ambang q, setelah itu preferensi penuh
            'linear' => $d <= 0 ? 0.0 : min($d / $p, 1.0), // preferensi naik linear antara 0..p
            'level' => $d <= $q ? 0.0 : ($d <= $p ? 0.5 : 1.0), //  Level: tidak ada preferensi ≤ q, setengah preferensi q..p, penuh > p
            'v_shape' => $d <= 0 ? 0.0 : min($d / $p, 1.0), // // V-Shape: sama seperti linear (alias)
            'gaussian' => $d <= 0 ? 0.0 : 1.0 - exp(-($d ** 2) / (2 * $s ** 2)), // Gaussian: preferensi naik mengikuti kurva gaussian
        };
    }
}
