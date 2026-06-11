<?php

namespace Database\Seeders;

class PenyemaianStateSeeder extends ApplicationStateSeederBase
{
    public function run(): void
    {
        $this->ensurePrerequisites();
        $this->seedState('penyemaian', 2, true, []);
    }
}
