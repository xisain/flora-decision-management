<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TanamanStatusLogs;
use Illuminate\Http\Request;

class TanamanLoggingController extends Controller
{
    public function index()
    {
        $data = TanamanStatusLogs::with('tanaman.tanamanPenerimaan.tanamanInfo')->latest()->paginate(15);
        return view('admin.tanamanlog.index',compact('data'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
