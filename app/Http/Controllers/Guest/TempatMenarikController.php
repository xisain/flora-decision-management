<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\TempatMenarik;
use App\Services\TempatMenarikPhotoStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TempatMenarikController extends Controller
{
    public function index(Request $request, TempatMenarikPhotoStorage $photos): View
    {
        $category = $request->validate(['kategori' => ['nullable', Rule::in(array_keys(TempatMenarik::CATEGORIES))]])['kategori'] ?? null;
        $places = TempatMenarik::active()->when($category, fn ($query) => $query->where('kategori', $category))->ordered()->paginate(12)->withQueryString();
        $photoUrls = $places->getCollection()->mapWithKeys(fn (TempatMenarik $place): array => [$place->id => $photos->url($place->foto)]);

        return view('home.tempat-menarik.index', compact('places', 'category', 'photoUrls'));
    }

    public function show(TempatMenarik $tempatMenarik, TempatMenarikPhotoStorage $photos): View
    {
        abort_unless($tempatMenarik->is_active, 404);

        return view('home.tempat-menarik.show', ['place' => $tempatMenarik, 'photoUrl' => $photos->url($tempatMenarik->foto)]);
    }
}
