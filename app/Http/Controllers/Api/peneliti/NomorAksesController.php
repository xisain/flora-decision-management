<?php

namespace App\Http\Controllers\Api\peneliti;

use App\Http\Controllers\Controller;
use App\Services\peneliti\penerimaan\nomorAksesService;
use Illuminate\Http\Request;

class NomorAksesController extends Controller
{
    //
    public function __construct(private nomorAksesService $nas){

    }
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'kode_kebun' => 'required|string|max:10',
            'total' => 'required|integer|min:1|max:10000'
        ]);

        $data = $this->nas->generateBatch(
            $validated['kode_kebun'],
            $validated['total']
        );

        return response()->json([
            'success' => true,
            'message' => 'Nomor akses berhasil dibuat',
            'data' => $data
        ]);
    }
}
