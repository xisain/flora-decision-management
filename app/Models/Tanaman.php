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
    public function inspeksiTanaman()
    {
        return $this->hasMany(InspeksiTanaman::class, 'tanaman_id');
    }

    public function getNomorAksesAttribute(): string
    {
        return $this->tanamanPenerimaan->nomor_akses.'-'.str_pad($this->nomor_urut, 3, '0', STR_PAD_LEFT);
    }
}
