<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable('nama_tim','deskripsi_team','lokasi_explorasi')]
#[Table('exploration_team')]
class TimExplorasi extends Model
{
    public function AnggotaTimExplorasi() : HasMany {
        return $this->hasMany(AnggotaTimExplorasi::class, 'exploration_team_id');
    }
}
