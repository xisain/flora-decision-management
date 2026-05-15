<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Http\Requests\peneliti\penyemaian\CreatePenyemaianRequest;
use App\Repositories\penyemaianRepository;
use App\Services\peneliti\penyemaian\penyemaianService;
use Illuminate\Http\Request;

class penyemaianTanamanController extends Controller
{
    public function __construct(private penyemaianService $penyemaianservices, private penyemaianRepository $penyemaianrepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->penyemaianrepository->index();

        return view('peneliti.penyemaian.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->penyemaianrepository->create();

        return view('peneliti.penyemaian.create', [
            'create' => $data['grouped'],
            'groupedForAlpine' => $data['groupedForAlpine'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePenyemaianRequest $request)
    {

        $this->penyemaianservices->store($request->validated());

        return redirect()->route('peneliti.penyemaian.index')->with('success', 'Penyemaian berhasil di buat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->penyemaianrepository->show($id);

        return view('peneliti.penyemaian.show', compact('data'));
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
