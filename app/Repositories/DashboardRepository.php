<?php

namespace App\Repositories;

use App\Models\CollectorInfo;
use App\Models\Criteria;
use App\Models\Inspeksi;
use App\Models\InspeksiTanaman;
use App\Models\Penerimaan;
use App\Models\PenerimaanTanaman;
use App\Models\Penyemaian;
use App\Models\PenyemaianTanaman;
use App\Models\Tanaman;
use App\Models\TimExplorasi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardRepository
{
    public function __construct()
    {
        //
    }

    public function dashboardPeneliti()
    {
        $userId = Auth::id();

        // 1. Data penerimaan milik user
        $dataPenerimaaan = Penerimaan::where('user_id', $userId)->get();

        $dataPenerimaaanCount = $dataPenerimaaan->count();

        $penerimaanIds = $dataPenerimaaan->pluck('id');

        // 2. Count data tanaman penerimaan
        $dataPenerimaanTanamanCount = PenerimaanTanaman::whereIn('penerimaan_id', $penerimaanIds)
            ->count();

        $penerimaanTanamanIds = PenerimaanTanaman::whereIn('penerimaan_id', $penerimaanIds)
            ->pluck('id');

        // 3. Count tanaman individual
        $tanamanIds = Tanaman::whereIn('tanaman_penerimaan_id', $penerimaanTanamanIds)
            ->pluck('id');

        $tanamanCount = $tanamanIds->count();

        // 4. Count penyemaian
        $penyemaianTanamanCount = PenyemaianTanaman::whereIn('tanaman_id', $tanamanIds)
            ->whereHas('penyemaian', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        // 5. Helper untuk count inspeksi berdasarkan stage
        $countInspeksiByStage = function (string $stage) use ($tanamanIds, $userId) {
            return InspeksiTanaman::whereIn('tanaman_id', $tanamanIds)
                ->whereHas('inspeksi', function ($query) use ($stage, $userId) {
                    $query->where('user_id', $userId)
                        ->where('stage', $stage);
                })
                ->count();
        };

        $checkupCount = $countInspeksiByStage('checkup');
        $labelingCount = $countInspeksiByStage('labeling');
        $aklimatisasiCount = $countInspeksiByStage('aklimatisasi');
        $evaluasiCount = $countInspeksiByStage('evaluasi');

        return compact(
            'dataPenerimaaanCount',
            'dataPenerimaanTanamanCount',
            'tanamanCount',
            'penyemaianTanamanCount',
            'checkupCount',
            'labelingCount',
            'aklimatisasiCount',
            'evaluasiCount');
    }

    public function dashboardAdmin()
    {

        $userCount = User::count();
        $collectorCount = CollectorInfo::count();
        $criteriaCount = Criteria::count();
        $activeCriteriaCount = Criteria::where('is_active', true)->count();
        $inactiveCriteriaCount = Criteria::where('is_active', false)->count();
        $timExplorasiCount = TimExplorasi::count();

        $tanamanCount = Tanaman::count();
        $penerimaanCount = Penerimaan::count();
        $eksplorasiCount = Penerimaan::where('jenis_form', 'eksplorasi')->count();
        $introduksiCount = Penerimaan::where('jenis_form', 'introduksi')->count();

        $recentActivities = [];

        return compact(
            'userCount',
            'collectorCount',
            'criteriaCount',
            'activeCriteriaCount',
            'inactiveCriteriaCount',
            'timExplorasiCount',
            'tanamanCount',
            'penerimaanCount',
            'eksplorasiCount',
            'introduksiCount',
            'recentActivities',
        );
    }
}
