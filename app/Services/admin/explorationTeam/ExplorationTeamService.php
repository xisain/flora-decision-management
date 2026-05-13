<?php

namespace App\Services\admin\explorationTeam;

use App\Models\TimExplorasi;
use Illuminate\Support\Facades\DB;
use Log;

class ExplorationTeamService
{
    public function store(array $validated)
    {
        DB::transaction(function () use ($validated) {
            $tim = TimExplorasi::create([
                'nama_tim' => $validated['nama_tim'],
                'deskripsi_team' => $validated['deskripsi_explorasi'],
                'lokasi_explorasi' => $validated['lokasi_explorasi'],
            ]);
            Log::info("Tim #{$tim->nama_tim} berhasil dibuat");
            foreach ($validated['anggota'] as $anggota) {
                $tim->AnggotaTimExplorasi()->create([
                    'collector_id' => $anggota['collector_id'],
                    'Peran' => $anggota['peran'],
                ]);
                Log::info("Collector #{$anggota['collector_id']} masuk kedalam Tim #{$tim->nama_tim}");
            }
        });
    }

    public function update(array $validated, TimExplorasi $te)
    {
        DB::transaction(function () use ($validated, $te) {
            $te->update([
                'nama_tim' => $validated['nama_tim'],
                'deskripsi_team' => $validated['deskripsi_explorasi'],
                'lokasi_explorasi' => $validated['lokasi_explorasi'],
            ]);
            Log::info("#{$te->id}: diupdate");
            $te->AnggotaTimExplorasi()->delete();
            Log::info('Anggota Berhasil di hapus');
            foreach ($validated['anggota'] as $anggota) {
                $te->AnggotaTimExplorasi()->create([
                    'collector_id' => $anggota['collector_id'],
                    'Peran' => $anggota['peran'],
                ]);
                Log::info("#{$te->id} Anggota Baru #{$anggota['collector_id']} Posisi : {$anggota['peran']}");
            }
        });
    }
}
