<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('penerimaan_id', 'nama_surat', 'nomor_surat', 'path_file')]

class LegalDocuments extends Model
{
    use HasFactory;

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class);
    }
}
