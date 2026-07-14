<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'description' => 'Admin Untuk Edit Berita, dan Pengaturan Bobot dan kriteria'
        ]);
        Role::create([
            'name' => 'teknisi registrasi',
            'description' => 'Admin untuk melakukan Penyemaian'
        ]);
        Role::create([
            'name' => 'teknisi pembibitan',
            'description' => 'User yang melakukan Explorasi dan Isi Data Penerimaan'
        ]);
    }
}
