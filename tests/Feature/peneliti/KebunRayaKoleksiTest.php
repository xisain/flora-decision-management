<?php

namespace Tests\Feature\peneliti;

use App\Models\InspeksiTanaman;
use App\Models\KebunRayaKoleksi;
use App\Models\Role;
use App\Models\Tanaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KebunRayaKoleksiTest extends TestCase
{
    use RefreshDatabase;

    protected User $peneliti;

    protected function setUp(): void
    {
        parent::setUp();

        $rolePeneliti = Role::factory()->create(['name' => 'teknisi registrasi']);
        $this->peneliti = User::factory()->create(['roles_id' => $rolePeneliti->id]);
    }

    public function test_peneliti_bisa_membuka_halaman_koleksi(): void
    {
        $response = $this->actingAs($this->peneliti)->get(route('peneliti.koleksi.index'));

        $response->assertStatus(200);
        $response->assertViewHas('koleksi');
    }

    public function test_peneliti_bisa_menambah_tanaman_ke_koleksi(): void
    {
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create(['tanaman_id' => $tanaman->id]);

        $payload = [
            'selected' => [
                [
                    'tanaman_id' => $tanaman->id,
                    'inspeksi_tanaman_id' => $inspeksiTanaman->id,
                    'ranking' => 1,
                    'net_flow' => 0.1234,
                    'leaving_flow' => 0.5678,
                    'entering_flow' => 0.4444,
                ],
            ],
        ];

        $response = $this->actingAs($this->peneliti)->post(route('peneliti.koleksi.store'), $payload);

        $response->assertRedirect(route('peneliti.koleksi.index'));

        // Data koleksi (tanpa flow) masuk ke tabel kebun_raya_koleksi
        $this->assertDatabaseHas('kebun_raya_koleksi', [
            'tanaman_id' => $tanaman->id,
            'user_id' => $this->peneliti->id,
        ]);

        // Data flow masuk ke tabel pelaporan_promethee
        $this->assertDatabaseHas('pelaporan_promethee', [
            'tanaman_id' => $tanaman->id,
            'inspeksi_tanaman_id' => $inspeksiTanaman->id,
            'ranking' => 1,
            'user_id' => $this->peneliti->id,
        ]);
    }

    public function test_tabel_koleksi_tidak_menyimpan_data_flow(): void
    {
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create(['tanaman_id' => $tanaman->id]);

        $payload = [
            'selected' => [
                [
                    'tanaman_id' => $tanaman->id,
                    'inspeksi_tanaman_id' => $inspeksiTanaman->id,
                    'ranking' => 1,
                    'net_flow' => 0.1234,
                    'leaving_flow' => 0.5678,
                    'entering_flow' => 0.4444,
                ],
            ],
        ];

        $this->actingAs($this->peneliti)->post(route('peneliti.koleksi.store'), $payload);

        // Pastikan kolom flow TIDAK ada di koleksi
        $koleksi = KebunRayaKoleksi::where('tanaman_id', $tanaman->id)->first();
        $this->assertNotNull($koleksi);
        $this->assertFalse(isset($koleksi->ranking));
        $this->assertFalse(isset($koleksi->net_flow));
        $this->assertFalse(isset($koleksi->leaving_flow));
        $this->assertFalse(isset($koleksi->entering_flow));
    }

    public function test_tanaman_yang_sudah_ada_tidak_ditambah_lagi_ke_koleksi(): void
    {
        $tanaman = Tanaman::factory()->create();
        $inspeksiTanaman = InspeksiTanaman::factory()->create(['tanaman_id' => $tanaman->id]);

        KebunRayaKoleksi::create([
            'tanaman_id' => $tanaman->id,
            'user_id' => $this->peneliti->id,
        ]);

        $payload = [
            'selected' => [
                [
                    'tanaman_id' => $tanaman->id,
                    'inspeksi_tanaman_id' => $inspeksiTanaman->id,
                    'ranking' => 1,
                    'net_flow' => 0.1234,
                    'leaving_flow' => 0.5678,
                    'entering_flow' => 0.4444,
                ],
            ],
        ];

        $this->actingAs($this->peneliti)->post(route('peneliti.koleksi.store'), $payload);

        $this->assertDatabaseCount('kebun_raya_koleksi', 1);

        // Tapi data flow tetap tersimpan di pelaporan_promethee
        $this->assertDatabaseCount('pelaporan_promethee', 1);
    }

    public function test_store_gagal_jika_tidak_ada_tanaman_dipilih(): void
    {
        $response = $this->actingAs($this->peneliti)->post(route('peneliti.koleksi.store'), [
            'selected' => [],
        ]);

        $response->assertSessionHasErrors('selected');
    }

    public function test_peneliti_bisa_menghapus_koleksi(): void
    {
        $tanaman = Tanaman::factory()->create();

        $koleksi = KebunRayaKoleksi::create([
            'tanaman_id' => $tanaman->id,
            'user_id' => $this->peneliti->id,
        ]);

        $response = $this->actingAs($this->peneliti)
            ->delete(route('peneliti.koleksi.destroy', $koleksi->id));

        $response->assertRedirect(route('peneliti.koleksi.index'));
        $this->assertDatabaseMissing('kebun_raya_koleksi', ['id' => $koleksi->id]);
    }

    public function test_export_mengembalikan_csv(): void
    {
        $tanaman = Tanaman::factory()->create();

        KebunRayaKoleksi::create([
            'tanaman_id' => $tanaman->id,
            'user_id' => $this->peneliti->id,
        ]);

        $response = $this->actingAs($this->peneliti)->get(route('peneliti.koleksi.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_tamu_tidak_bisa_akses_koleksi(): void
    {
        $response = $this->get(route('peneliti.koleksi.index'));

        $response->assertRedirect(route('login'));
    }
}
