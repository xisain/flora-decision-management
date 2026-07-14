<?php

namespace Tests\Feature\peneliti;

use App\Models\InspeksiTanaman;
use App\Models\PelaporanPromethee;
use App\Models\Role;
use App\Models\Tanaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelaporanPrometheeTest extends TestCase
{
    use RefreshDatabase;

    protected User $peneliti;

    protected function setUp(): void
    {
        parent::setUp();

        $rolePeneliti = Role::factory()->create(['name' => 'teknisi registrasi']);
        $this->peneliti = User::factory()->create(['roles_id' => $rolePeneliti->id]);
    }

    public function test_peneliti_bisa_membuka_halaman_pelaporan(): void
    {
        $response = $this->actingAs($this->peneliti)->get(route('peneliti.pelaporan.index'));

        $response->assertStatus(200);
        $response->assertViewHas('pelaporan');
    }

    public function test_halaman_pelaporan_menampilkan_data(): void
    {
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create(['tanaman_id' => $tanaman->id]);

        PelaporanPromethee::create([
            'tanaman_id' => $tanaman->id,
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'user_id' => $this->peneliti->id,
            'ranking' => 1,
            'net_flow' => 0.1234,
            'leaving_flow' => 0.5678,
            'entering_flow' => 0.4444,
        ]);

        $response = $this->actingAs($this->peneliti)->get(route('peneliti.pelaporan.index'));

        $response->assertStatus(200);
        $this->assertDatabaseCount('pelaporan_promethee', 1);
    }

    public function test_halaman_pelaporan_bisa_filter_berdasarkan_tanggal(): void
    {
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create(['tanaman_id' => $tanaman->id]);

        PelaporanPromethee::create([
            'tanaman_id' => $tanaman->id,
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'user_id' => $this->peneliti->id,
            'ranking' => 1,
            'net_flow' => 0.1234,
            'leaving_flow' => 0.5678,
            'entering_flow' => 0.4444,
        ]);

        $response = $this->actingAs($this->peneliti)->get(route('peneliti.pelaporan.index', [
            'dari' => now()->toDateString(),
            'sampai' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
    }

    public function test_export_pelaporan_mengembalikan_csv(): void
    {
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create(['tanaman_id' => $tanaman->id]);

        PelaporanPromethee::create([
            'tanaman_id' => $tanaman->id,
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'user_id' => $this->peneliti->id,
            'ranking' => 1,
            'net_flow' => 0.1234,
            'leaving_flow' => 0.5678,
            'entering_flow' => 0.4444,
        ]);

        $response = $this->actingAs($this->peneliti)->get(route('peneliti.pelaporan.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_tamu_tidak_bisa_akses_pelaporan(): void
    {
        $response = $this->get(route('peneliti.pelaporan.index'));

        $response->assertRedirect(route('login'));
    }
}
