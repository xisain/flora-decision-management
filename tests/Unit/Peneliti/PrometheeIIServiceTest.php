<?php

namespace Tests\Unit\Peneliti;

use App\Services\peneliti\algorithm\PrometheeIIService;
use PHPUnit\Framework\TestCase;

class PrometheeIIServiceTest extends TestCase
{
    public function test_calculate_returns_rank_one_when_only_one_alternative(): void
    {
        $alternatives = collect([
            [
                'tanaman_id'          => 1,
                'inspeksi_tanaman_id' => 10,
                'nilai'               => [
                    1 => 80,
                ],
            ],
        ]);

        $criteria = collect([
            (object) [
                'id'                  => 1,
                'nama_criteria'       => 'Tinggi',
                'tipe'                => 'benefit',
                'bobot'               => 1,
                'preference_function' => 'usual',
                'param_q'             => null,
                'param_p'             => null,
                'param_sigma'         => null,
            ],
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertCount(1, $result);
        $this->assertSame(1, $result[0]['ranking']);
        $this->assertSame(0.0, $result[0]['leaving_flow']);
        $this->assertSame(0.0, $result[0]['entering_flow']);
        $this->assertSame(0.0, $result[0]['net_flow']);
    }

    public function test_calculate_ranks_higher_benefit_value_first(): void
    {
        $alternatives = collect([
            [
                'tanaman_id'          => 1,
                'inspeksi_tanaman_id' => 101,
                'nilai'               => [
                    1 => 90,
                ],
            ],
            [
                'tanaman_id'          => 2,
                'inspeksi_tanaman_id' => 102,
                'nilai'               => [
                    1 => 50,
                ],
            ],
        ]);

        $criteria = collect([
            (object) [
                'id'                  => 1,
                'nama_criteria'       => 'Kesehatan',
                'tipe'                => 'benefit',
                'bobot'               => 1,
                'preference_function' => 'usual',
                'param_q'             => null,
                'param_p'             => null,
                'param_sigma'         => null,
            ],
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertSame(1, $result[0]['tanaman_id']);
        $this->assertSame(1, $result[0]['ranking']);
        $this->assertGreaterThan($result[1]['net_flow'], $result[0]['net_flow']);
    }

    public function test_calculate_ranks_lower_cost_value_first(): void
    {
        $alternatives = collect([
            [
                'tanaman_id'          => 1,
                'inspeksi_tanaman_id' => 101,
                'nilai'               => [
                    1 => 90,
                ],
            ],
            [
                'tanaman_id'          => 2,
                'inspeksi_tanaman_id' => 102,
                'nilai'               => [
                    1 => 50,
                ],
            ],
        ]);

        $criteria = collect([
            (object) [
                'id'                  => 1,
                'nama_criteria'       => 'Kerusakan',
                'tipe'                => 'cost',
                'bobot'               => 1,
                'preference_function' => 'usual',
                'param_q'             => null,
                'param_p'             => null,
                'param_sigma'         => null,
            ],
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertSame(2, $result[0]['tanaman_id']);
        $this->assertSame(1, $result[0]['ranking']);
    }

    public function test_calculate_returns_incomplete_when_alternative_missing_criteria(): void
    {
        $alternatives = collect([
            [
                'tanaman_id'          => 1,
                'inspeksi_tanaman_id' => 101,
                'nilai'               => [
                    1 => 90,
                ],
            ],
            [
                'tanaman_id'          => 2,
                'inspeksi_tanaman_id' => 102,
                'nilai'               => [],
            ],
        ]);

        $criteria = collect([
            (object) [
                'id'                  => 1,
                'nama_criteria'       => 'Kesehatan',
                'tipe'                => 'benefit',
                'bobot'               => 1,
                'preference_function' => 'usual',
                'param_q'             => null,
                'param_p'             => null,
                'param_sigma'         => null,
            ],
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertSame('incomplete', $result->get('status'));
        $this->assertNotEmpty($result->get('warnings'));
    }
}
