<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable('penyemaian_id','tanaman_id')]
class PenyemaianTanaman extends Model
{
    public function penyemaian() {
        return $this->belongsTo(penyemaian::class);
    }
}
