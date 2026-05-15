<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('tanggal_inspeksi', 'catatan', 'user_id', 'stage')]
#[Table('inspeksis')]
class Inspeksi extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inspeksiTanaman()
    {
        return $this->hasMany(InspeksiTanaman::class);
    }
}
