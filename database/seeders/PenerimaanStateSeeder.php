<?php

namespace Database\Seeders;

class PenerimaanStateSeeder extends ApplicationStateSeederBase
{
    public function run(): void
    {
        $this->ensurePrerequisites();
        $this->seedState('penerimaan', 0, false, []);
    }
}
