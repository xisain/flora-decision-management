<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'peneliti']);
    }

    public function test_guest_tidak_bisa_akses_admin_dashboard()
    {
        $response = $this->get(route('admin.home'));
        $response->assertRedirect('/login');
    }

    public function test_guest_tidak_bisa_akses_peneliti_dashboard()
    {
        $response = $this->get(route('peneliti.home'));
        $response->assertRedirect('/login');
    }

    public function test_admin_bisa_akses_admin_dashboard()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.home'));
        $response->assertStatus(200);
    }

    public function test_peneliti_bisa_akses_peneliti_dashboard()
    {
        $peneliti = User::factory()->peneliti()->create();

        $response = $this->actingAs($peneliti)->get(route('peneliti.home'));
        $response->assertStatus(200);
    }

    public function test_peneliti_tidak_bisa_akses_halaman_admin()
    {
        $peneliti = User::factory()->peneliti()->create();

        $response = $this->actingAs($peneliti)->get(route('admin.home'));
        $response->assertStatus(403);
    }

    public function test_admin_tidak_bisa_akses_halaman_peneliti_jika_dibatasi()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('peneliti.home'));
        $response->assertStatus(403);
    }
}
