<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTempatMenarikRequest;
use App\Http\Requests\UpdateTempatMenarikRequest;
use App\Models\TempatMenarik;
use App\Services\TempatMenarikPhotoStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TempatMenarikController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->validate(['search' => ['nullable', 'string', 'max:255']])['search'] ?? '';
        $places = TempatMenarik::query()->when($search, fn ($query) => $query->where('nama', 'like', '%'.$search.'%'))->ordered()->paginate(10)->withQueryString();

        return view('admin.tempat-menarik.index', compact('places', 'search'));
    }

    public function create(): View
    {
        return view('admin.tempat-menarik.create', ['place' => new TempatMenarik]);
    }

    public function store(StoreTempatMenarikRequest $request, TempatMenarikPhotoStorage $photos): RedirectResponse
    {
        $photos->save(new TempatMenarik, Arr::except($request->validated(), 'foto'), $request->file('foto'));

        return to_route('admin.tempat-menarik.index')->with('success', 'Tempat menarik berhasil ditambahkan.');
    }

    public function edit(TempatMenarik $tempatMenarik, TempatMenarikPhotoStorage $photos): View
    {
        return view('admin.tempat-menarik.edit', ['place' => $tempatMenarik, 'photoUrl' => $photos->url($tempatMenarik->foto)]);
    }

    public function update(UpdateTempatMenarikRequest $request, TempatMenarik $tempatMenarik, TempatMenarikPhotoStorage $photos): RedirectResponse
    {
        $cleaned = $photos->save($tempatMenarik, Arr::except($request->validated(), 'foto'), $request->file('foto'));
        $response = to_route('admin.tempat-menarik.index')->with('success', 'Tempat menarik berhasil diperbarui.');

        return $cleaned ? $response : $response->with('warning', 'Foto baru sudah tersimpan, tetapi foto lama belum berhasil dihapus dari penyimpanan.');
    }

    public function destroy(TempatMenarik $tempatMenarik, TempatMenarikPhotoStorage $photos): RedirectResponse
    {
        $photos->destroy($tempatMenarik);

        return to_route('admin.tempat-menarik.index')->with('success', 'Tempat menarik dan fotonya berhasil dihapus.');
    }
}
