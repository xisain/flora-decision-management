<?php

namespace Database\Seeders;

class CheckupStateSeeder extends ApplicationStateSeederBase
{
    public function run(): void
    {
        $this->ensurePrerequisites();
        $this->seedState('checkup', 4, true, ['checkup']);
    }
}
