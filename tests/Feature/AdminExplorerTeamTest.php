<?php

namespace Tests\Feature;

use App\Models\CollectorInfo;
use App\Models\Role;
use App\Models\TimExplorasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminExplorerTeamTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $roleAdmin = Role::factory()->create(['name' => 'admin']);
        $this->admin = User::factory()->create(['roles_id' => $roleAdmin->id]);

    }

    public function test_admin_bisa_melihat_daftar_tim_explorasi()
    {
        TimExplorasi::factory()->count(3)->create();
        $response = $this->actingAs($this->admin)->get(route('tim-explorasi.index'));
        $response->assertStatus(200);
        $response->assertViewHas('team');
    }

    public function test_admin_bisa_filter_daftar_tim_explorasi()
    {
        TimExplorasi::factory()->create([
            'nama_tim' => 'Tim Anggrek Papua',
            'lokasi_explorasi' => 'Papua',
        ]);

        TimExplorasi::factory()->create([
            'nama_tim' => 'Tim Rafflesia',
            'lokasi_explorasi' => 'Bengkulu',
        ]);

        TimExplorasi::factory()->create([
            'nama_tim' => 'Tim Edelweis',
            'lokasi_explorasi' => 'Jawa Barat',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('tim-explorasi.index', [
                'search' => 'Papua',
            ]));

        $response->assertStatus(200);

        $response->assertSee('Tim Anggrek Papua');
        $response->assertDontSee('Tim Rafflesia');
        $response->assertDontSee('Tim Edelweis');
    }

    public function test_admin_bisa_melihat_halaman_create_tim()
    {
        CollectorInfo::factory()->count(10)->create();
        $response = $this->actingAs($this->admin)->get(route('tim-explorasi.create'));
        $response->assertStatus(200);
        $response->assertViewHas('collector');

    }

    public function test_admin_bisa_membuat_tim_explorasi()
    {
        $anggota1 = CollectorInfo::factory()->create();
        $anggota2 = CollectorInfo::factory()->create();
        $anggota3 = CollectorInfo::factory()->create();

        $data = [
            'nama_tim' => 'Explorasi Bulungan',
            'lokasi_explorasi' => 'Di Gunung Salak',
            'deskripsi_explorasi' => 'explorasi disini',
            'anggota' => [
                [
                    'collector_id' => $anggota1->id,
                    'peran' => 'ketua',
                ],
                [
                    'collector_id' => $anggota2->id,
                    'peran' => 'anggota',
                ],
                [
                    'collector_id' => $anggota3->id,
                    'peran' => 'anggota',
                ],
            ],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post(route('tim-explorasi.store'), $data);

        $response->assertRedirect(route('tim-explorasi.index'));

        $this->assertDatabaseHas('exploration_team', [
            'nama_tim' => 'Explorasi Bulungan',
            'lokasi_explorasi' => 'Di Gunung Salak',
            'deskripsi_team' => 'explorasi disini',
        ]);

        $tim = TimExplorasi::where('nama_tim', 'Explorasi Bulungan')->first();

        $this->assertNotNull($tim);

        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggota1->id,
            'Peran' => 'ketua',
        ]);

        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggota2->id,
            'Peran' => 'anggota',
        ]);

        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggota3->id,
            'Peran' => 'anggota',
        ]);
    }

    public function test_admin_bisa_melihat_halaman_edit_tim_explorasi()
    {
        $anggota1 = CollectorInfo::factory()->create();
        $anggota2 = CollectorInfo::factory()->create();
        $anggota3 = CollectorInfo::factory()->create();

        $tim = TimExplorasi::factory()->create([
            'nama_tim' => 'Tim Eksplorasi Bulungan',
            'lokasi_explorasi' => 'Gunung Salak',
            'deskripsi_team' => 'Tim untuk eksplorasi tanaman',
        ]);

        $tim->AnggotaTimExplorasi()->createMany([
            [
                'collector_id' => $anggota1->id,
                'Peran' => 'ketua',
            ],
            [
                'collector_id' => $anggota2->id,
                'Peran' => 'anggota',
            ],
            [
                'collector_id' => $anggota3->id,
                'Peran' => 'anggota',
            ],
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('tim-explorasi.edit', $tim->id));

        $response->assertStatus(200);

        $response->assertViewIs('admin.timexplorasi.edit');

        $response->assertViewHas('team', function ($viewTeam) use ($tim) {
            return $viewTeam->id === $tim->id
                && $viewTeam->relationLoaded('AnggotaTimExplorasi');
        });

        $response->assertViewHas('members', function ($members) {
            return $members->count() === 3;
        });

        $response->assertViewHas('collector', function ($collector) {
            return $collector->count() >= 3;
        });
        $response->assertViewHas('members', function ($members) use ($anggota1, $anggota2, $anggota3) {
            return $members->contains('collector_id', $anggota1->id)
                && $members->contains('collector_id', $anggota2->id)
                && $members->contains('collector_id', $anggota3->id);
        });

        $response->assertSee('Tim Eksplorasi Bulungan');
        $response->assertSee('Gunung Salak');
        $response->assertSee('Tim untuk eksplorasi tanaman');
    }

    public function test_admin_bisa_update_tim_explorasi()
    {
        // $this->withoutExceptionHandling();
        $anggotaLama = CollectorInfo::factory()->create();

        $anggotaBaru1 = CollectorInfo::factory()->create();
        $anggotaBaru2 = CollectorInfo::factory()->create();
        $anggotaBaru3 = CollectorInfo::factory()->create();

        $tim = TimExplorasi::factory()->create([
            'nama_tim' => 'Tim Lama',
            'lokasi_explorasi' => 'Lokasi Lama',
            'deskripsi_team' => 'Deskripsi lama',
        ]);

        $tim->AnggotaTimExplorasi()->create([
            'collector_id' => $anggotaLama->id,
            'Peran' => 'ketua',
        ]);

        $data = [
            'nama_tim' => 'Tim Baru',
            'lokasi_explorasi' => 'Lokasi Baru',
            'deskripsi_explorasi' => 'Deskripsi baru',
            'anggota' => [
                [
                    'collector_id' => $anggotaBaru1->id,
                    'peran' => 'ketua',
                ],
                [
                    'collector_id' => $anggotaBaru2->id,
                    'peran' => 'anggota',
                ],
                [
                    'collector_id' => $anggotaBaru3->id,
                    'peran' => 'anggota',
                ],
            ],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->put(route('tim-explorasi.update', $tim->id), $data);

        $response->assertRedirect(route('tim-explorasi.index'));

        $this->assertDatabaseHas('exploration_team', [
            'id' => $tim->id,
            'nama_tim' => 'Tim Baru',
            'lokasi_explorasi' => 'Lokasi Baru',
            'deskripsi_team' => 'Deskripsi baru',
        ]);

        $this->assertDatabaseMissing('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggotaLama->id,
            'Peran' => 'ketua',
        ]);

        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggotaBaru1->id,
            'Peran' => 'ketua',
        ]);

        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggotaBaru2->id,
            'Peran' => 'anggota',
        ]);
        $this->assertDatabaseHas('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggotaBaru3->id,
            'Peran' => 'anggota',
        ]);
    }

    public function test_admin_bisa_hapus_tim_explorasi()
    {
        $anggota1 = CollectorInfo::factory()->create();
        $anggota2 = CollectorInfo::factory()->create();

        $tim = TimExplorasi::factory()->create([
            'nama_tim' => 'Tim Yang Akan Dihapus',
            'lokasi_explorasi' => 'Gunung Salak',
            'deskripsi_team' => 'Tim sementara',
        ]);

        $tim->AnggotaTimExplorasi()->createMany([
            [
                'collector_id' => $anggota1->id,
                'Peran' => 'ketua',
            ],
            [
                'collector_id' => $anggota2->id,
                'Peran' => 'anggota',
            ],
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('tim-explorasi.destroy', $tim->id));

        $response->assertRedirect(route('tim-explorasi.index'));

        $this->assertDatabaseMissing('exploration_team', [
            'id' => $tim->id,
        ]);

        $this->assertDatabaseMissing('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggota1->id,
        ]);

        $this->assertDatabaseMissing('exploration_team_member', [
            'exploration_team_id' => $tim->id,
            'collector_id' => $anggota2->id,
        ]);
    }
}
