<?php

namespace Tests\Unit\Admin;

use App\Http\Requests\admin\CriteriaCreateRequest;
use App\Http\Requests\admin\CriteriaUpdateRequest;
use App\Models\Criteria;
use App\Services\admin\CriteriaCreateService;
use App\Services\admin\CriteriaUpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriteriaServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_service_creates_numeric_criteria(): void
    {
        $request = CriteriaCreateRequest::create('/', 'POST', [
            'nama_kriteria'       => 'Tinggi Tanaman',
            'tipe'                => 'benefit',
            'satuan'              => 'cm',
            'skala'               => 'numerik',
            'bobot'               => 0.5,
            'preference_function' => 'usual',
            'param_q'             => null,
            'param_p'             => null,
            'param_sigma'         => null,
        ]);

        $criteria = (new CriteriaCreateService())->store($request);

        $this->assertDatabaseHas('criterias', [
            'id'                  => $criteria->id,
            'nama_criteria'       => 'Tinggi Tanaman',
            'tipe'                => 'benefit',
            'satuan'              => 'cm',
            'skala'               => 'numerik',
            'preference_function' => 'usual',
        ]);
    }

    public function test_create_service_creates_ordinal_criteria_with_ordinals(): void
    {
        $request = CriteriaCreateRequest::create('/', 'POST', [
            'nama_kriteria'       => 'Kesehatan',
            'tipe'                => 'benefit',
            'satuan'              => null,
            'skala'               => 'ordinal',
            'bobot'               => 1,
            'preference_function' => 'usual',
            'ordinal'             => [
                [
                    'label'      => 'Buruk',
                    'nilai'      => 1,
                    'operator'   => 'eq',
                    'range_from' => null,
                    'range_to'   => null,
                    'urutan'     => 1,
                ],
                [
                    'label'      => 'Baik',
                    'nilai'      => 2,
                    'operator'   => 'eq',
                    'range_from' => null,
                    'range_to'   => null,
                    'urutan'     => 2,
                ],
            ],
        ]);

        $criteria = (new CriteriaCreateService())->store($request);

        $this->assertDatabaseHas('criterias', [
            'id'            => $criteria->id,
            'nama_criteria' => 'Kesehatan',
            'skala'         => 'ordinal',
        ]);

        $this->assertDatabaseHas('criterias_ordinal', [
            'criteria_id' => $criteria->id,
            'label'       => 'Buruk',
            'nilai'       => 1,
        ]);

        $this->assertDatabaseHas('criterias_ordinal', [
            'criteria_id' => $criteria->id,
            'label'       => 'Baik',
            'nilai'       => 2,
        ]);
    }

    public function test_update_service_updates_criteria_and_replaces_ordinals(): void
    {
        $criteria = Criteria::create([
            'nama_criteria'       => 'Old Criteria',
            'tipe'                => 'benefit',
            'satuan'              => null,
            'skala'               => 'ordinal',
            'bobot'               => 1,
            'preference_function' => 'usual',
            'is_active'           => true,
        ]);

        $oldOrdinal = $criteria->ordinals()->create([
            'label'      => 'Old Label',
            'nilai'      => 1,
            'operator'   => 'eq',
            'range_from' => null,
            'range_to'   => null,
            'urutan'     => 1,
        ]);

        $removedOrdinal = $criteria->ordinals()->create([
            'label'      => 'Removed Label',
            'nilai'      => 2,
            'operator'   => 'eq',
            'range_from' => null,
            'range_to'   => null,
            'urutan'     => 2,
        ]);

        $request = CriteriaUpdateRequest::create('/', 'PUT', [
            'nama_kriteria'       => 'Updated Criteria',
            'tipe'                => 'benefit',
            'satuan'              => null,
            'skala'               => 'ordinal',
            'bobot'               => 0.8,
            'preference_function' => 'usual',
            'ordinal'             => [
                [
                    'id'         => $oldOrdinal->id,
                    'label'      => 'Updated Label',
                    'nilai'      => 10,
                    'operator'   => 'eq',
                    'range_from' => null,
                    'range_to'   => null,
                ],
                [
                    'label'      => 'New Label',
                    'nilai'      => 20,
                    'operator'   => 'eq',
                    'range_from' => null,
                    'range_to'   => null,
                ],
            ],
        ]);

        (new CriteriaUpdateService())->update($request, $criteria);

        $this->assertDatabaseHas('criterias', [
            'id'            => $criteria->id,
            'nama_criteria' => 'Updated Criteria',
            'bobot'         => 0.8,
        ]);

        $this->assertDatabaseHas('criterias_ordinal', [
            'id'    => $oldOrdinal->id,
            'label' => 'Updated Label',
            'nilai' => 10,
        ]);

        $this->assertDatabaseHas('criterias_ordinal', [
            'criteria_id' => $criteria->id,
            'label'       => 'New Label',
            'nilai'       => 20,
        ]);

        $this->assertDatabaseMissing('criterias_ordinal', [
            'id' => $removedOrdinal->id,
        ]);
    }

    public function test_update_service_deletes_ordinals_when_skala_becomes_numeric(): void
    {
        $criteria = Criteria::create([
            'nama_criteria'       => 'Old Criteria',
            'tipe'                => 'benefit',
            'satuan'              => null,
            'skala'               => 'ordinal',
            'bobot'               => 1,
            'preference_function' => 'usual',
            'is_active'           => true,
        ]);

        $ordinal = $criteria->ordinals()->create([
            'label'      => 'Old Label',
            'nilai'      => 1,
            'operator'   => 'eq',
            'range_from' => null,
            'range_to'   => null,
            'urutan'     => 1,
        ]);

        $request = CriteriaUpdateRequest::create('/', 'PUT', [
            'nama_kriteria'       => 'Numeric Criteria',
            'tipe'                => 'benefit',
            'satuan'              => 'cm',
            'skala'               => 'numerik',
            'bobot'               => 1,
            'preference_function' => 'usual',
        ]);

        (new CriteriaUpdateService())->update($request, $criteria);

        $this->assertDatabaseHas('criterias', [
            'id'            => $criteria->id,
            'nama_criteria' => 'Numeric Criteria',
            'skala'         => 'numerik',
        ]);

        $this->assertDatabaseMissing('criterias_ordinal', [
            'id' => $ordinal->id,
        ]);
    }
}
