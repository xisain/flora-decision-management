<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\CriteriaCreateRequest;
use App\Http\Requests\admin\CriteriaUpdateRequest;
use App\Models\Criteria;
use App\Services\admin\CriteriaCreateService;
use App\Services\admin\CriteriaUpdateService;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function __construct(private CriteriaCreateService $criteriaCreateService, private CriteriaUpdateService $criteriaUpdateService) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteria = Criteria::all();

        return view('admin.criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.criteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CriteriaCreateRequest $request)
    {
        $this->criteriaCreateService->store($request);

        return redirect()
            ->route('criteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
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
        $criteria = Criteria::findOrFail($id);
        return view('admin.criteria.edit',compact('criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CriteriaUpdateRequest $request, string $id)
    {
        // dd($request->all());
        $criteria = Criteria::findOrFail($id);
        $this->criteriaUpdateService->update($request,$criteria);
        return redirect()->route('criteria.index')->with('success','criteria berhasil di update');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->ordinals()->delete();
        $criteria->delete();
        return redirect()->route('criteria.index')->with('success', 'Criteria Berhasil Di hapus');
    }
}
