<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable('tim_explorasi_id','user_id','peran')]
class AnggotaTimExplorasi extends Model
{
    public function TimExplorasi(): BelongsTo {
        return $this->belongsTo(TimExplorasi::class);
    }
    public function user(): HasMany {
        return $this->hasMany(User::class);
    }
}
