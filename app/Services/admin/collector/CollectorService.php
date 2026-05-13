<?php

namespace App\Services\admin\collector;

use App\Models\CollectorInfo;
use Illuminate\Support\Facades\DB;
use Log;

class CollectorService
{
    public function store(array $validated): void
    {
        $validated['user_id'] = $validated['user_id'] ?? null;
        $validated['initial_collector_name'] = strtoupper($validated['initial_collector_name']);
        DB::transaction(function () use ($validated) {
            $collector = CollectorInfo::create([
                'user_id' => $validated['user_id'],
                'full_name' => $validated['full_name'],
                'initial_collector_name' => $validated['initial_collector_name'],
                'is_manual' => $validated['is_manual'],
                'last_sequence' => $validated['last_sequence'],
            ]);
            Log::info("collector dengan #$collector->id dibuat! ");
        });
    }

    public function update(array $validated, CollectorInfo $cf)
    {
        $validated['user_id'] = $validated['user_id'] ?? null;
        $validated['initial_collector_name'] = strtoupper($validated['initial_collector_name']);
        DB::transaction(function () use ($validated, $cf) {
            $cf->update([
                'user_id' => $validated['user_id'],
                'full_name' => $validated['full_name'],
                'initial_collector_name' => $validated['initial_collector_name'],
                'last_sequence' => $validated['last_sequence'],
                'is_manual' => $validated['is_manual'],
            ]);
            Log::info("Collector dengan #$cf->id diupdate!");
        });
    }
}
