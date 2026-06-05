<?php

namespace Tests\Feature;

use Database\Seeders\CriteriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CriteriaSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_criteria_seeder_uses_normalized_endemic_and_redlist_ordinals(): void
    {
        $this->seed(CriteriaSeeder::class);

        $endemikId = DB::table('criterias')
            ->where('nama_criteria', 'Endemisitas')
            ->value('id');
        dump($endemikId);

        $this->assertSame(
            ['Tidak endemik' => 1, 'Endemik di Indonesia' => 2, 'Endemik di Bulungan' => 3],
            DB::table('criterias_ordinal')
                ->where('criteria_id', $endemikId)
                ->orderBy('nilai')
                ->pluck('nilai', 'label')
                ->all()
        );

        $redlistId = DB::table('criterias')
            ->where('nama_criteria', 'Status Konservasi (Endanger)')
            ->value('id');

        $this->assertSame(
            ['Melimpah' => 1, 'Hampir Terancam' => 2, 'Rentan' => 3, 'Terancam' => 4, 'Kritis' => 5],
            DB::table('criterias_ordinal')
                ->where('criteria_id', $redlistId)
                ->orderBy('nilai')
                ->pluck('nilai', 'label')
                ->all()
        );
    }
}
