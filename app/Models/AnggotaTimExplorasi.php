<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable('tim_explorasi_id','collector_id','Peran')]
#[Table('exploration_team_member')]
class AnggotaTimExplorasi extends Model
{
    public function TimExplorasi(): BelongsTo {
        return $this->belongsTo(TimExplorasi::class);
    }
    public function Collector(): BelongsTo {
    return $this->belongsTo(CollectorInfo::class, 'collector_id');
}
}
