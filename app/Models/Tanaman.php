<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable('tanaman_penerimaan_id', 'nomor_urut')]
#[Table('tanaman')]
class Tanaman extends Model
{
    public function tanamanPenerimaan()
    {
        return $this->belongsTo(PenerimaanTanaman::class);
    }

    public function penyemaianTanaman()
    {
        return $this->hasMany(PenyemaianTanaman::class, 'tanaman_id');
    }

    public function getNomorAksesAttribute(): string
    {
        return $this->tanamanPenerimaan->nomor_akses.'-'.$this->nomor_urut;
    }
}
