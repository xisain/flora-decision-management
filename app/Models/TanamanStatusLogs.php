<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('tanaman_id', 'stage', 'status', 'user_id', 'catatan', 'tanggal_proses')]
class TanamanStatusLogs extends Model
{
    use HasFactory;
    public function tanaman(): BelongsTo {
        return $this->belongsTo(Tanaman::class);
    }
    public function user(): BelongsTo {
        return  $this->belongsTo(User::class);
    }
}
