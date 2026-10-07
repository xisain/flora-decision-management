<?php

namespace Database\Seeders;

use App\Models\TempatMenarik;
use Illuminate\Database\Seeder;

class TempatMenarikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        TempatMenarik::factory()->create([
            'nama' => 'Contoh tempat untuk pengembangan',
            'deskripsi' => 'Data contoh untuk mencoba pengelolaan tempat. Ganti dengan informasi tempat yang sudah diverifikasi sebelum mengaktifkannya.',
            'is_active' => false,
            'is_featured' => false,
        ]);
    }
}
