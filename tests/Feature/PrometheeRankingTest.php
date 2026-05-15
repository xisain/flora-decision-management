<?php

namespace Tests\Feature;

use App\Models\Criteria;
use App\Services\peneliti\algorithm\PrometheeIIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrometheeRankingTest extends TestCase
{
    use RefreshDatabase;

    public function test_satu_alternatif_menghasilkan_ranking_1()
    {
        $criteria = collect([
            (object) ['id' => 1, 'bobot' => 1.0, 'tipe' => 'benefit', 'preference_function' => 'usual']
        ]);

        $alternatives = collect([
            [
                'tanaman_id' => 10,
                'inspeksi_tanaman_id' => 100,
                'nilai' => [1 => 50]
            ]
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertEquals(1, $result->first()['ranking']);
        $this->assertEquals(0.0, $result->first()['net_flow']);
    }

    public function test_nilai_criteria_tidak_lengkap_menghasilkan_status_incomplete()
    {
        $criteria = collect([
            (object) ['id' => 1, 'nama_criteria' => 'Tinggi', 'bobot' => 0.5, 'tipe' => 'benefit', 'preference_function' => 'usual'],
            (object) ['id' => 2, 'nama_criteria' => 'Diameter', 'bobot' => 0.5, 'tipe' => 'benefit', 'preference_function' => 'usual']
        ]);

        $alternatives = collect([
            [
                'tanaman_id' => 10,
                'inspeksi_tanaman_id' => 100,
                'nilai' => [1 => 50] // missing criteria 2
            ],
            [
                'tanaman_id' => 11,
                'inspeksi_tanaman_id' => 101,
                'nilai' => [1 => 60, 2 => 10]
            ]
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertEquals('incomplete', $result->get('status'));
        $this->assertNotEmpty($result->get('warnings'));
    }

    public function test_benefit_criteria_menghitung_nilai_terbesar_sebagai_lebih_baik()
    {
        $criteria = collect([
            (object) ['id' => 1, 'bobot' => 1.0, 'tipe' => 'benefit', 'preference_function' => 'usual']
        ]);

        $alternatives = collect([
            ['tanaman_id' => 10, 'inspeksi_tanaman_id' => 100, 'nilai' => [1 => 50]], // smaller
            ['tanaman_id' => 11, 'inspeksi_tanaman_id' => 101, 'nilai' => [1 => 80]]  // larger
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertEquals(11, $result->first()['tanaman_id']); // alt with 80 should be rank 1
        $this->assertEquals(1, $result->first()['ranking']);
    }

    public function test_cost_criteria_menghitung_nilai_terkecil_sebagai_lebih_baik()
    {
        $criteria = collect([
            (object) ['id' => 1, 'bobot' => 1.0, 'tipe' => 'cost', 'preference_function' => 'usual']
        ]);

        $alternatives = collect([
            ['tanaman_id' => 10, 'inspeksi_tanaman_id' => 100, 'nilai' => [1 => 50]], // smaller
            ['tanaman_id' => 11, 'inspeksi_tanaman_id' => 101, 'nilai' => [1 => 80]]  // larger
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertEquals(10, $result->first()['tanaman_id']); // alt with 50 should be rank 1 (since cost)
        $this->assertEquals(1, $result->first()['ranking']);
    }

    public function test_net_flow_terbesar_mendapat_ranking_tertinggi()
    {
        $criteria = collect([
            (object) ['id' => 1, 'bobot' => 0.5, 'tipe' => 'benefit', 'preference_function' => 'usual'],
            (object) ['id' => 2, 'bobot' => 0.5, 'tipe' => 'benefit', 'preference_function' => 'usual']
        ]);

        $alternatives = collect([
            ['tanaman_id' => 10, 'inspeksi_tanaman_id' => 100, 'nilai' => [1 => 10, 2 => 10]],
            ['tanaman_id' => 11, 'inspeksi_tanaman_id' => 101, 'nilai' => [1 => 50, 2 => 50]],
            ['tanaman_id' => 12, 'inspeksi_tanaman_id' => 102, 'nilai' => [1 => 90, 2 => 90]],
        ]);

        $result = PrometheeIIService::calculate($alternatives, $criteria);

        $this->assertEquals(12, $result[0]['tanaman_id']); // Highest
        $this->assertEquals(11, $result[1]['tanaman_id']); // Middle
        $this->assertEquals(10, $result[2]['tanaman_id']); // Lowest

        $this->assertTrue($result[0]['net_flow'] > $result[1]['net_flow']);
        $this->assertTrue($result[1]['net_flow'] > $result[2]['net_flow']);
    }

    public function test_semua_preference_function_berjalan_sesuai_aturan()
    {
        // Testing one by one with linear for instance
        $criteriaLinear = collect([
            (object) ['id' => 1, 'bobot' => 1.0, 'tipe' => 'benefit', 'preference_function' => 'linear', 'param_p' => 100]
        ]);

        $alternativesLinear = collect([
            ['tanaman_id' => 10, 'inspeksi_tanaman_id' => 100, 'nilai' => [1 => 50]],
            ['tanaman_id' => 11, 'inspeksi_tanaman_id' => 101, 'nilai' => [1 => 100]],
        ]);

        $resultLinear = PrometheeIIService::calculate($alternativesLinear, $criteriaLinear);

        // Difference is 50. H = min(50/100, 1) = 0.5
        // Net flow for 11 is (0.5 - 0) / 1 = 0.5
        // Net flow for 10 is (0 - 0.5) / 1 = -0.5
        $rank1 = collect($resultLinear)->where('tanaman_id', 11)->first();
        $this->assertEquals(0.5, $rank1['net_flow']);

        // Gaussian
        $criteriaGaussian = collect([
            (object) ['id' => 1, 'bobot' => 1.0, 'tipe' => 'benefit', 'preference_function' => 'gaussian', 'param_sigma' => 10]
        ]);

        $alternativesGaussian = collect([
            ['tanaman_id' => 10, 'inspeksi_tanaman_id' => 100, 'nilai' => [1 => 10]],
            ['tanaman_id' => 11, 'inspeksi_tanaman_id' => 101, 'nilai' => [1 => 20]],
        ]);

        $resultGaussian = PrometheeIIService::calculate($alternativesGaussian, $criteriaGaussian);
        $rank1 = collect($resultGaussian)->where('tanaman_id', 11)->first();
        // Difference 10. H = 1 - exp(-(10^2) / 2*(10^2)) = 1 - exp(-100/200) = 1 - exp(-0.5) ≈ 0.393
        $this->assertEqualsWithDelta(0.3934, $rank1['net_flow'], 0.001);
    }
}
