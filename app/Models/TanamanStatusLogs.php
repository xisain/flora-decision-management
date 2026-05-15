<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('tanaman_id', 'stage', 'status', 'user_id', 'catatan', 'tanggal_proses')]
class TanamanStatusLogs extends Model
{
    use HasFactory;
    //
}
