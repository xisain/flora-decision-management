<?php

namespace Tests\Feature;

use App\Models\CollectorInfo;
use App\Models\Criteria;
use App\Models\InspeksiTanaman;
use App\Models\Penerimaan;
use App\Models\Tanaman;
use App\Models\TimExplorasi;
use App\Models\User;
use Database\Seeders\ApplicationStateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApplicationStateSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_state_seeder_creates_workflow_data_for_each_state(): void
    {
        DB::table('tanaman_infos')->insert(
            collect(range(1, 12))
                ->map(fn (int $index) => [
                    'scientific_name' => "Seeder Scientific {$index}",
                    'nama_lokal' => "Seeder Lokal {$index}",
                    'marga' => "Marga {$index}",
                    'marga_jenis' => "Marga Jenis {$index}",
                    'suku' => 'Fabaceae',
                    'spesies' => "Spesies {$index}",
                    'author_name' => "Author {$index}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->all()
        );
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'peneliti',
            'description' => 'Peneliti',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user = User::factory()->create(['roles_id' => 3]);
        CollectorInfo::factory()->create(['user_id' => $user->id]);
        TimExplorasi::factory()->create();
        Criteria::factory()->create();

        $this->seed(ApplicationStateSeeder::class);

        $this->assertDatabaseHas('penerimaans', ['source' => 'seed:app-state:penerimaan']);
        $this->assertDatabaseHas('penerimaans', ['source' => 'seed:app-state:penyemaian']);
        $this->assertDatabaseHas('penerimaans', ['source' => 'seed:app-state:checkup']);
        $this->assertDatabaseHas('penerimaans', ['source' => 'seed:app-state:labeling']);
        $this->assertDatabaseHas('penerimaans', ['source' => 'seed:app-state:aklimatisasi']);
        $this->assertDatabaseHas('penerimaans', ['source' => 'seed:app-state:evaluasi_queue']);

        $penerimaanOnlyPlants = $this->plantsForSource('seed:app-state:penerimaan');
        $this->assertNotEmpty($penerimaanOnlyPlants);
        $this->assertSame(
            0,
            $penerimaanOnlyPlants->filter(fn (Tanaman $tanaman) => $tanaman->penyemaianTanaman()->exists())->count()
        );

        $penyemaianPlants = $this->plantsForSource('seed:app-state:penyemaian');
        $this->assertSame(
            $penyemaianPlants->count(),
            $penyemaianPlants->filter(fn (Tanaman $tanaman) => $tanaman->penyemaianTanaman()->exists())->count()
        );
        $this->assertSame(
            0,
            $penyemaianPlants->filter(fn (Tanaman $tanaman) => $tanaman->inspeksiTanaman()->exists())->count()
        );

        $checkupStages = $this->stagesForSource('seed:app-state:checkup');
        $this->assertSame(['checkup'], $checkupStages);

        $labelingStages = $this->stagesForSource('seed:app-state:labeling');
        $this->assertSame(['checkup', 'labeling'], $labelingStages);

        $aklimatisasiStages = $this->stagesForSource('seed:app-state:aklimatisasi');
        $this->assertSame(['checkup', 'labeling'], $aklimatisasiStages);

        $evaluasiQueueStages = $this->stagesForSource('seed:app-state:evaluasi_queue');
        $this->assertSame(['aklimatisasi', 'checkup', 'labeling'], $evaluasiQueueStages);
    }

    /**
     * @return Collection<int, Tanaman>
     */
    private function plantsForSource(string $source)
    {
        $penerimaan = Penerimaan::query()
            ->where('source', $source)
            ->firstOrFail();

        return Tanaman::query()
            ->whereHas('tanamanPenerimaan', fn ($query) => $query->where('penerimaan_id', $penerimaan->id))
            ->get();
    }

    /**
     * @return array<int, string>
     */
    private function stagesForSource(string $source): array
    {
        $plants = $this->plantsForSource($source);

        return InspeksiTanaman::query()
            ->whereIn('tanaman_id', $plants->pluck('id'))
            ->with('inspeksi')
            ->get()
            ->pluck('inspeksi.stage')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
