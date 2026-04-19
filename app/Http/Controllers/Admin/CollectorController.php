<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectorInfo;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Log;
use Illuminate\Validation\Rule;
class CollectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collector = CollectorInfo::paginate(15);

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
    public function store(Request $request)
    {

        Log::info($request->all());
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'initial_collector_name' => ['required', 'unique:collector_infos,initial_collector_name,except,id'],
            'is_manual' => ['required'],
            'last_sequence' => ['required'],
        ]);
        if ($request->has('user_id')) {
            Log::info('Ada User ID');
            $validated['user_id'] = $request->user_id;
        } else {
            Log::info('tidak ada User ID');
            $validated['user_id'] = null;
        }
        $validated['initial_collector_name'] = strtoupper($validated['initial_collector_name']);
        DB::transaction(function () use ($validated) {
            CollectorInfo::create([
                'user_id'=> $validated['user_id'],
                'full_name' => $validated['full_name'],
                'initial_collector_name' => $validated['initial_collector_name'],
                'is_manual' => $validated['is_manual'],
                'last_sequence' => $validated['last_sequence'],
            ]);
            Log::info('Collector Dibuat');
        });

        return redirect()->route('collector.index')->with('success', 'Collector Berhasil di buat ');
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
        $collector = CollectorInfo::with('user')->findOrFail($id);
        $userTanpaCollector = User::doesntHave('collectorInfo')->orWhere('id', $collector->user_id)->get();
        return view('admin.collector.edit', compact('collector', 'userTanpaCollector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $collector = CollectorInfo::findOrFail($id);
        $validate = $request->validate([
            'user_id' => ['nullable','exists:users,id'],
            'full_name' => ['required','string','max:255'],
            'initial_collector_name' => ['required', 'max:3',Rule::unique('collector_infos','initial_collector_name')->ignore($id),],
            'last_sequence' => ['required'],
            'is_manual' => ['required'],
        ],[]);
        $validate['user_id'] = $request->filled('user_id')
        ? $request->user_id
        : null;
        // dd($validate);
        $collector->update([
            'user_id' => $validate['user_id'],
            'full_name' => $validate['full_name'],
            'initial_collector_name' => $validate['initial_collector_name'],
            'last_sequence' => $validate['last_sequence'],
            'is_manual' => $validate['is_manual'],
        ]);
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
