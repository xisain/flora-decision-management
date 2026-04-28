<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable('user_id','tanggal_explorasi','jenis_form','tanggal_penerimaan','tempat_asal','country','source','native', 'exploration_team_id')]
class Penerimaan extends Model
{
    public function penerimaanTanaman() : HasMany {
        return $this->hasMany(PenerimaanTanaman::class);
    }
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function legalDocument() : HasMany {
        return $this->hasMany(legalDocuments::class);
    }
    public function TimExplorasi(): BelongsTo {
    return $this->belongsTo(TimExplorasi::class, 'exploration_team_id');
}
}
