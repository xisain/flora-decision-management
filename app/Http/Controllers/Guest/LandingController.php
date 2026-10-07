<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\TempatMenarik;
use App\Services\BeritaContentRenderer;
use App\Services\BeritaImageUrlResolver;
use App\Services\TempatMenarikPhotoStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index(BeritaImageUrlResolver $imageResolver, BeritaContentRenderer $contentRenderer, TempatMenarikPhotoStorage $photos): View
    {
        $berita = Berita::with('kategoriBerita')->where('status', 'public')->latest()->take(3)->get();

        $imageUrls = $berita->mapWithKeys(fn (Berita $item): array => [$item->id => $imageResolver->resolve($item->image_url)]);
        $excerpts = $berita->mapWithKeys(function (Berita $item) use ($contentRenderer): array {
            $text = html_entity_decode(strip_tags($contentRenderer->render($item->content ?? '')->toHtml()), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return [$item->id => Str::limit(Str::squish($text), 140)];
        });

        $places = TempatMenarik::active()->where('is_featured', true)->ordered()->limit(3)->get();
        $placePhotoUrls = $places->mapWithKeys(fn (TempatMenarik $place): array => [$place->id => $photos->url($place->foto)]);

        return view('home.landing', compact('berita', 'imageUrls', 'excerpts', 'places', 'placePhotoUrls'));
    }

    public function visi()
    {
        return view('home.visi');
    }

    public function tentangKami()
    {
        return view('home.tentangkami');
    }

    public function struktural()
    {
        return view('home.struktural');
    }
}
