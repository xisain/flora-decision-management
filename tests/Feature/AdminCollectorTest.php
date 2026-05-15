<?php

namespace Tests\Feature;

use App\Models\CollectorInfo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCollectorTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::factory()->create(['name' => 'admin']);
        $this->admin = User::factory()->create(['roles_id' => $roleAdmin->id]);
    }

    public function test_admin_bisa_melihat_daftar_collector()
    {
        CollectorInfo::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('collector.index'));

        $response->assertStatus(200);
        $response->assertViewHas('collector');
    }

    public function test_admin_bisa_melihat_view_buat_collector()
    {
        $response = $this->actingAs($this->admin)->get(route('collector.create'));
        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_admin_bisa_melihat_detail_collector()
    {
        $info = CollectorInfo::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('collector.show', $info->id));

        $response->assertStatus(200);
        $response->assertViewHas('find');
    }

    public function test_admin_bisa_melihat_edit_collector()
    {
        $collector = CollectorInfo::factory()
            ->for(User::factory())
            ->create();

        User::factory()->count(10)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('collector.edit', $collector->id));

        $response->assertOk();

        $response->assertViewIs('admin.collector.edit');

        $response->assertViewHas('collector');
        $response->assertViewHas('userTanpaCollector');
    }

    public function test_admin_bisa_membuat_collector_manual()
    {
        $data = [
            'full_name' => 'Manual Collector',
            'initial_collector_name' => 'MNC',
            'is_manual' => 1,
            'last_sequence' => 0,
            'user_id' => null,
        ];

        $response = $this->actingAs($this->admin)->post(route('collector.store'), $data);

        $response->assertRedirect(route('collector.index'));
        $this->assertDatabaseHas('collector_infos', [
            'initial_collector_name' => 'MNC',
            'is_manual' => 1,
        ]);
    }

    public function test_initial_collector_name_wajib_unique()
    {
        CollectorInfo::factory()->create(['initial_collector_name' => 'UNI']);

        $data = [
            'full_name' => 'Another Collector',
            'initial_collector_name' => 'UNI',
            'is_manual' => 1,
            'last_sequence' => 0,
            'user_id' => null,
        ];

        $response = $this->actingAs($this->admin)->post(route('collector.store'), $data);

        $response->assertSessionHasErrors(['initial_collector_name']);
    }

    public function test_collector_bisa_dikaitkan_dengan_user()
    {
        $rolePeneliti = Role::factory()->create(['name' => 'peneliti']);
        $user = User::factory()->create(['roles_id' => $rolePeneliti->id]);

        $data = [
            'full_name' => 'Linked Collector',
            'initial_collector_name' => 'LNC',
            'is_manual' => 0,
            'last_sequence' => 0,
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($this->admin)->post(route('collector.store'), $data);

        $response->assertRedirect(route('collector.index'));
        $this->assertDatabaseHas('collector_infos', [
            'initial_collector_name' => 'LNC',
            'user_id' => $user->id,
        ]);
    }

    public function test_collector_bisa_diupdate()
    {
        $collector = CollectorInfo::factory()->create(['initial_collector_name' => 'OLD']);

        $data = [
            'full_name' => 'Updated Collector',
            'initial_collector_name' => 'NEW',
            'is_manual' => 1,
            'last_sequence' => 10,
            'user_id' => null,
        ];

        $response = $this->actingAs($this->admin)->put(route('collector.update', $collector->id), $data);

        $response->assertRedirect(route('collector.index'));
        $this->assertDatabaseHas('collector_infos', [
            'id' => $collector->id,
            'initial_collector_name' => 'NEW',
        ]);
    }

    public function test_admin_bisa_menghapus_collector()
    {
        $collector = CollectorInfo::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('collector.destroy', $collector->id));

        $response->assertRedirect(route('collector.index'));

        $this->assertDatabaseMissing('collector_infos', [
            'id' => $collector->id,
        ]);
    }
}
