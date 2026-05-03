<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Models\penyemaian;
use App\Models\Tanaman;
use App\Models\TanamanStatusLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class penyemaianTanamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = penyemaian::with('penyemaianTanaman.Tanaman')->get();

        return view('peneliti.penyemaian.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tanaman = Tanaman::with(['tanamanPenerimaan.tanamanInfo'])->whereDoesntHave('penyemaianTanaman')->get();

        $grouped = $tanaman->groupBy('tanamanPenerimaan.nomor_akses');

        $groupedForAlpine = $grouped
            ->map(fn ($group, $key) => [
                'nomor_akses' => $key,
                'scientific_name' => $group->first()->tanamanPenerimaan->tanamanInfo->scientific_name,
                'author_name' => $group->first()->tanamanPenerimaan->tanamanInfo->author_name,
                'ids' => $group->pluck('id')->values(),
                'items' => $group->map(fn ($item) => [
                    'id' => $item->id,
                    'nomor_urut' => str_pad($item->nomor_urut, 3, '0', STR_PAD_LEFT),
                ])->values(),
            ])
            ->values();

        return view('peneliti.penyemaian.create', [
            'create' => $grouped,
            'groupedForAlpine' => $groupedForAlpine,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'tanggal_penyemaian' => ['required', 'date'],
            'lokasi_semai' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:255'],
            'tanaman' => ['required', 'array', 'min:1'],
            'tanaman.*' => ['exists:tanaman,id'],
        ]);
        // dd($validate);
        $penyemaian = penyemaian::create([
            'tanggal_semai' => $validate['tanggal_penyemaian'],
            'lokasi_semai' => $validate['lokasi_semai'],
            'catatan' => $validate['catatan'] ?? null,
            'user_id' => Auth::user()->id,
        ]);
        $penyemaian->penyemaianTanaman()->createMany(
            collect($validate['tanaman'])->map(fn ($id) => [
                'tanaman_id' => $id,
            ])->toArray());
        $statusLogs = collect($validate['tanaman'])->map(fn ($id) => [
            'tanaman_id' => $id,
            'stage' => 'penyemaian',
            'status' => 'hidup',
            'user_id' => Auth::id(),
            'catatan' => $validate['catatan'] ?? null,
            'tanggal_proses' => $validate['tanggal_penyemaian'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        TanamanStatusLogs::insert($statusLogs);
        return redirect()->route('peneliti.penyemaian.index')->with('success', 'Penyemaian berhasil di buat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = penyemaian::with('penyemaianTanaman.Tanaman.tanamanPenerimaan.TanamanInfo')->find($id);
        return view('peneliti.penyemaian.show',compact('data'));
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
