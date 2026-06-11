<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('tanaman_id', 'inspeksi_tanaman_id', 'user_id', 'ranking', 'net_flow', 'leaving_flow', 'entering_flow')]
#[Table('pelaporan_promethee')]
class PelaporanPromethee extends Model
{
    use HasFactory;

    public function tanaman(): BelongsTo
    {
        return $this->belongsTo(Tanaman::class);
    }

    public function inspeksiTanaman(): BelongsTo
    {
        return $this->belongsTo(InspeksiTanaman::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
