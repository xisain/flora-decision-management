<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('criterias_ordinal')]
#[Fillable(
    'criteria_id',
    'label',
    'nilai',
    'operator',
    'range_from',
    'range_to',
    'urutan',
)]

class CriteriaOrdinal extends Model
{
    protected $casts = [
        'nilai'      => 'integer',
        'range_from' => 'decimal:4',
        'range_to'   => 'decimal:4',
        'urutan'     => 'integer',
    ];


    public function Criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }
    public function inspeksiNilaiCriteria()
    {
        return $this->hasMany(InspeksiNilaiCriteria::class);
    }


    public static function resolveNilai(float $input, int $kriteriaId): ?int
    {
        $ordinals = static::where('criteria_id', $kriteriaId)
            ->orderBy('urutan')
            ->get();
        if ($ordinals->isEmpty()) return null;
        foreach ($ordinals as $ordinal) {
            $match = match($ordinal->operator) {
                'eq'      => $input == $ordinal->range_from,
                'lt'      => $input <  $ordinal->range_from,
                'lte'     => $input <= $ordinal->range_from,
                'gt'      => $input >  $ordinal->range_from,
                'gte'     => $input >= $ordinal->range_from,
                'between' => $input >= $ordinal->range_from && $input <= $ordinal->range_to,
                default   => false,
            };

            if ($match) return $ordinal->nilai;
        }

        return null;
    }
}
