<?php

namespace App\Http\Controllers\peneliti\ranking;

use App\Http\Controllers\Controller;
use App\Models\InspeksiTanaman;
use App\Services\peneliti\RankingService;
use Illuminate\Http\Request;

class RankingTanamanController extends Controller
{
    public function __construct() {}

    /**
     * Display a listing of the resource.
     */
    public function index(RankingService $rs)
    {
        $ranking = $rs->getRanking();

        return view('peneliti.ranking.index', [
            'incomplete' => $ranking['incomplete'],
            'warnings' => $ranking['warnings'],
            'service' => $ranking['service'],
            'inspeksiMap' => $ranking['inspeksiMap'],
            'inspeksiIdMap' => $ranking['inspeksiIdMap'],
        ]);
    }

    public function findByInspeksiTanamanId(string $id)
    {
        $inspeksiTanaman = InspeksiTanaman::find($id);
        dd($inspeksiTanaman);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
