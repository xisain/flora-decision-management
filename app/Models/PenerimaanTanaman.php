<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Table('tanaman_penerimaans')]
#[Fillable('penerimaan_id','nomor_akses','jumlah_material','tanaman_info_id','collector_id','locality','vak_no',)]
class PenerimaanTanaman extends Model
{
    public function collector() : BelongsTo {
        return $this->belongsTo(CollectorInfo::class);
    }
    public function TanamanInfo() : BelongsTo {
        return $this->belongsTo(TanamanInfo::class,);
    }
    public function Tanaman(): HasMany {
        return $this->hasMany(Tanaman::class,);
    }
}
