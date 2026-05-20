<?php

namespace App\Http\Controllers\peneliti\ranking;

use App\Http\Controllers\Controller;
use App\Models\PelaporanPromethee;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PelaporanPrometheeController extends Controller
{
    /**
     * Display the PROMETHEE II reporting history.
     */
    public function index(Request $request)
    {
        $query = PelaporanPromethee::with([
            'tanaman.tanamanPenerimaan.tanamanInfo',
            'inspeksiTanaman.inspeksi',
            'user',
        ]);

        // Filter by tanaman (nomor akses) jika ada
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('tanaman.tanamanPenerimaan', function ($q) use ($search) {
                $q->where('nomor_akses', 'like', "%{$search}%");
            });
        }

        // Filter by tanggal
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->input('dari'));
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->input('sampai'));
        }

        $pelaporan = $query->orderBy('ranking')->paginate(20)->withQueryString();

        return view('peneliti.ranking.pelaporan', compact('pelaporan'));
    }

    /**
     * Export PROMETHEE II reporting data to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $pelaporan = PelaporanPromethee::with([
            'tanaman.tanamanPenerimaan.tanamanInfo',
            'inspeksiTanaman.inspeksi',
            'user',
        ])->orderBy('ranking')->get();

        $filename = 'pelaporan_promethee_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($pelaporan) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Ranking',
                'Nomor Akses',
                'Nama Ilmiah',
                'Nama Lokal',
                'Suku',
                'Leaving Flow (Φ+)',
                'Entering Flow (Φ−)',
                'Net Flow (Φ)',
                'ID Inspeksi',
                'Tanggal Inspeksi',
                'Dicatat Oleh',
                'Tanggal Pelaporan',
            ]);

            foreach ($pelaporan as $item) {
                $tanamanInfo = $item->tanaman?->tanamanPenerimaan?->tanamanInfo;
                $inspeksiTanaman = $item->inspeksiTanaman;

                fputcsv($handle, [
                    '#'.$item->ranking,
                    $item->tanaman?->NomorAkses ?? '-',
                    $tanamanInfo?->scientific_name ?? '-',
                    $tanamanInfo?->nama_lokal ?? '-',
                    $tanamanInfo?->suku ?? '-',
                    number_format($item->leaving_flow, 4),
                    number_format($item->entering_flow, 4),
                    number_format($item->net_flow, 4),
                    '#'.($inspeksiTanaman?->inspeksi_id ?? '-'),
                    $inspeksiTanaman?->inspeksi?->tanggal_inspeksi ?? '-',
                    $item->user?->name ?? '-',
                    $item->created_at?->format('d/m/Y H:i') ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
