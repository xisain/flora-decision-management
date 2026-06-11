<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Http\Requests\peneliti\inspeksi\CreateInspeksiRequest;
use App\Models\Criteria;
use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use App\Repositories\inspeksiRepository;
use App\Services\peneliti\inspeksi\inspeksiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class inspeksiTanamanController extends Controller
{
    public function __construct(private inspeksiRepository $inspeksiRepository, private inspeksiService $inspeksiservice) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('peneliti.inspeksi.index',   $this->inspeksiRepository->all($request));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->inspeksiRepository->stage();
        return view('peneliti.inspeksi.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateInspeksiRequest $request)
    {
        // dump($request->all());
        $this->inspeksiservice->store($request->validated());
        return Redirect()->route('peneliti.inspeksi.index')->with('success', 'data berhasil disimpan');

    }

    public function editEvaluasi(string $idTanaman)
    {
        return view('peneliti.inspeksi.editEvaluasi', $this->inspeksiRepository->findInspeksiTanamanWithCriteria($idTanaman));
    }

    public function updateEvaluasi(Request $request, string $id)
    {
        return redirect()->route('peneliti.inspeksi.show', $this->inspeksiservice->updateEvaluasi($request->nilai, $id));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('peneliti.inspeksi.show', $this->inspeksiRepository->findInspeksiDetail($id));
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
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
