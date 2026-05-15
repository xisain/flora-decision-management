<?php

namespace App\Services\peneliti\penyemaian;

use App\Models\Penyemaian;
use App\Models\TanamanStatusLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class penyemaianService
{
    public function store(array $validated): void
    {
        Log::info('penyemaianService@store dimulai', [
            'user_id' => Auth::id(),
            'tanggal_penyemaian' => $validated['tanggal_penyemaian'],
            'jumlah_tanaman' => count($validated['tanaman']),
        ]);

        $penyemaian = Penyemaian::create([
            'tanggal_semai' => $validated['tanggal_penyemaian'],
            'lokasi_semai' => $validated['lokasi_semai'],
            'catatan' => $validated['catatan'] ?? null,
            'user_id' => Auth::id(),
        ]);

        Log::info("Penyemaian #{$penyemaian->id} berhasil dibuat", [
            'lokasi_semai' => $penyemaian->lokasi_semai,
        ]);

        $penyemaian->penyemaianTanaman()->createMany(
            collect($validated['tanaman'])->map(fn ($id) => [
                'tanaman_id' => $id,
            ])->toArray()
        );

        Log::info('PenyemaianTanaman berhasil dibuat', [
            'penyemaian_id' => $penyemaian->id,
            'tanaman_ids' => $validated['tanaman'],
        ]);

        $statusLogs = collect($validated['tanaman'])->map(fn ($id) => [
            'tanaman_id' => $id,
            'stage' => 'penyemaian',
            'status' => 'hidup',
            'user_id' => Auth::id(),
            'catatan' => $validated['catatan'] ?? null,
            'tanggal_proses' => $validated['tanggal_penyemaian'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        TanamanStatusLogs::insert($statusLogs);

        Log::info('TanamanStatusLogs berhasil diinsert', [
            'penyemaian_id' => $penyemaian->id,
            'jumlah_log' => count($statusLogs),
        ]);

        Log::info('penyemaianService@store selesai', [
            'penyemaian_id' => $penyemaian->id,
        ]);
    }
}
