<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Table('tanaman_penerimaans')]
#[Fillable('penerimaan_id','scientific_name','nomor_akses','nama_lokal','marga','marga_jenis','suku','spesies','author_name','locality','vak_no','jumlah_material','collector_id')]
class PenerimaanTanaman extends Model
{
    public function collector() : BelongsTo {
        return $this->belongsTo(CollectorInfo::class);
    }
    public function tanaman() : HasMany {
        return $this->hasMany(Tanaman::class);
    }
}
