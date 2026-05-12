<?php

namespace App\Services\peneliti\algorithm;

use Illuminate\Support\Collection;

class PrometheeIIServices
{
    /**
     * Hitung ranking PROMETHEE II.
     *
     * @param  Collection  $alternatives
     *         [{tanaman_id, inspeksi_tanaman_id, nilai: [criteria_id => float]}]
     * @param  Collection  $criterias
     *         Criteria models dengan bobot, tipe, preference_function, params
     * @return Collection
     *         [{tanaman_id, inspeksi_tanaman_id, leaving_flow, entering_flow, net_flow, ranking}]
     */
    public static function calculate(Collection $alternatives, Collection $criterias): Collection
    {
        $n = $alternatives->count();

        // Edge case: hanya 1 tanaman — PROMETHEE II tidak bisa dihitung, semua flow = 0
        if ($n === 1) {
            $alt = $alternatives->first();

            return collect([[
                'tanaman_id'          => $alt['tanaman_id'],
                'inspeksi_tanaman_id' => $alt['inspeksi_tanaman_id'],
                'leaving_flow'        => 0.0,
                'entering_flow'       => 0.0,
                'net_flow'            => 0.0,
                'ranking'             => 1,
            ]]);
        }

        // Re-index agar indeks loop 0..n-1 konsisten
        $alts  = $alternatives->values();
        $crits = $criterias->values();

        // ── Tahap 4: Bangun matrix π[i][j] ─────────────────────────────────────
        // π(a,b) = Σ_j [ w_j × H_j(d_j(a,b)) ]
        $pi = [];

        foreach ($alts as $i => $a) {
            foreach ($alts as $j => $b) {
                if ($i === $j) {
                    continue;
                }

                $piValue = 0.0;

                foreach ($crits as $criteria) {
                    $id = $criteria->id;
                    $fA = (float) ($a['nilai'][$id] ?? 0);
                    $fB = (float) ($b['nilai'][$id] ?? 0);

                    // Tahap 2: d_j(a,b) — cost dibalik
                    $d = ($criteria->tipe === 'benefit')
                        ? ($fA - $fB)
                        : ($fB - $fA);

                    // Tahap 3: H_j(d) — fungsi preferensi
                    $h = self::preferenceFunction($criteria, $d);

                    $piValue += (float) $criteria->bobot * $h;
                }

                $pi[$i][$j] = $piValue;
            }
        }

        // ── Tahap 5: Hitung Φ⁺, Φ⁻, Φ ─────────────────────────────────────────
        $results = [];
        $factor  = 1.0 / ($n - 1);

        foreach ($alts as $i => $a) {
            $leaving  = 0.0;
            $entering = 0.0;

            foreach ($alts as $j => $b) {
                if ($i === $j) {
                    continue;
                }
                $leaving  += $pi[$i][$j];   // Σ π(a,b)
                $entering += $pi[$j][$i];   // Σ π(b,a)
            }

            $results[] = [
                'tanaman_id'          => $a['tanaman_id'],
                'inspeksi_tanaman_id' => $a['inspeksi_tanaman_id'],
                'leaving_flow'        => round($factor * $leaving, 6),
                'entering_flow'       => round($factor * $entering, 6),
                'net_flow'            => round($factor * ($leaving - $entering), 6),
            ];
        }

        // ── Tahap 6: Sort descending net_flow → ranking ─────────────────────────
        usort($results, fn ($x, $y) => $y['net_flow'] <=> $x['net_flow']);

        foreach ($results as $rank => &$row) {
            $row['ranking'] = $rank + 1;
        }
        unset($row);

        return collect($results);
    }

    // ── Helper: Fungsi Preferensi H_j(d) ────────────────────────────────────────

    /**
     * Hitung nilai fungsi preferensi berdasarkan tipe dan selisih d.
     *
     * @param  object  $criteria  Criteria model
     * @param  float   $d         Selisih f_j(a) − f_j(b) (sudah memperhatikan tipe cost/benefit)
     * @return float   Nilai H ∈ [0, 1]
     */
    private static function preferenceFunction(object $criteria, float $d): float
    {
        $q = (float) ($criteria->param_q     ?? 0);
        $p = (float) ($criteria->param_p     ?? 1);   // default 1 → hindari div-by-zero
        $s = (float) ($criteria->param_sigma ?? 1);   // default 1 → hindari div-by-zero

        return match ($criteria->preference_function) {
            // Usual: selisih > 0 → preferensi penuh
            'usual'    => $d > 0 ? 1.0 : 0.0,

            // Quasi: toleransi ambang q, setelah itu preferensi penuh
            'quasi'    => $d > $q ? 1.0 : 0.0,

            // Linear: preferensi naik linear antara 0..p
            'linear'   => $d <= 0 ? 0.0 : min($d / $p, 1.0),

            // Level: tidak ada preferensi ≤ q, setengah preferensi q..p, penuh > p
            'level'    => $d <= $q ? 0.0 : ($d <= $p ? 0.5 : 1.0),

            // V-Shape: sama seperti linear (alias)
            'v_shape'  => $d <= 0 ? 0.0 : min($d / $p, 1.0),

            // Gaussian: preferensi naik mengikuti kurva gaussian
            'gaussian' => $d <= 0 ? 0.0 : 1.0 - exp(-($d ** 2) / (2 * $s ** 2)),

            default    => 0.0,
        };
    }
}
