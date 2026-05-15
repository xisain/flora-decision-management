<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table('criterias')]
#[Fillable(
    'nama_criteria',
    'satuan',
    'bobot',
    'tipe',
    'skala',
    'preference_function',
    'param_q',
    'param_p',
    'param_sigma',
    'is_active',
)]

class Criteria extends Model
{
    use HasFactory;

    protected $casts = [
        'bobot' => 'decimal:2',
        'param_q' => 'decimal:4',
        'param_p' => 'decimal:4',
        'param_sigma' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function ordinals()
    {
        return $this->hasMany(CriteriaOrdinal::class, 'criteria_id')
            ->orderBy('urutan');
    }

    public function inspeksiNilaiCriteria()
    {
        return $this->hasMany(InspeksiNilaiCriteria::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
