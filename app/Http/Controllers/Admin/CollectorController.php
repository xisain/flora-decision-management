<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\collector\CollectorCreateRequest;
use App\Http\Requests\admin\collector\CollectorUpdateRequest;
use App\Models\CollectorInfo;
use App\Models\User;
use App\Services\admin\collector\CollectorService;
use DB;
use Illuminate\Http\Request;
use Log;
use Illuminate\Validation\Rule;
class CollectorController extends Controller
{
    public function __construct(private CollectorService $cs){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collector = CollectorInfo::with('PenerimaanTanaman')->paginate(15);

        return view('admin.collector.index', compact('collector'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::doesntHave('collectorInfo')->get();

        return view('admin.collector.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollectorCreateRequest $request)
    {
        $this->cs->store($request->validated());
        return redirect()->route('collector.index')->with('success', 'Collector Berhasil di buat ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = CollectorInfo::with(['penerimaanTanaman.tanamanInfo','user'])->find($id);
        return view('admin.collector.show',compact('find'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $collector = CollectorInfo::with('user')->findOrFail($id);
        $userTanpaCollector = User::doesntHave('collectorInfo')->orWhere('id', $collector->user_id)->get();
        return view('admin.collector.edit', compact('collector', 'userTanpaCollector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollectorUpdateRequest $request, string $id)
    {
        $collector = CollectorInfo::findOrFail($id);
        $this->cs->update($request->validated(),$collector);
        return redirect()->route('collector.index')->with('success','Berhasil update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $collector  = CollectorInfo::findOrFail($id);
        $collector->delete();
        return redirect()->route('collector.index',)->with('success', 'Collector Berhasil di Hapus');
    }
}
