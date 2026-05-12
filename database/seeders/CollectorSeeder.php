<?php

namespace Database\Seeders;

use App\Models\AnggotaTimExplorasi;
use App\Models\CollectorInfo;
use App\Models\TimExplorasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CollectorSeeder extends Seeder
{
    /**
     * Seed 5 user kolektor, CollectorInfo masing-masing,
     * dan 3 grup tim eksplorasi beserta anggotanya.
     *
     * Password default semua akun: password123
     *
     * roles_id = 3 (Peneliti) → role yang digunakan kolektor
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AnggotaTimExplorasi::truncate();
        TimExplorasi::truncate();
        CollectorInfo::truncate();
        // Hapus user collector lama jika ada (bukan admin, bukan yang sudah ada sebelumnya id <= 100)
        User::where('id', '>', 100)
            ->where('roles_id', 3)
            ->whereIn('email', [
                'sain@gmail.com',
                'budi.santoso@collector.id',
                'anisa.putri@collector.id',
                'rendi.prasetyo@collector.id',
                'siti.rahayu@collector.id',
            ])
            ->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ─── 1. BUAT / AMBIL 5 USER KOLEKTOR ────────────────────────────────

        // 1a. Muhammad Husain Al Ghazali (sudah ada, ambil saja)
        $husain = User::firstOrCreate(
            ['email' => 'sain@gmail.com'],
            [
                'name'           => 'Muhammad Husain Al Ghazali',
                'password'       => Hash::make('password123'),
                'phone_number'   => '081234567890',
                'account_status' => 1,
                'roles_id'       => 3,
            ]
        );
        // Pastikan role dan status aktif
        $husain->update(['roles_id' => 3, 'account_status' => 1]);

        // 1b – 1e. 4 user generate lainnya
        $collectors = [
            [
                'name'           => 'Budi Santoso',
                'email'          => 'budi.santoso@collector.id',
                'phone_number'   => '082233445566',
                'account_status' => 1,
                'roles_id'       => 3,
                'password'       => Hash::make('password123'),
            ],
            [
                'name'           => 'Anisa Putri Rahmawati',
                'email'          => 'anisa.putri@collector.id',
                'phone_number'   => '083344556677',
                'account_status' => 1,
                'roles_id'       => 3,
                'password'       => Hash::make('password123'),
            ],
            [
                'name'           => 'Rendi Prasetyo',
                'email'          => 'rendi.prasetyo@collector.id',
                'phone_number'   => '084455667788',
                'account_status' => 1,
                'roles_id'       => 3,
                'password'       => Hash::make('password123'),
            ],
            [
                'name'           => 'Siti Rahayu Ningrum',
                'email'          => 'siti.rahayu@collector.id',
                'phone_number'   => '085566778899',
                'account_status' => 1,
                'roles_id'       => 3,
                'password'       => Hash::make('password123'),
            ],
        ];

        $userModels = [$husain];
        foreach ($collectors as $data) {
            $userModels[] = User::firstOrCreate(['email' => $data['email']], $data);
        }

        // ─── 2. BUAT COLLECTOR INFO ───────────────────────────────────────────

        $collectorData = [
            // [ user_model, full_name, initial, is_manual ]
            [$userModels[0], 'Muhammad Husain Al Ghazali', 'MHG', false],
            [$userModels[1], 'Budi Santoso',               'BDS', false],
            [$userModels[2], 'Anisa Putri Rahmawati',      'APR', false],
            [$userModels[3], 'Rendi Prasetyo',             'RDP', false],
            [$userModels[4], 'Siti Rahayu Ningrum',        'SRN', false],
        ];

        $collectorInfos = [];
        foreach ($collectorData as [$user, $fullName, $initial, $isManual]) {
            $collectorInfos[] = CollectorInfo::create([
                'user_id'                => $user->id,
                'full_name'              => $fullName,
                'initial_collector_name' => $initial,
                'is_manual'              => $isManual,
                'last_sequence'          => 0,
            ]);
        }

        // ─── 3. BUAT 3 TIM EKSPLORASI & ASSIGN ANGGOTA ───────────────────────

        /**
         * Distribusi:
         *  Tim A – Eksplorasi Hutan Berau    : Husain (Ketua), Budi (Anggota), Anisa (Anggota)
         *  Tim B – Eksplorasi Kawasan Kutai  : Rendi (Ketua), Siti (Anggota)
         *  Tim C – Eksplorasi Pantai Tarakan : Budi (Ketua), Husain (Anggota), Rendi (Anggota)
         */
        $teams = [
            [
                'info' => [
                    'nama_tim'         => 'Tim Eksplorasi Hutan Berau',
                    'deskripsi_team'   => 'Tim yang bertugas melakukan eksplorasi dan pengumpulan spesimen tanaman di kawasan hutan Berau, Kalimantan Timur.',
                    'lokasi_explorasi' => 'Hutan Berau, Kalimantan Timur',
                ],
                'members' => [
                    // [ collector_info_index, peran ]
                    [0, 'Ketua Tim'],
                    [1, 'Anggota'],
                    [2, 'Anggota'],
                ],
            ],
            [
                'info' => [
                    'nama_tim'         => 'Tim Eksplorasi Kawasan Kutai',
                    'deskripsi_team'   => 'Tim yang bertugas melakukan eksplorasi tanaman endemik di kawasan Taman Nasional Kutai, Kalimantan Timur.',
                    'lokasi_explorasi' => 'Taman Nasional Kutai, Kalimantan Timur',
                ],
                'members' => [
                    [3, 'Ketua Tim'],
                    [4, 'Anggota'],
                ],
            ],
            [
                'info' => [
                    'nama_tim'         => 'Tim Eksplorasi Pantai Tarakan',
                    'deskripsi_team'   => 'Tim yang bertugas melakukan eksplorasi tanaman pesisir dan mangrove di wilayah Tarakan, Kalimantan Utara.',
                    'lokasi_explorasi' => 'Kota Tarakan, Kalimantan Utara',
                ],
                'members' => [
                    [1, 'Ketua Tim'],
                    [0, 'Anggota'],
                    [3, 'Anggota'],
                ],
            ],
        ];

        foreach ($teams as $teamData) {
            $tim = TimExplorasi::create($teamData['info']);

            foreach ($teamData['members'] as [$collectorIndex, $peran]) {
                DB::table('exploration_team_member')->insert([
                    'exploration_team_id' => $tim->id,
                    'collector_id'        => $collectorInfos[$collectorIndex]->id,
                    'Peran'               => $peran,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);
            }
        }
    }
}
