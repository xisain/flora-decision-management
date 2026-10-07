<?php

namespace App\Models;

use Database\Factories\KategoriBeritaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('nama_kategori', 'deskripsi')]
#[UseFactory(KategoriBeritaFactory::class)]
class kategoriBerita extends Model
{
    use HasFactory;

    protected $table = 'kategori_berita';

    public function berita(): hasMany
    {
        return $this->hasMany(Berita::class, 'kategori_id');
    }
}
