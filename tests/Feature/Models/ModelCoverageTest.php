<?php

namespace Tests\Feature\Models;

use App\Models\AnggotaTimExplorasi;
use App\Models\CollectorInfo;
use App\Models\Criteria;
use App\Models\CriteriaOrdinal;
use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Models\LegalDocuments;
use App\Models\Penerimaan;
use App\Models\PenerimaanTanaman;
use App\Models\Penyemaian;
use App\Models\PenyemaianTanaman;
use App\Models\Role;
use App\Models\Tanaman;
use App\Models\TanamanInfo;
use App\Models\TanamanStatusLogs;
use App\Models\TimExplorasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_model_relationships_are_defined(): void
    {
        $this->assertInstanceOf(BelongsTo::class, (new AnggotaTimExplorasi)->TimExplorasi());
        $this->assertInstanceOf(BelongsTo::class, (new AnggotaTimExplorasi)->Collector());

        $this->assertInstanceOf(BelongsTo::class, (new CollectorInfo)->user());
        $this->assertInstanceOf(HasMany::class, (new CollectorInfo)->penerimaanTanaman());

        $this->assertInstanceOf(HasMany::class, (new Criteria)->ordinals());
        $this->assertInstanceOf(HasMany::class, (new Criteria)->inspeksiNilaiCriteria());

        $this->assertInstanceOf(BelongsTo::class, (new CriteriaOrdinal)->Criteria());
        $this->assertInstanceOf(HasMany::class, (new CriteriaOrdinal)->inspeksiNilaiCriteria());

        $this->assertInstanceOf(BelongsTo::class, (new Inspeksi)->user());
        $this->assertInstanceOf(HasMany::class, (new Inspeksi)->inspeksiTanaman());

        $this->assertInstanceOf(BelongsTo::class, (new InspeksiNilaiCriteria)->inspeksiTanaman());
        $this->assertInstanceOf(BelongsTo::class, (new InspeksiNilaiCriteria)->criteria());
        $this->assertInstanceOf(BelongsTo::class, (new InspeksiNilaiCriteria)->criteriaOrdinal());

        $this->assertInstanceOf(BelongsTo::class, (new InspeksiTanaman)->inspeksi());
        $this->assertInstanceOf(HasMany::class, (new InspeksiTanaman)->nilaiCriteria());
        $this->assertInstanceOf(BelongsTo::class, (new InspeksiTanaman)->tanaman());

        $this->assertInstanceOf(BelongsTo::class, (new LegalDocuments)->penerimaan());

        $this->assertInstanceOf(HasMany::class, (new Penerimaan)->penerimaanTanaman());
        $this->assertInstanceOf(BelongsTo::class, (new Penerimaan)->user());
        $this->assertInstanceOf(HasMany::class, (new Penerimaan)->legalDocument());
        $this->assertInstanceOf(BelongsTo::class, (new Penerimaan)->TimExplorasi());

        $this->assertInstanceOf(BelongsTo::class, (new PenerimaanTanaman)->collector());
        $this->assertInstanceOf(BelongsTo::class, (new PenerimaanTanaman)->TanamanInfo());
        $this->assertInstanceOf(HasMany::class, (new PenerimaanTanaman)->Tanaman());

        $this->assertInstanceOf(BelongsTo::class, (new Penyemaian)->user());
        $this->assertInstanceOf(HasMany::class, (new Penyemaian)->penyemaianTanaman());

        $this->assertInstanceOf(BelongsTo::class, (new PenyemaianTanaman)->penyemaian());
        $this->assertInstanceOf(BelongsTo::class, (new PenyemaianTanaman)->tanaman());

        $this->assertInstanceOf(HasMany::class, (new Role)->users());

        $this->assertInstanceOf(BelongsTo::class, (new Tanaman)->tanamanPenerimaan());
        $this->assertInstanceOf(HasMany::class, (new Tanaman)->penyemaianTanaman());
        $this->assertInstanceOf(HasMany::class, (new Tanaman)->inspeksiTanaman());

        $this->assertInstanceOf(BelongsTo::class, (new TanamanInfo)->PenerimaanTanaman());

        $this->assertInstanceOf(BelongsTo::class, (new TanamanStatusLogs)->tanaman());
        $this->assertInstanceOf(BelongsTo::class, (new TanamanStatusLogs)->user());

        $this->assertInstanceOf(HasMany::class, (new TimExplorasi)->AnggotaTimExplorasi());
        $this->assertInstanceOf(HasMany::class, (new TimExplorasi)->Penerimaan());

        $this->assertInstanceOf(BelongsTo::class, (new User)->roles());
        $this->assertInstanceOf(HasOne::class, (new User)->collectorInfo());
    }

    public function test_criteria_active_scope_only_returns_active_criteria(): void
    {
        Criteria::create([
            'nama_criteria' => 'Active Criteria',
            'satuan' => null,
            'bobot' => 1,
            'tipe' => 'benefit',
            'skala' => 'numerik',
            'preference_function' => 'usual',
            'param_q' => null,
            'param_p' => null,
            'param_sigma' => null,
            'is_active' => true,
        ]);

        Criteria::create([
            'nama_criteria' => 'Inactive Criteria',
            'satuan' => null,
            'bobot' => 1,
            'tipe' => 'benefit',
            'skala' => 'numerik',
            'preference_function' => 'usual',
            'param_q' => null,
            'param_p' => null,
            'param_sigma' => null,
            'is_active' => false,
        ]);

        $result = Criteria::active()->get();

        $this->assertCount(1, $result);
        $this->assertSame('Active Criteria', $result->first()->nama_criteria);
    }

    public function test_criteria_ordinal_can_resolve_nilai_by_operator(): void
    {
        $criteria = Criteria::create([
            'nama_criteria' => 'Tingkat Kesehatan',
            'satuan' => null,
            'bobot' => 1,
            'tipe' => 'benefit',
            'skala' => 'ordinal',
            'preference_function' => 'usual',
            'param_q' => null,
            'param_p' => null,
            'param_sigma' => null,
            'is_active' => true,
        ]);

        CriteriaOrdinal::create([
            'criteria_id' => $criteria->id,
            'label' => 'Rendah',
            'nilai' => 1,
            'operator' => 'lt',
            'range_from' => 50,
            'range_to' => null,
            'urutan' => 1,
        ]);

        CriteriaOrdinal::create([
            'criteria_id' => $criteria->id,
            'label' => 'Sedang',
            'nilai' => 2,
            'operator' => 'between',
            'range_from' => 50,
            'range_to' => 80,
            'urutan' => 2,
        ]);

        CriteriaOrdinal::create([
            'criteria_id' => $criteria->id,
            'label' => 'Tinggi',
            'nilai' => 3,
            'operator' => 'gte',
            'range_from' => 81,
            'range_to' => null,
            'urutan' => 3,
        ]);

        $this->assertSame(1, CriteriaOrdinal::resolveNilai(40, $criteria->id));
        $this->assertSame(2, CriteriaOrdinal::resolveNilai(70, $criteria->id));
        $this->assertSame(3, CriteriaOrdinal::resolveNilai(90, $criteria->id));
        $this->assertNull(CriteriaOrdinal::resolveNilai(999, 999));
    }

    public function test_tanaman_nomor_akses_accessor(): void
    {
        $tanamanInfo = TanamanInfo::factory()->create();
        $collector = CollectorInfo::factory()->create();
        $penerimaan = Penerimaan::factory()->create();
        $penerimaanTanaman = PenerimaanTanaman::create([
            'penerimaan_id' => $penerimaan->id,
            'nomor_akses' => 'FDM',
            'jumlah_material' => 1,
            'tanaman_info_id' => $tanamanInfo->id,
            'habitus' => 'tree',
            'collector_id' => $collector->id,
            'locality' => 'Bogor',
            'vak_no' => 'A1',
        ]);

        $tanaman = Tanaman::create([
            'tanaman_penerimaan_id' => $penerimaanTanaman->id,
            'nomor_urut' => 7,
        ]);

        $this->assertSame('FDM-007', $tanaman->nomor_akses);
    }
}
