<?php

namespace Tests\Unit\Admin;

use App\Models\CollectorInfo;
use App\Models\User;
use App\Models\Role;
use App\Services\admin\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService();
    }

    public function test_register_creates_user_with_active_status(): void
    {
        $role = Role::factory()->create([
            'id' => 3,
            'name'=> 'peneliti'
        ]);
        $data = [
            'name'           => 'John Doe',
            'email'          => 'john@example.com',
            'password'       => 'secret123',
            'roles_id'       => $role->id,
            'phone_number'   => '081234567890',
            'account_status' => 'active',
        ];

        $user = $this->service->register($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'email'          => 'john@example.com',
            'account_status' => true,
        ]);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_register_creates_user_with_inactive_status(): void
    {
        $role = Role::factory()->create([
            'id' => 3,
            'name'=> 'peneliti'
        ]);
        $data = [
            'name'           => 'Jane Doe',
            'email'          => 'jane@example.com',
            'password'       => 'secret123',
            'roles_id'       => $role->id,
            'phone_number'   => '081234567891',
            'account_status' => 'inactive',
        ];

        $user = $this->service->register($data);

        $this->assertDatabaseHas('users', [
            'email'          => 'jane@example.com',
            'account_status' => false,
        ]);
    }

    public function test_register_creates_collector_info_when_is_collector_true(): void
    {
        $role = Role::factory()->create([
            'id' => 1,
            'name'=> 'collector'
        ]);
        $data = [
            'name'                   => 'Collector Guy',
            'email'                  => 'collector@example.com',
            'password'               => 'secret123',
            'roles_id'               => $role->id,
            'phone_number'           => '081234567892',
            'account_status'         => 'active',
            'is_collector'           => true,
            'collector_initial_name' => 'CGY',
        ];

        $user = $this->service->register($data);

        $this->assertDatabaseHas('collector_infos', [
            'user_id'                => $user->id,
            'initial_collector_name' => 'CGY',
            'is_manual'              => false,
            'last_Sequence'          => 0,
        ]);
    }

    public function test_register_does_not_create_collector_info_when_is_collector_false(): void
    {
        $role = Role::factory()->create([
            'id' => 1,
            'name'=> 'collector'
        ]);
        $data = [
            'name'           => 'Regular User',
            'email'          => 'regular@example.com',
            'password'       => 'secret123',
            'roles_id'       => $role->id,
            'phone_number'   => '081234567893',
            'account_status' => 'active',
        ];

        $user = $this->service->register($data);

        $this->assertDatabaseMissing('collector_infos', [
            'user_id' => $user->id,
        ]);
    }

    public function test_update_updates_user_fields(): void
    {
        $role = Role::factory()->create([
            'id' => 3,
            'name'=> 'peneliti'
        ]);
        $user = User::factory()->create([
            'name'           => 'Old Name',
            'email'          => 'old@example.com',
            'account_status' => false,
        ]);

        $validated = [
            'name'           => 'New Name',
            'email'          => 'new@example.com',
            'phone_number'   => '089999999999',
            'roles_id'       => $role->id,
            'account_status' => 'active',
        ];

        $this->service->update($validated, $user);

        $this->assertDatabaseHas('users', [
            'id'             => $user->id,
            'name'           => 'New Name',
            'email'          => 'new@example.com',
            'account_status' => true,
        ]);
    }

    public function test_update_creates_collector_info_when_is_collector_set(): void
    {

        $role = Role::factory()->create([
            'id' => 1,
            'name'=> 'collector'
        ]);
        $user = User::factory()->create();

        $validated = [
            'name'                    => $user->name,
            'email'                   => $user->email,
            'phone_number'            => '081111111111',
            'roles_id'                => $role->id,
            'account_status'          => 'active',
            'is_collector'            => true,
            'collector_display_name'  => 'My Collector',
            'collector_initial_name'  => 'MC',
            'is_manual'               => false,
        ];

        $this->service->update($validated, $user);

        $this->assertDatabaseHas('collector_infos', [
            'user_id'                => $user->id,
            'initial_collector_name' => 'MC',
        ]);
    }

    public function test_update_deletes_collector_info_when_is_collector_not_set(): void
    {
        $user = User::factory()->create();
        CollectorInfo::create([
            'user_id'                => $user->id,
            'full_name'              => $user->name,
            'initial_collector_name' => 'OLD',
            'is_manual'              => false,
            'last_Sequence'          => 0,
        ]);

        $validated = [
            'name'           => $user->name,
            'email'          => $user->email,
            'phone_number'   => null,
            'roles_id'       => 1,
            'account_status' => 'active',
        ];

        $this->service->update($validated, $user);

        $this->assertDatabaseMissing('collector_infos', [
            'user_id' => $user->id,
        ]);
    }
}
