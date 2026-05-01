<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Models\AnggotaTimExplorasi;
use App\Models\CollectorInfo;
use App\Models\legalDocuments;
use App\Models\Penerimaan;
use App\Models\PenerimaanTanaman;
use App\Models\Tanaman;
use App\Models\TanamanInfo;
use App\Models\TimExplorasi;
use App\Services\peneliti\penerimaan\nomorAksesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class penerimaanTanamanController extends Controller
{
    public function __construct(private nomorAksesService $nas) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $penerimaan = Penerimaan::with(['penerimaanTanaman', 'user'])
            ->when($request->penerimaan_dari, function ($q, $dari) {
                $q->whereDate('tanggal_penerimaan', '>=', $dari);
            })
            ->when($request->penerimaan_sampai, function ($q, $sampai) {
                $q->whereDate('tanggal_penerimaan', '<=', $sampai);
            })
            ->when($request->eksplorasi_dari, function ($q, $dari) {
                $q->whereDate('tanggal_explorasi', '>=', $dari);
            })
            ->when($request->eksplorasi_sampai, function ($q, $sampai) {
                $q->whereDate('tanggal_explorasi', '<=', $sampai);
            })
            ->latest('tanggal_penerimaan')
            ->paginate(15)
            ->withQueryString();

        return view('peneliti.penerimaan.index', compact('penerimaan'));

        return view('peneliti.penerimaan.index', compact('penerimaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $collector = CollectorInfo::all();
        $team = TimExplorasi::with('AnggotaTimExplorasi.Collector.User')->get();

        return view('peneliti.penerimaan.create', compact('collector', 'team'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            // Step 1 Validate
            'dokumen.*.namaSurat' => ['required', 'string', 'max:255'],
            'dokumen.*.nomorSurat' => ['nullable', 'string', 'max:255'],
            'dokumen.*.fileSurat' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            // Step 2 Validate
            'tanggal_penerimaan' => ['required', 'date'],
            'tanggal_explorasi' => ['required', 'date'],
            'jenis_form' => ['required', 'string'],
            'tempat_asal' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'native' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            // Step 3 Validate
            'tim_id' => ['nullable', 'required_without:tim_baru.nama_tim'],
            'tim_baru.nama_tim' => ['nullable', 'required_without:tim_id', 'string'],
            'tim_baru.lokasi' => ['nullable', 'required_with:tim_baru.nama_tim', 'string'],
            'tim_baru.deskripsi' => ['nullable', 'string'],
            'tim_baru.anggota' => ['nullable', 'array'],
            'tim_baru.anggota.*.id' => ['nullable', 'exists:collector_infos,id'],
            'tim_baru.anggota.*.role' => ['nullable', 'string'],

            // Step 4 Validate
            'tanaman' => ['array', 'min: 3'],
            'tanaman.*.scientific_name' => ['required', 'string', 'max:255'],
            'tanaman.*.nomor_akses' => ['nullable'],
            'tanaman.*.nama_lokal' => ['nullable', 'string', 'max:255'],
            'tanaman.*.marga' => ['nullable', 'string', 'max:255'],
            'tanaman.*.marga_jenis' => ['nullable', 'string', 'max:255'],
            'tanaman.*.suku' => ['nullable', 'string', 'max:255'],
            'tanaman.*.spesies' => ['nullable', 'string', 'max:255'],
            'tanaman.*.author_name' => ['nullable', 'string', 'max:255'],
            'tanaman.*.locality' => ['nullable', 'string', 'max:255'],
            'tanaman.*.jumlah_material' => ['nullable', 'integer', 'min:1'],
            'tanaman.*.vak_no' => ['nullable', 'string', 'max:255'],
            'tanaman.*.collector_id' => ['nullable', 'exists:collector_infos,id'],
            'tanaman.*.collector_initial' => ['nullable', 'string', 'max:50'],

        ]);
        // dd($validate);
        // bagaimana untuk cek apakah dia menggunakan tim baru atau yang sudah ada lalu jika baru dia akan buat tim dlu baru penerimaan
        $timId = null;
        if ($request->filled('tim_id')) {
            $timId = $request->tim_id;

        } else {
            $tim = TimExplorasi::create([
                'nama_tim' => $validate['tim_baru']['nama_tim'],
                'lokasi_explorasi' => $validate['tim_baru']['lokasi'],
                'deskripsi_team' => $validate['tim_baru']['deskripsi'] ?? null,
            ]);
            foreach ($validate['tim_baru']['anggota'] ?? [] as $anggota) {
                AnggotaTimExplorasi::create([
                    'exploration_team_id' => $tim->id,
                    'collector_id' => $anggota['id'],
                    'Peran' => $anggota['role'] ?? 'Kolektor',
                ]);
            }
            $timId = $tim->id;
        }
        // dd(count($validate['tanaman']));
        $penerimaan = Penerimaan::create([
            'tanggal_penerimaan' => $validate['tanggal_penerimaan'],
            'tanggal_explorasi' => $validate['tanggal_explorasi'],
            'jenis_form' => $validate['jenis_form'],
            'tempat_asal' => $validate['tempat_asal'],
            'country' => $validate['country'],
            'native' => $validate['native'],
            'source' => $validate['source'],
            'exploration_team_id' => $timId,
            'user_id' => Auth::user()->id,
        ]);
        foreach ($validate['dokumen'] as $doc) {
            $path = $doc['fileSurat']->store('dokumen/penerimaan', 'private');
            $legalDocs = legalDocuments::create([
                'penerimaan_id' => $penerimaan->id,
                'nama_surat' => $doc['namaSurat'],
                'nomor_surat' => $doc['nomorSurat'] ?? null,
                'path_file' => $path,
            ]);
        }
        // edit dari sini
        $nomorAkses = $this->nas->generateBatch('BB', count($validate['tanaman']));
        DB::transaction(function () use ($validate, $penerimaan, $nomorAkses) {
            foreach ($validate['tanaman'] as $index => $t) {
                // dd($validate);
                $tanamanInfo = TanamanInfo::firstOrCreate(
                    [
                        'scientific_name' => $t['scientific_name'],
                        'author_name' => $t['author_name'],
                    ], [
                        'nama_lokal' => $t['nama_lokal'] ?? null,
                        'marga' => $t['marga'] ?? null,
                        'marga_jenis' => $t['marga_jenis'] ?? null,
                        'suku' => $t['suku'] ?? null,
                        'spesies' => $t['spesies'] ?? null,
                        'locality' => $t['locality'] ?? null,
                        'vak_no' => $t['vak_no'] ?? null,
                    ]);
                    // dd($tanamanInfo); data keluar
                $tanamanPenerimaan = PenerimaanTanaman::create([
                    'penerimaan_id' => $penerimaan->id,
                    'tanaman_info_id' => $tanamanInfo->id,
                    'nomor_akses' => $nomorAkses[$index],
                    'jumlah_material' => $t['jumlah_material'],
                    'collector_id'=> $t['collector_id'],
                ]);
                // dd($tanamanPenerimaan->id); data keluar
                for ($i = 1; $i <= ($t['jumlah_material'] ?? 1); $i++) {
                    // dd($tanamanPenerimaan->id);
                    Tanaman::create([
                        'tanaman_penerimaan_id' => $tanamanPenerimaan->id,
                        'nomor_urut' => $i,
                    ]);
                }
            }
        });
        return redirect()->route('peneliti.penerimaan.index')->with('success', 'Penerimaan Berhasil Dibuat');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Penerimaan::with(['penerimaanTanaman.TanamanInfo', 'legalDocument', 'TimExplorasi', 'User'])->find($id);

        return view('peneliti.penerimaan.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $penerimaan = Penerimaan::with('penerimaanTanaman')->findOrFail($id);

        DB::transaction(function () use ($request, $penerimaan) {

            if ($request->delete_tanaman_penerimaan == 1) {
                $penerimaan->penerimaanTanaman()->delete();
            }

            // kalau mau sekalian hapus parent
            $penerimaan->delete();
        });

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
