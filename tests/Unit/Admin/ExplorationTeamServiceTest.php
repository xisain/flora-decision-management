<?php

namespace Tests\Unit\Admin;

use App\Models\CollectorInfo;
use App\Models\TimExplorasi;
use App\Models\User;
use App\Services\admin\explorationTeam\ExplorationTeamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExplorationTeamServiceTest extends TestCase
{
    use RefreshDatabase;

    private ExplorationTeamService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ExplorationTeamService();
    }

    private function makeCollector(string $initial = 'TC'): CollectorInfo
    {
        $user = User::factory()->create();

        return CollectorInfo::create([
            'user_id'                => $user->id,
            'full_name'              => 'Test Collector',
            'initial_collector_name' => $initial,
            'is_manual'              => false,
            'last_sequence'          => 0,
        ]);
    }

    public function test_store_creates_exploration_team_with_members(): void
    {
        $collector = $this->makeCollector();

        $data = [
            'nama_tim'             => 'Tim Flora Bogor',
            'deskripsi_explorasi'  => 'Eksplorasi tanaman Bogor',
            'lokasi_explorasi'     => 'Bogor',
            'anggota'              => [
                [
                    'collector_id' => $collector->id,
                    'peran'        => 'Ketua',
                ],
            ],
        ];

        $this->service->store($data);

        $this->assertDatabaseHas('exploration_team', [
            'nama_tim'          => 'Tim Flora Bogor',
            'deskripsi_team'    => 'Eksplorasi tanaman Bogor',
            'lokasi_explorasi'  => 'Bogor',
        ]);

        $team = TimExplorasi::where('nama_tim', 'Tim Flora Bogor')->first();

        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $team->id,
            'collector_id'        => $collector->id,
            'Peran'               => 'Ketua',
        ]);
    }

    public function test_update_replaces_exploration_team_members(): void
    {
        $oldCollector = $this->makeCollector('OLD');
        $newCollector = $this->makeCollector('NEW');

        $team = TimExplorasi::create([
            'nama_tim'          => 'Old Team',
            'deskripsi_team'    => 'Old Description',
            'lokasi_explorasi'  => 'Old Location',
        ]);

        $team->AnggotaTimExplorasi()->create([
            'collector_id' => $oldCollector->id,
            'Peran'       => 'Anggota',
        ]);

        $data = [
            'nama_tim'             => 'Updated Team',
            'deskripsi_explorasi'  => 'Updated Description',
            'lokasi_explorasi'     => 'Updated Location',
            'anggota'              => [
                [
                    'collector_id' => $newCollector->id,
                    'peran'        => 'Ketua',
                ],
            ],
        ];

        $this->service->update($data, $team);

        $this->assertDatabaseHas('exploration_team', [
            'id'                => $team->id,
            'nama_tim'          => 'Updated Team',
            'deskripsi_team'    => 'Updated Description',
            'lokasi_explorasi'  => 'Updated Location',
        ]);

        $this->assertDatabaseMissing('exploration_team_member', [
            'exploration_team_id' => $team->id,
            'collector_id'        => $oldCollector->id,
        ]);

        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $team->id,
            'collector_id'        => $newCollector->id,
            'Peran'               => 'Ketua',
        ]);
    }
}
