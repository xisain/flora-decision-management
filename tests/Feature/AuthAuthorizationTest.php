<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'teknisi registrasi']);
    }

    public function test_guest_tidak_bisa_akses_admin_dashboard()
    {
        $response = $this->get(route('admin.home'));
        $response->assertRedirect('/login');
    }

    public function test_guest_tidak_bisa_akses_peneliti_dashboard()
    {
        $response = $this->get(route('peneliti.registrasi.home'));
        $response->assertRedirect('/login');
    }

    public function test_admin_bisa_akses_admin_dashboard()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.home'));
        $response->assertStatus(200);
        $response->assertViewHasAll([
            'userCount',
            'collectorCount',
            'criteriaCount',
            'activeCriteriaCount',
            'inactiveCriteriaCount',
            'timExplorasiCount',
            'tanamanCount',
            'penerimaanCount',
            'eksplorasiCount',
            'introduksiCount',
            'recentActivities',
        ]);
    }

    public function test_peneliti_registrasi_bisa_akses_peneliti_dashboard()
    {
        $peneliti = User::factory()->teknisiRegistrasi()->create();

        $response = $this->actingAs($peneliti)->get(route('peneliti.registrasi.home'));
        $response->assertStatus(200);
        $response->assertViewHasAll([
            'dataPenerimaaanCount',
            'dataPenerimaanTanamanCount',
            'tanamanCount',
            'penyemaianTanamanCount',
            'checkupCount',
            'labelingCount',
            'aklimatisasiCount',
            'evaluasiCount']);
    }
    public function test_peneliti_pembibitan_bisa_akses_peneliti_registrasi_dashboard(){
        $peneliti = User::factory()->teknisiPembibitan()->create();
        $response = $this->actingAs($peneliti)->get(route('peneliti.registrasi.home'));
        $response->assertStatus(403);
    }
    public function test_peneliti_pembibitan_bisa_akses_peneliti_pembibitan_dashboard(){
        $peneliti = User::factory()->teknisiPembibitan()->create();
        $response = $this->actingAs($peneliti)->get(route('peneliti.pembibitan.home'));
        $response->assertStatus(200);
        $response->assertViewHasAll([
            'dataPenerimaaanCount',
            'dataPenerimaanTanamanCount',
            'tanamanCount',
            'penyemaianTanamanCount',
            'checkupCount',
            'labelingCount',
            'aklimatisasiCount',
            'evaluasiCount']);
    }

    public function test_peneliti_tidak_bisa_akses_halaman_admin()
    {
        $peneliti = User::factory()->teknisiRegistrasi()->create();

        $response = $this->actingAs($peneliti)->get(route('admin.home'));
        $response->assertStatus(403);
    }

    public function test_admin_tidak_bisa_akses_halaman_peneliti_jika_dibatasi()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('peneliti.registrasi.home'));
        $response->assertStatus(403);
    }
}
