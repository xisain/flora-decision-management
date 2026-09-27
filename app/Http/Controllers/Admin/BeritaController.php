<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\kategoriBerita;
use Illuminate\Http\Request;
use App\Http\Requests\berita\beritaCreateRequest;
use Illuminate\Support\Facades\Log;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Berita = Berita::all();
        return view("admin.berita.index", compact("Berita"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriBerita = kategoriBerita::all();
        return view("admin.berita.create", compact("kategoriBerita"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(beritaCreateRequest $request)
    {
        Log::info("=== STORE BERITA START ===");

        $validated = $request->validated();

        Log::info("Validation berhasil", [
            "data" => $validated,
        ]);
        $validated = $request->validated();

        // dd("VALIDATION LOLOS", $validated);

        $kategoriInput = trim($validated['kategori_berita_id']);
        
        Log::info('Input kategori', [
            'kategori' => $kategoriInput,
        ]);
        
        $kategori = KategoriBerita::firstOrCreate(
            [
                'nama_kategori' => $kategoriInput,
            ],
            [
                'deskripsi' => '',
            ]
        );
        
        Log::info('Kategori berhasil diproses', [
            'id' => $kategori->id,
            'nama' => $kategori->nama_kategori,
        ]);
        $imagePath = null;

        if ($request->hasFile("image_url")) {
            $imagePath = $request->file("image_url")->store("berita", "public");
        }
        Berita::create([
            "judul" => $validated["judul"],
            "slugs" => $validated["slugs"],
            "kategori_berita_id" => $kategori->id,
            "content" => $validated["content"],
            "image_url" => $imagePath,
            "user_id" => auth()->id(),
            "status" => $validated["status"],
            "visitor" => 0,
        ]);

        return redirect()
            ->route("admin.berita.index")
            ->with("success", "Berita berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Berita $Berita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $Berita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $Berita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $Berita)
    {
        //
    }
}
