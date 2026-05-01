<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable('tanggal_semai','lokasi_semai','user_id','catatan')]
class penyemaian extends Model
{
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function penyemaianTanaman() : HasMany {
        return $this->hasMany(PenyemaianTanaman::class);
    }


}
