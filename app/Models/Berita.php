<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
#[
    Fillable(
        "slugs",
        "kategori_berita_id",
        "judul",
        "content",
        "image_url",
        "user_id",
        "status",
        "visitor",
    ),
]
class Berita extends Model
{
    protected $table = "beritas";
    public function kategoriBerita(): BelongsTo
    {
        return $this->belongsTo(kategoriBerita::class, "kategori_berita_id");
    }
}
