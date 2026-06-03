<?php

namespace Database\Seeders;

class LabelingStateSeeder extends ApplicationStateSeederBase
{
    public function run(): void
    {
        $this->ensurePrerequisites();
        $this->seedState('labeling', 6, true, ['checkup', 'labeling']);
    }
}
