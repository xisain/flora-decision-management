<?php

namespace App\Models;

use Database\Factories\TempatMenarikFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'deskripsi', 'foto', 'kategori', 'is_featured', 'is_active', 'urutan'])]
class TempatMenarik extends Model
{
    /** @use HasFactory<TempatMenarikFactory> */
    use HasFactory;

    protected $table = 'tempat_menarik';

    protected $attributes = ['kategori' => 'lainnya', 'is_featured' => false, 'is_active' => true, 'urutan' => 0];

    public const array CATEGORIES = [
        'taman' => 'Taman', 'greenhouse' => 'Greenhouse', 'danau' => 'Danau',
        'jalan' => 'Jalan', 'fasilitas' => 'Fasilitas', 'lainnya' => 'Lainnya',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'is_active' => 'boolean', 'urutan' => 'integer'];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('urutan')->latest()->latest('id');
    }
}
