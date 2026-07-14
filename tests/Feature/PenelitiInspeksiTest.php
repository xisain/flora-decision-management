<?php

namespace Tests\Feature;

use App\Models\Criteria;
use App\Models\CriteriaOrdinal;
use App\Models\Inspeksi;
use App\Models\InspeksiTanaman;
use App\Models\PenyemaianTanaman;
use App\Models\Role;
use App\Models\Tanaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenelitiInspeksiTest extends TestCase
{
    use RefreshDatabase;

    protected $peneliti;

    protected function setUp(): void
    {
        parent::setUp();

        $rolePeneliti = Role::factory()->create(['name' => 'teknisi pembibitan']);
        $this->peneliti = User::factory()->create(['roles_id' => $rolePeneliti->id]);
    }

    public function test_peneliti_bisa_melihat_inspeksi_table()
    {
        $tanamanCheckup = $this->buatTanamanDiStage('checkup');
        $tanamanLabeling = $this->buatTanamanDiStage('labeling');
        $tanamanAklimatisasi = $this->buatTanamanDiStage('aklimatisasi');
        $tanamanEvaluasi = $this->buatTanamanDiStage('evaluasi');

        $response = $this->actingAs($this->peneliti)
            ->get(route('peneliti.inspeksi.create'));

        $response->assertStatus(200);

        // Cek tiap tanaman muncul di view
        $response->assertViewHas('checkup', fn ($data) => $data->contains('id', $tanamanCheckup->id)
        );
        $response->assertViewHas('labeling', fn ($data) => $data->contains('id', $tanamanLabeling->id)
        );
        $response->assertViewHas('aklimatisasi', fn ($data) => $data->contains('id', $tanamanAklimatisasi->id)
        );
        $response->assertViewHas('evaluasi', fn ($data) => $data->contains('id', $tanamanEvaluasi->id)
        );

    }

    public function test_form_evaluasi_memuat_data_untuk_autofill_endemisitas_dan_status_konservasi(): void
    {
        $tanaman = $this->buatTanamanDiStage('evaluasi');
        $tanaman->tanamanPenerimaan->tanamanInfo->update([
            'redlist_category' => 'Terancam',
            'endemisitas' => 'Endemik di Bulungan',
        ]);

        $endemik = Criteria::factory()->ordinal()->create([
            'nama_criteria' => 'Endemisitas',
        ]);
        CriteriaOrdinal::factory()->create([
            'criteria_id' => $endemik->id,
            'label' => 'Endemik di Bulungan',
            'nilai' => 3,
        ]);

        $statusKonservasi = Criteria::factory()->ordinal()->create([
            'nama_criteria' => 'Status Konservasi (Endanger)',
        ]);
        CriteriaOrdinal::factory()->create([
            'criteria_id' => $statusKonservasi->id,
            'label' => 'Terancam',
            'nilai' => 4,
        ]);

        $response = $this->actingAs($this->peneliti)
            ->get(route('peneliti.inspeksi.create'));

        $response->assertOk();
        $response->assertSee('Endemik di Bulungan');
        $response->assertSee('Terancam');
        $response->assertSee('togglePlantById', false);
        $response->assertSee('defaultCriteriaValue', false);
    }

    public function test_peneliti_bisa_membuat_inspeksi_checkup()
    {
        $tanaman = Tanaman::factory()->create();

        $data = [
            'tanggal_inspeksi' => '2026-05-14',
            'catatan' => 'Checkup rutin',
            'stage' => 'checkup',
            'plants' => [
                [
                    'id' => $tanaman->id,
                    'status' => 'hidup',
                ],
            ],
        ];

        $response = $this->actingAs($this->peneliti)->post(route('peneliti.inspeksi.store'), $data);

        $response->assertRedirect(route('peneliti.inspeksi.index'));
        $this->assertDatabaseHas('inspeksis', [
            'stage' => 'checkup',
            'catatan' => 'Checkup rutin',
        ]);
        // Status updates are tracked elsewhere (e.g., in tanaman_status_logs)
    }

    public function test_peneliti_bisa_membuat_inspeksi_labeling()
    {
        $tanaman = Tanaman::factory()->create();

        $data = [
            'tanggal_inspeksi' => '2026-05-14',
            'catatan' => 'labeling',
            'stage' => 'labeling',
            'plants' => [
                [
                    'id' => $tanaman->id,
                    'status' => 'hidup',
                    'label' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->peneliti)->post(route('peneliti.inspeksi.store'), $data);

        $response->assertRedirect(route('peneliti.inspeksi.index'));
        $this->assertDatabaseHas('inspeksis', [
            'stage' => 'labeling',
            'catatan' => 'labeling',
        ]);
        // Status updates are tracked elsewhere (e.g., in tanaman_status_logs)
    }

    public function test_peneliti_bisa_membuat_inspeksi_aklimatisasi()
    {
        $tanaman = Tanaman::factory()->create();

        $data = [
            'tanggal_inspeksi' => '2026-05-14',
            'catatan' => 'Aklimatisasi batch 1',
            'stage' => 'aklimatisasi',
            'plants' => [
                [
                    'id' => $tanaman->id,
                    'status' => 'hidup',
                ],
            ],
        ];

        $response = $this->actingAs($this->peneliti)->post(route('peneliti.inspeksi.store'), $data);

        $response->assertRedirect(route('peneliti.inspeksi.index'));
        $this->assertDatabaseHas('inspeksis', [
            'stage' => 'aklimatisasi',
        ]);
    }

    public function test_peneliti_bisa_membuat_inspeksi_evaluasi()
    {
        $tanaman = Tanaman::factory()->create();
        $criteriaNumerik = Criteria::factory()->numeric()->create();
        $criteriaOrdinal = Criteria::factory()->ordinal()->create();
        $ordinalVal = CriteriaOrdinal::factory()->create(['criteria_id' => $criteriaOrdinal->id, 'nilai' => 3]);

        $data = [
            'tanggal_inspeksi' => '2026-05-14',
            'catatan' => 'Evaluasi kelayakan',
            'stage' => 'evaluasi',
            'plants' => [
                [
                    'id' => $tanaman->id,
                    'status' => 'hidup',
                    'kriteria' => [
                        $criteriaNumerik->id => [
                            'nilai' => 15.5,
                            'skala' => 'numerik',
                        ],
                        $criteriaOrdinal->id => [
                            'nilai' => $ordinalVal->id,
                            'skala' => 'ordinal',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->peneliti)->post(route('peneliti.inspeksi.store'), $data);

        $response->assertRedirect(route('peneliti.inspeksi.index'));
        $this->assertDatabaseHas('inspeksis', [
            'stage' => 'evaluasi',
        ]);

        $inspeksi = Inspeksi::where('stage', 'evaluasi')->first();
        $inspeksiTanaman = InspeksiTanaman::where('inspeksi_id', $inspeksi->id)->first();

        $this->assertDatabaseHas('inspeksi_nilai_criteria', [
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'criteria_id' => $criteriaNumerik->id,
            'nilai_numeric' => 15.5,
        ]);
    }

    public function test_detail_inspeksi_bisa_dilihat()
    {
        $inspeksi = Inspeksi::factory()->checkup()->create();

        $response = $this->actingAs($this->peneliti)->get(route('peneliti.inspeksi.show', $inspeksi->id));

        $response->assertStatus(200);
        // assert view has data...
    }

    public function test_edit_evaluasi_menampilkan_criteria_aktif()
    {
        $inspeksi = Inspeksi::factory()->evaluasi()->create();
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create([
            'inspeksi_id' => $inspeksi->id,
            'tanaman_id' => $tanaman->id,
        ]);

        Criteria::factory()->create(['is_active' => true]);
        Criteria::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->peneliti)->get(route('peneliti.inspeksi.editEvaluasi', $inspeksiTanaman->id));

        $response->assertStatus(200);
        $activeCriteria = Criteria::where('is_active', true)->get();
        foreach ($activeCriteria as $c) {
            $response->assertSee($c->nama_criteria);
        }
    }

    public function test_update_evaluasi_menyimpan_nilai_numeric()
    {
        $inspeksi = Inspeksi::factory()->evaluasi()->create();
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create([
            'inspeksi_id' => $inspeksi->id,
            'tanaman_id' => $tanaman->id,
        ]);

        $criteriaNumerik = Criteria::factory()->numeric()->create();

        $data = [
            'nilai' => [
                [
                    'criteria_id' => $criteriaNumerik->id,
                    'nilai_numeric' => 20.5,
                    'criteria_ordinal_id' => null,
                ],
            ],
        ];

        $response = $this->actingAs($this->peneliti)->put(route('peneliti.inspeksi.updateEvaluasi', $inspeksiTanaman->id), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('inspeksi_nilai_criteria', [
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'criteria_id' => $criteriaNumerik->id,
            'nilai_numeric' => 20.5,
        ]);
    }

    public function test_update_evaluasi_menyimpan_nilai_ordinal()
    {
        $inspeksi = Inspeksi::factory()->evaluasi()->create();
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create([
            'inspeksi_id' => $inspeksi->id,
            'tanaman_id' => $tanaman->id,
        ]);

        $criteriaOrdinal = Criteria::factory()->ordinal()->create();
        $ordinalVal = CriteriaOrdinal::factory()->create(['criteria_id' => $criteriaOrdinal->id, 'nilai' => 4]);

        $data = [
            'nilai' => [
                [
                    'criteria_id' => $criteriaOrdinal->id,
                    'nilai_numeric' => null,
                    'criteria_ordinal_id' => $ordinalVal->id,
                ],
            ],
        ];

        $response = $this->actingAs($this->peneliti)->put(route('peneliti.inspeksi.updateEvaluasi', $inspeksiTanaman->id), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('inspeksi_nilai_criteria', [
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'criteria_id' => $criteriaOrdinal->id,
            'criteria_ordinal_id' => $ordinalVal->id,
        ]);
    }

    // Helper Function
    private function tanamanSudahSemai(): Tanaman
    {
        $tanaman = Tanaman::factory()->create();
        PenyemaianTanaman::factory()->create([
            'tanaman_id' => $tanaman->id,
        ]);

        return $tanaman;
    }

    private function buatInspeksi(Tanaman $tanaman, string $stage, string $status = 'hidup')
    {
        $inspeksi = Inspeksi::factory()->create(['stage' => $stage]);

        InspeksiTanaman::factory()->create([
            'tanaman_id' => $tanaman->id,
            'inspeksi_id' => $inspeksi->id,
            'status' => $status,
        ]);

    }

    private function buatTanamanDiStage(string $targetStage): Tanaman
    {
        $tanaman = $this->tanamanSudahSemai();

        // Setiap stage butuh history inspeksi sebelumnya
        $pipeline = ['checkup', 'labeling', 'aklimatisasi', 'evaluasi'];
        $index = array_search($targetStage, $pipeline);

        foreach (array_slice($pipeline, 0, $index) as $stage) {
            $this->buatInspeksi($tanaman, $stage);
        }

        return $tanaman;
    }
}
