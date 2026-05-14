<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('inspeksi_nilai_criteria')]
#[Fillable('inspeksi_tanaman_id', 'criteria_id', 'nilai_numeric', 'criteria_ordinal_id')]
class InspeksiNilaiCriteria extends Model
{
    use HasFactory;

    public function inspeksiTanaman(): BelongsTo
    {
        return $this->belongsTo(InspeksiTanaman::class);
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    public function criteriaOrdinal(): BelongsTo
    {
        return $this->belongsTo(CriteriaOrdinal::class, 'criteria_ordinal_id');
    }
}
