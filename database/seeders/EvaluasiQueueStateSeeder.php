<?php

namespace Database\Seeders;

class EvaluasiQueueStateSeeder extends ApplicationStateSeederBase
{
    public function run(): void
    {
        $this->ensurePrerequisites();
        $this->seedState('evaluasi_queue', 10, true, ['checkup', 'labeling', 'aklimatisasi']);
    }
}
