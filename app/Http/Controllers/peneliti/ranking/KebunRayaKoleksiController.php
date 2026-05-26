<?php

namespace App\Http\Controllers\peneliti\ranking;

use App\Http\Controllers\Controller;
use App\Models\KebunRayaKoleksi;
use App\Models\PelaporanPromethee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KebunRayaKoleksiController extends Controller
{
    /**
     * Display the botanical garden collection list.
     */
    public function index()
    {
        $koleksi = KebunRayaKoleksi::with([
            'tanaman.tanamanPenerimaan.tanamanInfo',
            'user',
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peneliti.ranking.koleksi', compact('koleksi'));
    }

    /**
     * Store selected plants as botanical garden collection.
     * Also saves PROMETHEE II flow data to pelaporan_promethee table.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'selected' => ['required', 'array', 'min:1'],
            'selected.*.tanaman_id' => ['required', 'integer', 'exists:tanaman,id'],
            'selected.*.inspeksi_tanaman_id' => ['required', 'integer', 'exists:inspeksi_tanaman,id'],
            'selected.*.ranking' => ['required', 'integer'],
            'selected.*.net_flow' => ['required', 'numeric'],
            'selected.*.leaving_flow' => ['required', 'numeric'],
            'selected.*.entering_flow' => ['required', 'numeric'],
        ], [
            'selected.required' => 'Pilih minimal satu tanaman untuk ditambahkan ke koleksi.',
        ]);

        $userId = Auth::id();
        $addedCount = 0;

        foreach ($request->input('selected') as $item) {
            $alreadyExists = KebunRayaKoleksi::where('tanaman_id', $item['tanaman_id'])->exists();

            if (! $alreadyExists) {
                KebunRayaKoleksi::create([
                    'tanaman_id' => $item['tanaman_id'],
                    'user_id' => $userId,
                ]);

                $addedCount++;
            }

            PelaporanPromethee::create([
                'tanaman_id' => $item['tanaman_id'],
                'inspeksi_tanaman_id' => $item['inspeksi_tanaman_id'],
                'user_id' => $userId,
                'ranking' => $item['ranking'],
                'net_flow' => $item['net_flow'],
                'leaving_flow' => $item['leaving_flow'],
                'entering_flow' => $item['entering_flow'],
            ]);
        }

        $skipped = count($request->input('selected')) - $addedCount;
        $message = "{$addedCount} tanaman berhasil ditambahkan ke koleksi kebun raya.";

        if ($skipped > 0) {
            $message .= " {$skipped} tanaman sudah ada dalam koleksi sebelumnya.";
        }

        return redirect()->route('peneliti.koleksi.index')->with('success', $message);
    }

    public function destroy(string $id): RedirectResponse
    {
        $koleksi = KebunRayaKoleksi::findOrFail($id);
        $koleksi->delete();

        return redirect()->route('peneliti.koleksi.index')->with('success', 'Tanaman berhasil dihapus dari koleksi kebun raya.');
    }


    public function export(Request $request): StreamedResponse
    {
        $ids = $request->input('ids', []);

        $query = KebunRayaKoleksi::with([
            'tanaman.tanamanPenerimaan.tanamanInfo',
            'user',
        ])->orderBy('created_at', 'desc');

        if (! empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $koleksi = $query->get();

        $filename = 'koleksi_kebun_raya_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($koleksi) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'Nomor Akses',
                'Nama Ilmiah',
                'Nama Lokal',
                'Suku',
                'Habitus',
                'Locality',
                'Vak No',
                'Ditambahkan Oleh',
                'Tanggal Ditambahkan',
            ]);

            foreach ($koleksi as $item) {
                $tanamanInfo = $item->tanaman?->tanamanPenerimaan?->tanamanInfo;
                $penerimaanTanaman = $item->tanaman?->tanamanPenerimaan;

                fputcsv($handle, [
                    $item->tanaman?->NomorAkses ?? '-',
                    $tanamanInfo?->scientific_name ?? '-',
                    $tanamanInfo?->nama_lokal ?? '-',
                    $tanamanInfo?->suku ?? '-',
                    $penerimaanTanaman?->habitus ?? '-',
                    $penerimaanTanaman?->locality ?? '-',
                    $penerimaanTanaman?->vak_no ?? '-',
                    $item->user?->name ?? '-',
                    $item->created_at?->format('d/m/Y H:i') ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
