<?php

namespace Tests\Feature;

use App\Models\CollectorInfo;
use App\Models\Criteria;
use App\Models\CriteriaOrdinal;
use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Models\legalDocuments;
use App\Models\Penerimaan;
use App\Models\PenerimaanTanaman;
use App\Models\penyemaian;
use App\Models\PenyemaianTanaman;
use App\Models\Role;
use App\Models\Tanaman;
use App\Models\TanamanInfo;
use App\Models\TimExplorasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactorySmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_factories_can_create_basic_records(): void
    {
        $role = Role::factory()->admin()->create();

        $user = User::factory()->create([
            'roles_id' => $role->id,
        ]);

        $collector = CollectorInfo::factory()->create([
            'user_id' => $user->id,
        ]);

        $timExplorasi = TimExplorasi::factory()->create();

        $penerimaan = Penerimaan::factory()->create([
            'user_id' => $user->id,
            'exploration_team_id' => $timExplorasi->id,
        ]);

        $tanamanInfo = TanamanInfo::factory()->create();

        $penerimaanTanaman = PenerimaanTanaman::factory()->create([
            'penerimaan_id' => $penerimaan->id,
            'tanaman_info_id' => $tanamanInfo->id,
            'collector_id' => $collector->id,
        ]);

        $tanaman = Tanaman::factory()->create([
            'tanaman_penerimaan_id' => $penerimaanTanaman->id,
        ]);

        $penyemaian = penyemaian::factory()->create([
            'user_id' => $user->id,
        ]);

        PenyemaianTanaman::factory()->create([
            'penyemaian_id' => $penyemaian->id,
            'tanaman_id' => $tanaman->id,
        ]);

        $inspeksi = Inspeksi::factory()->create([
            'user_id' => $user->id,
        ]);

        $inspeksiTanaman = InspeksiTanaman::factory()->create([
            'inspeksi_id' => $inspeksi->id,
            'tanaman_id' => $tanaman->id,
        ]);

        $criteria = Criteria::factory()->numeric()->create();

        InspeksiNilaiCriteria::factory()->create([
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'criteria_id' => $criteria->id,
            'nilai_numeric' => 80,
            'criteria_ordinal_id' => null,
        ]);

        // legalDocuments::factory()->create([
        //     'penerimaan_id' => $penerimaan->id,
        // ]);

        $this->assertDatabaseCount('roles', 1);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('collector_infos', 1);
        $this->assertDatabaseCount('exploration_team', 1);
        $this->assertDatabaseCount('penerimaans', 1);
        $this->assertDatabaseCount('tanaman_infos', 1);
        $this->assertDatabaseCount('tanaman_penerimaans', 1);
        $this->assertDatabaseCount('tanaman', 1);
        $this->assertDatabaseCount('penyemaian', 1);
        $this->assertDatabaseCount('penyemaian_tanaman', 1);
        $this->assertDatabaseCount('inspeksis', 1);
        $this->assertDatabaseCount('inspeksi_tanaman', 1);
        $this->assertDatabaseCount('criterias', 1);
        $this->assertDatabaseCount('inspeksi_nilai_criteria', 1);
        // $this->assertDatabaseCount('legal_documents', 1);
    }

    public function test_factory_can_create_ordinal_criteria_with_ordinal_value(): void
    {
        $criteria = Criteria::factory()->ordinal()->create();

        $criteriaOrdinal = CriteriaOrdinal::factory()->create([
            'criteria_id' => $criteria->id,
        ]);

        $this->assertDatabaseHas('criterias', [
            'id' => $criteria->id,
            'skala' => 'ordinal',
        ]);

        $this->assertDatabaseHas('criterias_ordinal', [
            'id' => $criteriaOrdinal->id,
            'criteria_id' => $criteria->id,
        ]);
    }
}
