<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\TanamanStatusLogs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTanamanLoggingTest extends TestCase
{
    use RefreshDatabase;
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $roleAdmin = Role::factory()->create(['name' => 'admin']);
        $this->admin = User::factory()->create(['roles_id' => $roleAdmin->id]);
    }

    public function test_admin_bisa_melihat_data_log_tanaman()
    {
        $tanamanLog = TanamanStatusLogs::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('logtanaman.index'));
        $response->assertViewHas('data');
        $this->assertDatabaseHas('tanaman_status_logs',[
            'id'=> $tanamanLog->id,
        ]);
    }
}
