<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable('nama_kategori','deskripsi')]
class kategoriBerita extends Model
{
    protected $table = "kategori_berita";
    public function berita() : hasMany {
        return $this->hasMany(Berita::class,'kategori_id');
    }
}
