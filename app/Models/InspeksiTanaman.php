<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
#[Fillable('inspeksi_id','tanaman_id','status','catatan')]
#[Table('inspeksi_tanaman')]
class InspeksiTanaman extends Model
{
    public function inspeksi() {
        return $this->belongsTo(Inspeksi::class);
    }
    public function tanaman(){
        return $this->belongsTo(Tanaman::class);
    }
}
