<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Models\Inspeksi;
use App\Models\InspeksiTanaman;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class inspeksiTanamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Inspeksi::all();

        return view('peneliti.inspeksi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $checkup = Tanaman::with(['tanamanPenerimaan', 'penyemaianTanaman'])
            ->has('penyemaianTanaman')
            ->whereDoesntHave('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'checkup');
            })
            ->get();
        $labeling = Tanaman::with(['tanamanPenerimaan', 'penyemaianTanaman'])
            ->whereDoesntHave('inspeksiTanaman',function($q){
                $q->where('status','mati');
            })
            ->has('penyemaianTanaman')->whereHas('inspeksiTanaman.inspeksi',function($query){
                $query->where('stage','checkup');
            })
            ->whereDoesntHave('inspeksiTanaman.inspeksi', function ($query) {
                $query->where('stage', 'labeling');
            })
            ->get();
        $aklimatisasi = Tanaman::with('tanamanPenerimaan')->has('penyemaianTanaman')
        ->whereHas('inspeksiTanaman.inspeksi', function($query){
            $query->where('stage','labeling');
        })
        ->whereDoesntHave('inspeksiTanaman.inspeksi',function($query){
            $query->where('stage','aklimatisasi');
        })
        ->get();
        $evaluasi = Tanaman::with('tanamanPenerimaan')->has('penyemaianTanaman')
        ->whereHas('inspeksiTanaman.inspeksi', function($query){
            $query->where('stage','aklimatisasi');
        })
        ->whereDoesntHave('inspeksiTanaman.inspeksi',function($query){
            $query->where('stage','evaluasi');
        })
        ->get();
        return view('peneliti.inspeksi.create', compact('checkup','labeling','aklimatisasi','evaluasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'tanggal_inspeksi' => ['required', 'date'],
            'catatan' => ['required', 'string'],
            'stage' => ['required', 'string'],
            'plants.*' => ['required', 'array', 'min:1'],
            'plants.*.id' => ['required', 'exists:tanaman,id'],
            'plants.*.status' => ['required', 'string'],
        ]);
        $inspeksi = Inspeksi::create([
            'tanggal_inspeksi' => $validated['tanggal_inspeksi'],
            'catatan' => $validated['catatan'],
            'stage' => $validated['stage'],
            'user_id' => Auth::user()->id,
        ]);
        foreach ($validated['plants'] as $plant) {
            $inspeksiTanaman = InspeksiTanaman::create([
                'tanaman_id' => $plant['id'],
                'inspeksi_id' => $inspeksi->id,
                'status' => $plant['status'],
                'catatan' => $validated['catatan'],
            ]);
        }

        return Redirect()->route('peneliti.inspeksi.index')->with('success', 'data berhasil disimpan');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
