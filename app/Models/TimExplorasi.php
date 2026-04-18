<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable('nama_tim','deskripsi_explorasi','lokasi_explorasi')]
class TimExplorasi extends Model
{
    public function AnggotaTimExplorasi() : HasMany {
        return $this->hasMany(AnggotaTimExplorasi::class);
    }
}
