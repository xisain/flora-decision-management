<?php

namespace Tests\Feature;

use Database\Seeders\TanamanInfoSainSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TanamanInfoSainSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_husain_seeder_inserts_expected_tanaman_info_rows(): void
    {
        $this->seed(TanamanInfoSainSeeder::class);

        $this->assertDatabaseHas('tanaman_infos', [
            'scientific_name' => 'Abarema clypearia (Jack) Kosterm.',
            'marga' => 'Archidendron',
            'marga_jenis' => 'Archidendron clypearia',
            'suku' => 'Fabaceae',
            'spesies' => 'Archidendron clypearia',
            'author_name' => '(Jack) Kosterm.',
            'redlist_category' => 'Melimpah',
            'endemisitas' => 'Tidak endemik',
        ]);

        $this->assertDatabaseHas('tanaman_infos', [
            'scientific_name' => 'Abarema tjendana (Kosterm.) Kosterm.',
            'redlist_category' => 'Terancam',
            'endemisitas' => 'Tidak endemik',
        ]);

        $this->assertDatabaseHas('tanaman_infos', [
            'scientific_name' => 'Brownlowia ferruginea Kosterm.',
            'redlist_category' => 'Terancam',
            'endemisitas' => 'Endemik di Bulungan',
        ]);

        $this->assertSame(33720, DB::table('tanaman_infos')->count());

        $this->assertSame(
            ['Hampir Terancam', 'Kritis', 'Melimpah', 'Rentan', 'Terancam'],
            DB::table('tanaman_infos')
                ->distinct()
                ->orderBy('redlist_category')
                ->pluck('redlist_category')
                ->all()
        );

        $this->assertSame(
            ['Endemik di Bulungan', 'Endemik di Indonesia', 'Tidak endemik'],
            DB::table('tanaman_infos')
                ->distinct()
                ->orderBy('endemisitas')
                ->pluck('endemisitas')
                ->all()
        );
    }
}
