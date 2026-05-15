<?php

namespace Tests\Feature;

use App\Models\CollectorInfo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $roleAdmin;
    protected $rolePeneliti;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleAdmin = Role::factory()->create(['name' => 'admin']);
        $this->rolePeneliti = Role::factory()->create(['name' => 'peneliti']);

        $this->admin = User::factory()->create(['roles_id' => $this->roleAdmin->id]);
    }

    public function test_admin_bisa_melihat_daftar_user()
    {
        User::factory()->count(3)->create(['roles_id' => $this->rolePeneliti->id]);

        $response = $this->actingAs($this->admin)->get(route('user.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_admin_bisa_membuat_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles_id' => $this->rolePeneliti->id,
            'phone_number' => '08123456789',
            'account_status' => 'active'
        ];

        $response = $this->actingAs($this->admin)->post(route('user.store'), $userData);

        $response->assertRedirect(route('user.index'));
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function test_admin_bisa_update_user()
    {
        $user = User::factory()->create(['roles_id' => $this->rolePeneliti->id]);

        $updateData = [
            'name' => 'Updated User Name',
            'email' => $user->email,
            'roles_id' => $this->roleAdmin->id,
            'phone_number' => '08987654321',
            'account_status' => 'active'
        ];

        $response = $this->actingAs($this->admin)->put(route('user.update', $user->id), $updateData);

        $response->assertRedirect(route('user.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User Name',
            'roles_id' => $this->roleAdmin->id
        ]);
    }

    public function test_email_user_harus_unique()
    {
        $existingUser = User::factory()->create();

        $userData = [
            'name' => 'Test User',
            'email' => $existingUser->email, // Duplicate email
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles_id' => $this->rolePeneliti->id,
            'account_status' => 'active'
        ];

        $response = $this->actingAs($this->admin)->post(route('user.store'), $userData);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_role_wajib_valid()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'testuser2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles_id' => 999, // Invalid role ID
            'account_status' => 'active'
        ];

        $response = $this->actingAs($this->admin)->post(route('user.store'), $userData);

        $response->assertSessionHasErrors(['roles_id']);
    }

    public function test_user_collector_membuat_collector_info()
    {
        $userData = [
            'name' => 'Collector User',
            'email' => 'collector@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles_id' => $this->rolePeneliti->id,
            'phone_number' => '08123456789',
            'account_status' => 'active',
            'is_collector' => true,
            'collector_initial_name' => 'COL'
        ];

        $response = $this->actingAs($this->admin)->post(route('user.store'), $userData);

        $response->assertRedirect(route('user.index'));

        $user = User::where('email', 'collector@example.com')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('collector_infos', [
            'user_id' => $user->id,
            'initial_collector_name' => 'COL'
        ]);
    }

    public function test_user_non_collector_menghapus_collector_info()
    {
        $user = User::factory()->create(['roles_id' => $this->rolePeneliti->id]);
        CollectorInfo::factory()->create([
            'user_id' => $user->id,
            'initial_collector_name' => 'OLD'
        ]);

        $updateData = [
            'name' => $user->name,
            'email' => $user->email,
            'roles_id' => $this->rolePeneliti->id,
            'phone_number' => $user->phone_number,
            'account_status' => 'active',
            // is_collector is omitted / null
        ];

        $response = $this->actingAs($this->admin)->put(route('user.update', $user->id), $updateData);

        $response->assertRedirect(route('user.index'));

        $this->assertDatabaseMissing('collector_infos', [
            'user_id' => $user->id
        ]);
    }
}
