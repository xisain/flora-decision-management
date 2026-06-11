<?php

namespace Database\Seeders;

class AklimatisasiStateSeeder extends ApplicationStateSeederBase
{
    public function run(): void
    {
        $this->ensurePrerequisites();
        $this->seedState('aklimatisasi', 8, true, ['checkup', 'labeling']);
    }
}
