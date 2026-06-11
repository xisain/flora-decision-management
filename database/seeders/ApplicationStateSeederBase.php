<?php

namespace Database\Seeders;

use App\Models\CollectorInfo;
use App\Models\Criteria;
use App\Models\Inspeksi;
use App\Models\InspeksiTanaman;
use App\Models\Penerimaan;
use App\Models\PenerimaanTanaman;
use App\Models\Penyemaian;
use App\Models\PenyemaianTanaman;
use App\Models\Role;
use App\Models\Tanaman;
use App\Models\TanamanInfo;
use App\Models\TanamanStatusLogs;
use App\Models\TimExplorasi;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class ApplicationStateSeederBase extends Seeder
{
    protected function ensurePrerequisites(): void
    {
        if (! Role::query()->exists()) {
            $this->call(roleSeeder::class);
        }

        if (! Criteria::query()->exists()) {
            $this->call(CriteriaSeeder::class);
        }

        if (
            ! User::query()->where('roles_id', 3)->exists()
            || ! CollectorInfo::query()->exists()
            || ! TimExplorasi::query()->exists()
        ) {
            $this->call(CollectorSeeder::class);
        }

        if (! TanamanInfo::query()->exists()) {
            $this->call(TanamanInfoSainSeeder::class);
        }
    }

    /**
     * @param  array<int, string>  $completedStages
     */
    protected function seedState(
        string $stateKey,
        int $tanamanInfoOffset,
        bool $withPenyemaian,
        array $completedStages
    ): void {
        DB::transaction(function () use ($stateKey, $tanamanInfoOffset, $withPenyemaian, $completedStages) {
            $peneliti = $this->resolvePeneliti();
            $collector = $this->resolveCollector();
            $tim = $this->resolveTim();
            $baseDate = CarbonImmutable::parse('2026-06-01')->addDays($tanamanInfoOffset);

            $penerimaan = Penerimaan::query()->firstOrCreate(
                ['source' => "seed:app-state:{$stateKey}"],
                [
                    'user_id' => $peneliti->id,
                    'tanggal_explorasi' => $baseDate->toDateString(),
                    'jenis_form' => 'penerimaan',
                    'tanggal_penerimaan' => $baseDate->addDay()->toDateString(),
                    'tempat_asal' => "Seeder {$stateKey}",
                    'country' => 'Indonesia',
                    'source' => "seed:app-state:{$stateKey}",
                    'native' => 'Indonesia',
                    'exploration_team_id' => $tim->id,
                ]
            );

            $tanamanInfos = $this->resolveTanamanInfos($tanamanInfoOffset, 2);
            $tanaman = collect();

            foreach ($tanamanInfos as $index => $tanamanInfo) {
                $nomorAkses = strtoupper("APP-{$stateKey}-".str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT));

                $penerimaanTanaman = PenerimaanTanaman::query()->firstOrCreate(
                    [
                        'penerimaan_id' => $penerimaan->id,
                        'nomor_akses' => $nomorAkses,
                    ],
                    [
                        'jumlah_material' => '2',
                        'habitus' => $index % 2 === 0 ? 'tree' : 'shrub',
                        'tanaman_info_id' => $tanamanInfo->id,
                        'collector_id' => $collector->id,
                        'locality' => "Lokasi Seeder {$stateKey}",
                        'vak_no' => strtoupper("VAK-{$stateKey}-".($index + 1)),
                    ]
                );

                for ($nomorUrut = 1; $nomorUrut <= 2; $nomorUrut++) {
                    $tanaman->push(
                        Tanaman::query()->firstOrCreate(
                            [
                                'tanaman_penerimaan_id' => $penerimaanTanaman->id,
                                'nomor_urut' => $nomorUrut,
                            ]
                        )
                    );
                }
            }

            if (! $withPenyemaian) {
                return;
            }

            $this->ensurePenyemaianBatch($stateKey, $peneliti, $tanaman, $baseDate->addDays(2));

            foreach (array_values($completedStages) as $index => $stage) {
                $this->ensureInspeksiBatch(
                    $stateKey,
                    $stage,
                    $peneliti,
                    $tanaman,
                    $baseDate->addDays(3 + $index)
                );
            }
        });
    }

    protected function resolvePeneliti(): User
    {
        $peneliti = User::query()
            ->where('roles_id', 3)
            ->orderBy('id')
            ->first();

        if ($peneliti instanceof User) {
            return $peneliti;
        }

        return User::factory()->create(['roles_id' => 3]);
    }

    protected function resolveCollector(): CollectorInfo
    {
        $collector = CollectorInfo::query()->orderBy('id')->first();

        if ($collector instanceof CollectorInfo) {
            return $collector;
        }

        return CollectorInfo::factory()->create([
            'user_id' => $this->resolvePeneliti()->id,
        ]);
    }

    protected function resolveTim(): TimExplorasi
    {
        $tim = TimExplorasi::query()->orderBy('id')->first();

        if ($tim instanceof TimExplorasi) {
            return $tim;
        }

        return TimExplorasi::factory()->create();
    }

    /**
     * @return Collection<int, TanamanInfo>
     */
    protected function resolveTanamanInfos(int $offset, int $count): Collection
    {
        $tanamanInfos = TanamanInfo::query()
            ->orderBy('id')
            ->skip($offset)
            ->take($count)
            ->get();

        if ($tanamanInfos->count() >= $count) {
            return $tanamanInfos;
        }

        $missing = $count - $tanamanInfos->count();

        return $tanamanInfos->concat(
            TanamanInfo::factory()->count($missing)->create()
        )->values();
    }

    /**
     * @param  Collection<int, Tanaman>  $tanaman
     */
    protected function ensurePenyemaianBatch(
        string $stateKey,
        User $peneliti,
        Collection $tanaman,
        CarbonImmutable $tanggal
    ): void {
        $penyemaian = Penyemaian::query()->firstOrCreate(
            [
                'tanggal_semai' => $tanggal->toDateString(),
                'lokasi_semai' => "Greenhouse Seeder {$stateKey}",
            ],
            [
                'user_id' => $peneliti->id,
                'catatan' => "Seeder state {$stateKey}",
            ]
        );

        foreach ($tanaman as $item) {
            PenyemaianTanaman::query()->firstOrCreate(
                [
                    'penyemaian_id' => $penyemaian->id,
                    'tanaman_id' => $item->id,
                ],
                ['status' => 'hidup']
            );

            $this->ensureStatusLog(
                $item->id,
                'penyemaian',
                'hidup',
                $peneliti->id,
                "Seeder state {$stateKey}",
                $tanggal
            );
        }
    }

    /**
     * @param  Collection<int, Tanaman>  $tanaman
     */
    protected function ensureInspeksiBatch(
        string $stateKey,
        string $stage,
        User $peneliti,
        Collection $tanaman,
        CarbonImmutable $tanggal
    ): void {
        $inspeksi = Inspeksi::query()->firstOrCreate(
            [
                'stage' => $stage,
                'tanggal_inspeksi' => $tanggal->toDateString(),
                'catatan' => "Seeder state {$stateKey} - {$stage}",
                'user_id' => $peneliti->id,
            ]
        );

        foreach ($tanaman as $item) {
            InspeksiTanaman::query()->firstOrCreate(
                [
                    'inspeksi_id' => $inspeksi->id,
                    'tanaman_id' => $item->id,
                ],
                [
                    'status' => 'hidup',
                    'catatan' => "Seeder state {$stateKey} - {$stage}",
                    'labeling' => $stage === 'labeling',
                    'tanggal_mati' => null,
                ]
            );

            $this->ensureStatusLog(
                $item->id,
                $stage,
                'hidup',
                $peneliti->id,
                "Seeder state {$stateKey} - {$stage}",
                $tanggal
            );
        }
    }

    protected function ensureStatusLog(
        int $tanamanId,
        string $stage,
        string $status,
        int $userId,
        string $catatan,
        CarbonImmutable $tanggal
    ): void {
        TanamanStatusLogs::query()->updateOrCreate(
            [
                'tanaman_id' => $tanamanId,
                'stage' => $stage,
                'tanggal_proses' => $tanggal->toDateString(),
            ],
            [
                'status' => $status,
                'user_id' => $userId,
                'catatan' => $catatan,
            ]
        );
    }
}
