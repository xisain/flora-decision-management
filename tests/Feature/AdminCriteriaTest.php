<?php

namespace Tests\Feature;

use App\Models\Criteria;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCriteriaTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::factory()->create(['name' => 'admin']);
        $this->admin = User::factory()->create(['roles_id' => $roleAdmin->id]);
    }

    public function test_admin_bisa_melihat_table_index_criteria()
    {
        $response = $this->actingAs($this->admin)->get(route('criteria.index'));
        $response->assertStatus(200);
        $response->assertViewHas('criteria');
    }

    public function test_admin_bisa_melihat_view_buat_criteria()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('criteria.create'));

        $response->assertOk();
        $response->assertViewIs('admin.criteria.create');
    }

    public function test_admin_bisa_membuat_criteria_numeric()
    {
        $data = [
            'nama_kriteria' => 'Kriteria Numerik Test',
            'tipe' => 'benefit',
            'satuan' => 'cm',
            'skala' => 'numerik',
            'bobot' => 0.5,
            'preference_function' => 'usual',
        ];

        $response = $this->actingAs($this->admin)->post(route('criteria.store'), $data);

        $response->assertRedirect(route('criteria.index'));
        $this->assertDatabaseHas('criterias', [
            'nama_criteria' => 'Kriteria Numerik Test',
            'skala' => 'numerik',
        ]);
    }

    public function test_admin_bisa_membuat_criteria_ordinal()
    {
        $data = [
            'nama_kriteria' => 'Kriteria Ordinal Test',
            'tipe' => 'benefit',
            'skala' => 'ordinal',
            'bobot' => 0.3,
            'preference_function' => 'usual',
            'ordinal' => [
                [
                    'label' => 'Sangat Baik',
                    'nilai' => 5,
                    'operator' => 'gte',
                    'urutan' => 1,
                    'range_from' => 80,
                ],
                [
                    'label' => 'Baik',
                    'nilai' => 4,
                    'operator' => 'between',
                    'urutan' => 2,
                    'range_from' => 60,
                    'range_to' => 80,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('criteria.store'), $data);

        $response->assertRedirect(route('criteria.index'));
        $this->assertDatabaseHas('criterias', [
            'nama_criteria' => 'Kriteria Ordinal Test',
            'skala' => 'ordinal',
        ]);

        $criteria = Criteria::where('nama_criteria', 'Kriteria Ordinal Test')->first();
        $this->assertDatabaseHas('criterias_ordinal', [
            'criteria_id' => $criteria->id,
            'label' => 'Sangat Baik',
        ]);
    }

    public function test_bobot_wajib_diisi()
    {
        $data = [
            'nama_kriteria' => 'Test Bobot',
            'tipe' => 'benefit',
            'skala' => 'numerik',
            // 'bobot' is missing
            'preference_function' => 'usual',
        ];

        $response = $this->actingAs($this->admin)->post(route('criteria.store'), $data);

        $response->assertSessionHasErrors(['bobot']);
    }

    public function test_tipe_harus_benefit_atau_cost()
    {
        $data = [
            'nama_kriteria' => 'Test Tipe',
            'tipe' => 'invalid_tipe',
            'skala' => 'numerik',
            'bobot' => 0.1,
            'preference_function' => 'usual',
        ];

        $response = $this->actingAs($this->admin)->post(route('criteria.store'), $data);

        $response->assertSessionHasErrors(['tipe']);
    }

    public function test_preference_function_harus_valid()
    {
        $data = [
            'nama_kriteria' => 'Test Pref Function',
            'tipe' => 'benefit',
            'skala' => 'numerik',
            'bobot' => 0.1,
            'preference_function' => 'invalid_function',
        ];

        $response = $this->actingAs($this->admin)->post(route('criteria.store'), $data);

        $response->assertSessionHasErrors(['preference_function']);
    }

    public function test_criteria_inactive_tidak_ikut_evaluasi()
    {
        Criteria::factory()->create(['is_active' => true, 'bobot' => 0.1]);
        $inactive = Criteria::factory()->create(['is_active' => false, 'bobot' => 0.1]);

        $activeCriteria = Criteria::where('is_active', true)->get();

        $this->assertFalse($activeCriteria->contains($inactive));
    }

    public function test_admin_bisa_melihat_view_edit_criteria()
    {
        $criteria = Criteria::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('criteria.edit', $criteria->id));

        $response->assertOk();
        $response->assertViewIs('admin.criteria.edit');

        $response->assertViewHas('criteria', function ($viewCriteria) use ($criteria) {
            return $viewCriteria->id === $criteria->id;
        });
    }

    public function test_admin_bisa_menghapus_criteria_beserta_ordinalnya()
    {
        $criteria = Criteria::factory()->create([
            'skala' => 'ordinal',
        ]);

        $criteria->ordinals()->create([
            'label' => 'Baik',
            'nilai' => 4,
            'operator' => 'gte',
            'urutan' => 1,
            'range_from' => 70,
            'range_to' => null,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('criteria.destroy', $criteria->id));

        $response->assertRedirect(route('criteria.index'));

        $this->assertDatabaseMissing('criterias', [
            'id' => $criteria->id,
        ]);

        $this->assertDatabaseMissing('criterias_ordinal', [
            'criteria_id' => $criteria->id,
        ]);
    }

    public function test_admin_bisa_update_criteria_ordinal()
    {
        $criteria = Criteria::factory()->create([
            'nama_criteria' => 'Ordinal Lama',
            'skala' => 'ordinal',
            'tipe' => 'benefit',
            'bobot' => 0.3,
            'preference_function' => 'usual',
        ]);

        $criteria->ordinals()->create([
            'label' => 'Lama',
            'nilai' => 1,
            'operator' => 'gte',
            'urutan' => 1,
            'range_from' => 50,
            'range_to' => null,
        ]);

        $data = [
            'nama_kriteria' => 'Ordinal Baru',
            'tipe' => 'benefit',
            'skala' => 'ordinal',
            'bobot' => 0.5,
            'preference_function' => 'usual',
            'ordinal' => [
                [
                    'label' => 'Sangat Baik',
                    'nilai' => 5,
                    'operator' => 'gte',
                    'urutan' => 1,
                    'range_from' => 80,
                    'range_to' => null,
                ],
                [
                    'label' => 'Baik',
                    'nilai' => 4,
                    'operator' => 'between',
                    'urutan' => 2,
                    'range_from' => 60,
                    'range_to' => 80,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('criteria.update', $criteria->id), $data);

        $response->assertRedirect(route('criteria.index'));

        $this->assertDatabaseHas('criterias', [
            'id' => $criteria->id,
            'nama_criteria' => 'Ordinal Baru',
            'skala' => 'ordinal',
            'bobot' => 0.5,
        ]);

        $this->assertDatabaseHas('criterias_ordinal', [
            'criteria_id' => $criteria->id,
            'label' => 'Sangat Baik',
            'nilai' => 5,
        ]);

        $this->assertDatabaseHas('criterias_ordinal', [
            'criteria_id' => $criteria->id,
            'label' => 'Baik',
            'nilai' => 4,
        ]);
    }

    public function test_admin_bisa_update_criteria_numeric()
    {
        $criteria = Criteria::factory()->create([
            'nama_criteria' => 'Kriteria Lama',
            'skala' => 'numerik',
            'tipe' => 'benefit',
            'bobot' => 0.2,
            'preference_function' => 'usual',
        ]);

        $data = [
            'nama_kriteria' => 'Kriteria Baru',
            'tipe' => 'cost',
            'satuan' => 'cm',
            'skala' => 'numerik',
            'bobot' => 0.7,
            'preference_function' => 'usual',
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('criteria.update', $criteria->id), $data);

        $response->assertRedirect(route('criteria.index'));

        $this->assertDatabaseHas('criterias', [
            'id' => $criteria->id,
            'nama_criteria' => 'Kriteria Baru',
            'tipe' => 'cost',
            'skala' => 'numerik',
            'bobot' => 0.7,
        ]);
    }
}
