<?php

namespace Database\Seeders;

class ApplicationStateSeeder extends ApplicationStateSeederBase
{
    public function run(): void
    {
        $this->ensurePrerequisites();

        $this->call([
            PenerimaanStateSeeder::class,
            PenyemaianStateSeeder::class,
            CheckupStateSeeder::class,
            LabelingStateSeeder::class,
            AklimatisasiStateSeeder::class,
            EvaluasiQueueStateSeeder::class,
        ]);
    }
}
