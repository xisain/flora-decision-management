<?php

namespace App\Services\peneliti;

use App\Models\TanamanStatusLogs;
use Illuminate\Support\Facades\DB;

class TanamanLoggingService
{
    public function store(array $statuslog)
    {
        DB::transaction(function () use ($statuslog) {
            TanamanStatusLogs::insert($statuslog);
        });

    }
}
