<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('penyemaian_id', 'tanaman_id', 'status')]
#[Table('penyemaian_tanaman')]
class PenyemaianTanaman extends Model
{
    use HasFactory;

    public function penyemaian()
    {
        return $this->belongsTo(penyemaian::class);
    }

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }
}
