<?php

namespace App\Http\Controllers\peneliti;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Inspeksi;
use App\Models\InspeksiNilaiCriteria;
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
        $criteria = Criteria::with('ordinals')->active()->get();
        return view('peneliti.inspeksi.create', compact('checkup','labeling','aklimatisasi','evaluasi','criteria'));
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
            'plants.*.kriteria'         => ['required_if:stage,evaluasi', 'array'],
            'plants.*.kriteria.*.nilai' => ['required_if:stage,evaluasi'],
            'plants.*.kriteria.*.skala' => ['required_if:stage,evaluasi', 'in:numerik,ordinal'],
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
            if ($validated['stage'] === 'evaluasi' && isset($plant['kriteria'])) {
                foreach ($plant['kriteria'] as $kriteriaId => $kriteriaData) {
                    InspeksiNilaiCriteria::create([
                        'inspeksi_tanaman_id' => $inspeksiTanaman->id,
                        'criteria_id' => $kriteriaId,
                        'nilai_numeric' => $kriteriaData['skala'] === 'numerik' ? $kriteriaData['nilai']: null,
                        'criteria_ordinal_id' => $kriteriaData['skala'] === 'ordinal'? $kriteriaData['nilai']: null,
                    ]);
                }
            }
        }

        return Redirect()->route('peneliti.inspeksi.index')->with('success', 'data berhasil disimpan');

    }
    public function editEvaluasi(string $idTanaman)
    {
        $inspeksi = InspeksiTanaman::with([
            'tanaman.tanamanPenerimaan.tanamanInfo',
            'nilaiCriteria.criteria.ordinals'
        ])->findOrFail($idTanaman);

        $criteria = Criteria::active()->with('ordinals')->get();

        $nilaiMap = $inspeksi->nilaiCriteria->keyBy('criteria_id');

        return view('peneliti.inspeksi.editEvaluasi', compact('inspeksi', 'criteria', 'nilaiMap'));
    }
    public function updateEvaluasi(Request $request,string $id){
        $inspeksi = InspeksiTanaman::find($id);
        $inspeksiId = $inspeksi->inspeksi_id;
        // dump($inspeksiId);
        foreach ($request->nilai as $item) {
        InspeksiNilaiCriteria::updateOrCreate(
            [
                'inspeksi_tanaman_id' => $inspeksi->id,
                'criteria_id'         => $item['criteria_id'],
            ],
            [
                'nilai_numeric'       => $item['nilai_numeric'] ?: null,
                'criteria_ordinal_id' => $item['criteria_ordinal_id'] ?: null,
            ]
            );
        }
        return redirect()->route('peneliti.inspeksi.show',$inspeksiId);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Inspeksi::with('user')->findOrFail($id);
        $inspeksiTanamanIds = InspeksiTanaman::where('inspeksi_id', $data->id)->pluck('id')->toArray();
        $inspeksiTanaman = InspeksiTanaman::with('tanaman.tanamanPenerimaan.TanamanInfo')->where('inspeksi_id',$data->id)->paginate(10);
        $inspeksiCriteria = InspeksiNilaiCriteria::with('criteriaOrdinal')->whereIn('inspeksi_tanaman_id', $inspeksiTanamanIds)->get()->groupBy('inspeksi_tanaman_id');
        // dd($inspeksiCriteria);
        $criteria = Criteria::active()->get();
        return view('peneliti.inspeksi.show',compact('data','inspeksiTanaman','inspeksiCriteria','criteria'));
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
