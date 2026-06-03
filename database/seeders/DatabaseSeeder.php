<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TanamanInfoSainSeeder::class,
            roleSeeder::class,
            // tanamanInfoSeeders::class,
            CriteriaSeeder::class,
            CollectorSeeder::class,
            ApplicationStateSeeder::class,
        ]);
        // User::factory(100)->create();
    }
}
