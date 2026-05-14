<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('scientific_name', 'nama_lokal', 'marga', 'marga_jenis', 'suku', 'spesies', 'author_name')]
class TanamanInfo extends Model
{
    use HasFactory;

    public function PenerimaanTanaman()
    {
        return $this->belongsTo(PenerimaanTanaman::class, '');
    }
}
