<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\explorationTeam\ExplorationTeamCreateRequest;
use App\Http\Requests\admin\explorationTeam\ExplorationTeamUpdateRequest;
use App\Models\CollectorInfo;
use App\Models\TimExplorasi;
use App\Services\admin\explorationTeam\ExplorationTeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExplorationTeamController extends Controller
{
    public function __construct(private ExplorationTeamService $ets) {}

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
    public function store(ExplorationTeamCreateRequest $request)
    {
        $this->ets->store($request->validated());

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
            'collector' => $collector,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExplorationTeamUpdateRequest $request, string $id)
    {
        $tim = TimExplorasi::findOrFail($id);
        $this->ets->update($request->validated(), $tim);

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
