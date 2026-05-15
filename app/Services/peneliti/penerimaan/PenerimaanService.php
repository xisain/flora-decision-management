<?php

namespace App\Services\peneliti\penerimaan;

use App\Models\AnggotaTimExplorasi;
use App\Models\LegalDocuments;
use App\Models\Penerimaan;
use App\Models\PenerimaanTanaman;
use App\Models\Tanaman;
use App\Models\TanamanInfo;
use App\Models\TimExplorasi;
use App\Repositories\penerimaanRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenerimaanService
{
    public function __construct(protected nomorAksesService $nas, protected penerimaanRepository $pr) {}

    public function index(array $filters = [])
    {
        return $this->pr->search($filters);
    }

    public function store(array $validated): void
    {
        Log::info('PenerimaanService@store dimulai', [
            'user_id' => Auth::id(),
            'jumlah_tanaman' => count($validated['tanaman']),
            'jumlah_dokumen' => count($validated['dokumen']),
        ]);

        DB::transaction(function () use ($validated) {
            $timId = $this->resolveTimId($validated);
            $penerimaan = $this->createPenerimaan($validated, $timId);
            $this->createDokumen($validated['dokumen'], $penerimaan->id);
            $this->createTanaman($validated['tanaman'], $penerimaan->id);
        });

        Log::info('PenerimaanService@store selesai', ['user_id' => Auth::id()]);
    }

    private function resolveTimId(array $validated): int
    {
        if (! empty($validated['tim_id'])) {
            Log::info('Menggunakan tim yang sudah ada', ['tim_id' => $validated['tim_id']]);

            return $validated['tim_id'];
        }

        Log::info('Membuat tim baru', ['nama_tim' => $validated['tim_baru']['nama_tim']]);

        $tim = TimExplorasi::create([
            'nama_tim' => $validated['tim_baru']['nama_tim'],
            'lokasi_explorasi' => $validated['tim_baru']['lokasi'],
            'deskripsi_team' => $validated['tim_baru']['deskripsi'] ?? null,
        ]);

        Log::info("Tim #{$tim->id} {$tim->nama_tim} berhasil dibuat");

        foreach ($validated['tim_baru']['anggota'] ?? [] as $anggota) {
            AnggotaTimExplorasi::create([
                'exploration_team_id' => $tim->id,
                'collector_id' => $anggota['id'],
                'Peran' => $anggota['role'] ?? 'Kolektor',
            ]);
            Log::info("Collector #{$anggota['id']} masuk ke Tim #{$tim->id} {$tim->nama_tim}", [
                'peran' => $anggota['role'] ?? 'Kolektor',
            ]);
        }

        return $tim->id;
    }

    private function createPenerimaan(array $validated, int $timId): Penerimaan
    {
        Log::info('Membuat penerimaan', [
            'tim_id' => $timId,
            'tanggal_penerimaan' => $validated['tanggal_penerimaan'],
            'tanggal_explorasi' => $validated['tanggal_explorasi'],
        ]);

        $penerimaan = Penerimaan::create([
            'tanggal_penerimaan' => $validated['tanggal_penerimaan'],
            'tanggal_explorasi' => $validated['tanggal_explorasi'],
            'jenis_form' => $validated['jenis_form'],
            'tempat_asal' => $validated['tempat_asal'],
            'country' => $validated['country'],
            'native' => $validated['native'],
            'source' => $validated['source'],
            'exploration_team_id' => $timId,
            'user_id' => Auth::id(),
        ]);

        Log::info("Penerimaan #{$penerimaan->id} berhasil dibuat");

        return $penerimaan;
    }

    private function createDokumen(array $dokumen, int $penerimaanId): void
    {
        Log::info('Membuat dokumen', [
            'penerimaan_id' => $penerimaanId,
            'jumlah_dokumen' => count($dokumen),
        ]);

        foreach ($dokumen as $index => $doc) {
            $path = $doc['fileSurat']->store('dokumen/penerimaan', 'private');
            $legal = LegalDocuments::create([
                'penerimaan_id' => $penerimaanId,
                'nama_surat' => $doc['namaSurat'],
                'nomor_surat' => $doc['nomorSurat'] ?? null,
                'path_file' => $path,
            ]);
            Log::info("Dokumen #{$legal->id} {$legal->nama_surat} berhasil disimpan", [
                'path' => $path,
            ]);
        }
    }

    private function createTanaman(array $tanamanList, int $penerimaanId): void
    {
        Log::info('Membuat tanaman', [
            'penerimaan_id' => $penerimaanId,
            'jumlah_tanaman' => count($tanamanList),
        ]);

        $nomorAkses = $this->nas->generateBatch('BB', count($tanamanList));

        foreach ($tanamanList as $index => $t) {
            $tanamanInfo = TanamanInfo::firstOrCreate(
                [
                    'scientific_name' => $t['scientific_name'],
                    'author_name' => $t['author_name'],
                ],
                [
                    'nama_lokal' => $t['nama_lokal'] ?? null,
                    'marga' => $t['marga'] ?? null,
                    'marga_jenis' => $t['marga_jenis'] ?? null,
                    'suku' => $t['suku'] ?? null,
                    'spesies' => $t['spesies'] ?? null,
                ]
            );

            Log::info("TanamanInfo #{$tanamanInfo->id} {$tanamanInfo->scientific_name}", [
                'wasRecentlyCreated' => $tanamanInfo->wasRecentlyCreated,
            ]);

            $tanamanPenerimaan = PenerimaanTanaman::create([
                'penerimaan_id' => $penerimaanId,
                'tanaman_info_id' => $tanamanInfo->id,
                'nomor_akses' => $nomorAkses[$index],
                'jumlah_material' => $t['jumlah_material'],
                'habitus' => $t['tipe_tanaman'],
                'collector_id' => $t['collector_id'],
                'locality' => $t['locality'] ?? null,
                'vak_no' => $t['vak_no'] ?? null,
            ]);

            Log::info("PenerimaanTanaman #{$tanamanPenerimaan->id} dibuat", [
                'nomor_akses' => $nomorAkses[$index],
                'jumlah_material' => $t['jumlah_material'],
            ]);

            for ($i = 1; $i <= ($t['jumlah_material'] ?? 1); $i++) {
                $tanaman = Tanaman::create([
                    'tanaman_penerimaan_id' => $tanamanPenerimaan->id,
                    'nomor_urut' => $i,
                ]);
                Log::info("Tanaman #{$tanaman->id} nomor urut {$i} dibuat");
            }
        }

        Log::info('Semua tanaman berhasil dibuat', ['penerimaan_id' => $penerimaanId]);
    }
}
