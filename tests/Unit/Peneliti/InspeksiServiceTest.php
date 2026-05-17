<?php

namespace Tests\Unit\Peneliti;

use App\Models\Criteria;
use App\Models\CriteriaOrdinal;
use App\Models\InspeksiTanaman;
use App\Models\Tanaman;
use App\Models\User;
use App\Services\peneliti\inspeksi\inspeksiService;
use App\Services\peneliti\TanamanLoggingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class InspeksiServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_inspeksi_and_inspeksi_tanaman(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tanaman = Tanaman::factory()->create();

        $loggingService = Mockery::mock(TanamanLoggingService::class);
        $loggingService
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::type('array'));

        $service = new inspeksiService($loggingService);

        $validated = [
            'tanggal_inspeksi' => '2026-05-18',
            'catatan'          => 'Tanaman sehat',
            'stage'            => 'checkup',
            'plants'           => [
                [
                    'id'          => $tanaman->id,
                    'status'      => 'hidup',
                    'label'       => 'A1',
                ],
            ],
        ];

        $service->store($validated);

        $this->assertDatabaseHas('inspeksis', [
            'tanggal_inspeksi' => '2026-05-18',
            'catatan'          => 'Tanaman sehat',
            'stage'            => 'checkup',
            'user_id'          => $user->id,
        ]);

        $this->assertDatabaseHas('inspeksi_tanaman', [
            'tanaman_id' => $tanaman->id,
            'status'     => 'hidup',
            'labeling'   => 'A1',
            'catatan'    => 'Tanaman sehat',
        ]);
    }

    public function test_store_creates_evaluation_values_when_stage_is_evaluasi(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tanaman = Tanaman::factory()->create();

        $criteriaNumeric = Criteria::factory()->create([
            'skala' => 'numerik',
        ]);

        $criteriaOrdinal = Criteria::factory()->create([
            'skala' => 'ordinal',
        ]);

        $ordinal = CriteriaOrdinal::factory()->create([
            'criteria_id' => $criteriaOrdinal->id,
            'nilai'       => 2,
        ]);

        $loggingService = Mockery::mock(TanamanLoggingService::class);
        $loggingService->shouldReceive('store')->once();

        $service = new inspeksiService($loggingService);

        $validated = [
            'tanggal_inspeksi' => '2026-05-18',
            'catatan'          => 'Evaluasi tanaman',
            'stage'            => 'evaluasi',
            'plants'           => [
                [
                    'id'       => $tanaman->id,
                    'status'   => 'hidup',
                    'label'    => 'E1',
                    'kriteria' => [
                        $criteriaNumeric->id => [
                            'skala' => 'numerik',
                            'nilai' => 80,
                        ],
                        $criteriaOrdinal->id => [
                            'skala' => 'ordinal',
                            'nilai' => $ordinal->id,
                        ],
                    ],
                ],
            ],
        ];

        $service->store($validated);

        $inspeksiTanaman = InspeksiTanaman::where('tanaman_id', $tanaman->id)->first();

        $this->assertDatabaseHas('inspeksi_nilai_criteria', [
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'criteria_id'         => $criteriaNumeric->id,
            'nilai_numeric'       => 80,
            'criteria_ordinal_id' => null,
        ]);

        $this->assertDatabaseHas('inspeksi_nilai_criteria', [
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'criteria_id'         => $criteriaOrdinal->id,
            'nilai_numeric'       => null,
            'criteria_ordinal_id' => $ordinal->id,
        ]);
    }

    public function test_update_evaluasi_updates_or_creates_values(): void
    {
        $inspeksiTanaman = InspeksiTanaman::factory()->create();

        $criteria = Criteria::factory()->create([
            'skala' => 'numerik',
        ]);

        $loggingService = Mockery::mock(TanamanLoggingService::class);
        $service = new inspeksiService($loggingService);

        $result = $service->updateEvaluasi([
            [
                'criteria_id'         => $criteria->id,
                'nilai_numeric'       => 88,
                'criteria_ordinal_id' => null,
            ],
        ], $inspeksiTanaman->id);

        $this->assertSame($inspeksiTanaman->inspeksi_id, $result);

        $this->assertDatabaseHas('inspeksi_nilai_criteria', [
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'criteria_id'         => $criteria->id,
            'nilai_numeric'       => 88,
        ]);
    }
}
