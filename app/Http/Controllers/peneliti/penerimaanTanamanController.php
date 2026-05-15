<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Http\Requests\peneliti\penerimaan\CreatePenerimaanRequest;
use App\Models\CollectorInfo;
use App\Models\Penerimaan;
use App\Models\TimExplorasi;
use App\Services\peneliti\penerimaan\PenerimaanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class penerimaanTanamanController extends Controller
{
    public function __construct(private PenerimaanService $ps) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $penerimaan = $this->ps->index($request->only([
            'penerimaan_dari',
            'penerimaan_sampai',
            'eksplorasi_dari',
            'eksplorasi_sampai',
        ]));

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
    public function store(CreatePenerimaanRequest $request)
    {

        $this->ps->store($request->validated());

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
