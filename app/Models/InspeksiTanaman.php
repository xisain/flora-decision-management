<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('inspeksi_id', 'tanaman_id', 'status', 'catatan', 'labeling','tanggal_mati')]
#[Table('inspeksi_tanaman')]
class InspeksiTanaman extends Model
{
    use HasFactory;

    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class);
    }

    public function nilaiCriteria()
    {
        return $this->hasMany(InspeksiNilaiCriteria::class);
    }

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }
}
