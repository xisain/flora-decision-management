<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Services\BeritaContentRenderer;
use App\Services\BeritaImageUrlResolver;
use Illuminate\Contracts\View\View;

class BeritaController extends Controller
{
    public function index()
    {
        return view('home.berita.index', [
            'beritas' => Berita::all(),
        ]);
    }

    public function detail(string $slug, BeritaContentRenderer $contentRenderer, BeritaImageUrlResolver $imageResolver): View
    {
        $berita = Berita::with(['kategoriBerita', 'user'])
            ->where('slugs', $slug)
            ->where('status', 'public')
            ->firstOrFail();

        $articleContent = $contentRenderer->render($berita->content ?? '');

        return view('home.berita.detail', [
            'berita' => $berita,
            'articleContent' => $articleContent,
            'hasContent' => filled(trim(strip_tags($articleContent->toHtml()))),
            'imageUrl' => $imageResolver->resolve($berita->image_url),
        ]);
    }
}
