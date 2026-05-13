<?php

namespace App\Repositories;

use App\Models\Penerimaan;
use Illuminate\Pagination\LengthAwarePaginator;

class PenerimaanRepository
{
    public function search(array $filters = []): LengthAwarePaginator
    {
        return Penerimaan::with(['penerimaanTanaman', 'user'])
            ->when($filters['penerimaan_dari'] ?? null, function ($q, $dari) {
                $q->whereDate('tanggal_penerimaan', '>=', $dari);
            })
            ->when($filters['penerimaan_sampai'] ?? null, function ($q, $sampai) {
                $q->whereDate('tanggal_penerimaan', '<=', $sampai);
            })
            ->when($filters['eksplorasi_dari'] ?? null, function ($q, $dari) {
                $q->whereDate('tanggal_explorasi', '>=', $dari);
            })
            ->when($filters['eksplorasi_sampai'] ?? null, function ($q, $sampai) {
                $q->whereDate('tanggal_explorasi', '<=', $sampai);
            })
            ->latest('tanggal_penerimaan')
            ->paginate(15)
            ->withQueryString();
    }
}
