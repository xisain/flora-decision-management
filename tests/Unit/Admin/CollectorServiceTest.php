<?php

namespace Tests\Unit\Admin;

use App\Models\CollectorInfo;
use App\Models\User;
use App\Services\admin\collector\CollectorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectorServiceTest extends TestCase
{
    use RefreshDatabase;

    private CollectorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CollectorService();
    }

    public function test_store_creates_collector_info(): void
    {
        $user = User::factory()->create();

        $data = [
            'user_id'                => $user->id,
            'full_name'              => 'Test Collector',
            'initial_collector_name' => 'TC',
            'is_manual'              => false,
            'last_sequence'          => 0,
        ];

        $this->service->store($data);

        $this->assertDatabaseHas('collector_infos', [
            'user_id'                => $user->id,
            'initial_collector_name' => 'TC',
            'last_sequence'          => 0,
        ]);
    }

    public function test_update_updates_collector_info(): void
    {
        $user = User::factory()->create();

        $collector = CollectorInfo::create([
            'user_id'                => $user->id,
            'full_name'              => 'Old Name',
            'initial_collector_name' => 'ON',
            'is_manual'              => false,
            'last_sequence'          => 0,
        ]);

        $data = [
            'user_id'                => $user->id,
            'full_name'              => 'Updated Name',
            'initial_collector_name' => 'UN',
            'is_manual'              => true,
            'last_sequence'          => 0,
        ];

        $this->service->update($data, $collector);

        $this->assertDatabaseHas('collector_infos', [
            'id'                     => $collector->id,
            'full_name'              => 'Updated Name',
            'initial_collector_name' => 'UN',
            'is_manual'              => true,
            'last_sequence'          => 0,
        ]);
    }
}
