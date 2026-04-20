<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectorInfo;
use App\Models\TimExplorasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExplorationTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $team = TimExplorasi::with('AnggotaTimExplorasi.Collector.user')
            ->when($search, function ($query, $search) {
                $query->where('nama_tim', 'like', "%{$search}%")
                    ->orWhere('lokasi_explorasi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.timexplorasi.index', compact('team', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $collector = CollectorInfo::with('user')->get();

        return view('admin.timexplorasi.create', compact('collector'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'nama_tim' => ['required', 'string', 'max:255'],
            'lokasi_explorasi' => ['required', 'string', 'max:255'],
            'deskripsi_explorasi' => ['required', 'string', 'max:255'],
            'anggota' => ['required', 'array', 'min:3'],
            'anggota.*.collector_id' => ['required', 'exists:collector_infos,id'],
            'anggota.*.peran' => ['required', 'string'],
        ],
            [

                'anggota.min' => 'Untuk Tim Minimal 3 Orang Terdiri dari 1 Ketua dan 2 Anggota',
                'anggota.*.peran' => 'Mohon isi Peran dari Anggota',
                'anggota.*.collector_id.required' => 'Mohon Isi Kolektor ',
                'anggota.*.collector_id.exists' => 'Collector yang dipilih tidak valid.',
            ]);
        // dd($validate);
        DB::transaction(function () use ($validate) {
            $tim = TimExplorasi::create([
                'nama_tim' => $validate['nama_tim'],
                'deskripsi_team' => $validate['deskripsi_explorasi'],
                'lokasi_explorasi' => $validate['lokasi_explorasi'],
            ]);
            // dd($validate['anggota']);
            foreach ($validate['anggota'] as $anggota) {
                $tim->AnggotaTimExplorasi()->create([
                    'collector_id' => $anggota['collector_id'],
                    'peran' => $anggota['peran'],
                ]);
            }
        });

        return redirect()->route('tim-explorasi.index')->with('success', 'Tim Berhasil di buat');
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
        $team = TimExplorasi::with('AnggotaTimExplorasi.Collector.user')->findOrFail($id);
        $collector = CollectorInfo::with('user')->get();
        return view('admin.timexplorasi.edit', [
            'team' => $team,
            'members' => $team->AnggotaTimExplorasi,
            'collector'=> $collector,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $tim = TimExplorasi::findOrFail($id);
        $validate = $request->validate([
            'nama_tim' => ['required', 'string', 'max:255'],
            'lokasi_explorasi' => ['required', 'string', 'max:255'],
            'deskripsi_explorasi' => ['required', 'string', 'max:255'],
            'anggota' => ['required', 'array', 'min:3'],
            'anggota.*.collector_id' => ['required', 'exists:collector_infos,id'],
            'anggota.*.peran' => ['required', 'string'],
            ]);
        $tim->update([
            'nama_tim' => $validate['nama_tim'],
            'deskripsi_team' => $validate['deskripsi_explorasi'],
            'lokasi_explorasi' => $validate['lokasi_explorasi'],
            ]);
            // hapus anggota untuk didaftar baru jika ada perubahan
        $tim->AnggotaTimExplorasi()->delete();
            foreach ($validate['anggota'] as $anggota) {
            $tim->AnggotaTimExplorasi()->create([
                'collector_id' => $anggota['collector_id'],
                'peran' => $anggota['peran'],
            ]);
        }
        // dd($tim);
        return redirect()->route('tim-explorasi.index')->with('Success', 'Tim Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = TimExplorasi::findOrFail($id);
        Log::info($team->AnggotaTimExplorasi()->get());
        $team->AnggotaTimExplorasi()->delete();
        $team->delete();

        return redirect()->route('tim-explorasi.index')->with('success', 'Tim Explorasi Berhasil di Hapus');
    }
}
