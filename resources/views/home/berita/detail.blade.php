@extends('layout.app')

@section('title', $berita->judul.' | Flora')

@section('content')
<article class="public-container news-detail py-8 md:py-12" aria-labelledby="article-title">
    <nav aria-label="Jejak navigasi" class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-flora-bark">
        <a href="{{ route('landing') }}" class="inline-flex min-h-11 items-center underline-offset-4 hover:underline">Beranda</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('berita.index') }}" class="inline-flex min-h-11 items-center underline-offset-4 hover:underline">Berita</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">Detail berita</span>
    </nav>

    <header class="grid gap-6 border-b border-flora-sage-mid py-8 md:py-10 lg:grid-cols-[minmax(0,1fr)_18rem] lg:gap-12">
        <div class="flex min-w-0 flex-col gap-4">
            @if ($berita->kategoriBerita)
                <p class="text-sm font-semibold text-flora-moss">{{ $berita->kategoriBerita->nama_kategori }}</p>
            @endif
            <h1 id="article-title" class="text-[clamp(2rem,4vw,4rem)] leading-tight font-semibold tracking-tight text-flora-moss wrap-anywhere">{{ $berita->judul }}</h1>
        </div>
        <dl class="flex flex-wrap gap-x-8 gap-y-4 self-end text-sm lg:flex-col">
            @if ($berita->user)
                <div class="flex flex-col gap-1">
                    <dt class="text-flora-bark">Ditulis oleh</dt>
                    <dd class="font-semibold text-flora-moss wrap-anywhere">{{ $berita->user->name }}</dd>
                </div>
            @endif
            @if ($berita->created_at)
                <div class="flex flex-col gap-1">
                    <dt class="text-flora-bark">Diterbitkan</dt>
                    <dd><time datetime="{{ $berita->created_at->toDateString() }}">{{ $berita->created_at->translatedFormat('d F Y') }}</time></dd>
                </div>
            @endif
            @if ($berita->visitor !== null)
                <div class="flex flex-col gap-1">
                    <dt class="text-flora-bark">Kunjungan tercatat</dt>
                    <dd class="font-semibold text-flora-moss">{{ number_format((int) $berita->visitor, 0, ',', '.') }}</dd>
                </div>
            @endif
        </dl>
    </header>

    @if ($imageUrl)
        <figure class="my-8" x-data="{ imageLoaded: false, imageFailed: false }"
                x-init="$nextTick(() => { const image = $el.querySelector('img'); if (image.complete) { imageLoaded = image.naturalWidth > 0; imageFailed = !imageLoaded } })">
            <div class="news-detail-image relative flex min-h-56 items-center justify-center rounded-2xl bg-flora-sage-pale text-flora-moss">
                <p class="absolute px-6 text-center text-sm" x-show="!imageLoaded && !imageFailed" role="status">Memuat foto berita…</p>
                <p class="px-6 text-center text-sm" x-cloak x-show="imageFailed" role="status">Foto berita tidak dapat dimuat. Isi berita tetap dapat dibaca di bawah.</p>
                <img src="{{ $imageUrl }}" alt="{{ $berita->judul }}" width="1600" height="900" fetchpriority="high"
                     class="relative z-1 w-full rounded-2xl object-cover" x-show="imageLoaded && !imageFailed"
                     x-on:load="imageLoaded = true" x-on:error="imageFailed = true">
            </div>
        </figure>
    @else
        <p class="my-8 border-l-2 border-flora-moss pl-4 text-sm text-flora-bark">Berita ini belum memiliki foto.</p>
    @endif

    <div class="grid gap-10 py-4 lg:grid-cols-[minmax(0,1fr)_18rem] lg:gap-12">
        <div class="min-w-0">
            @if ($hasContent)
                <div class="news-detail-body">{{ $articleContent }}</div>
            @else
                <div class="rounded-xl bg-flora-sage-pale p-6 text-flora-bark">
                    <h2 class="mb-2 text-lg font-semibold text-flora-moss">Isi berita belum tersedia.</h2>
                    <p>Silakan kembali ke daftar berita untuk membaca artikel lainnya.</p>
                </div>
            @endif
        </div>
        <aside class="flex flex-col items-start gap-3 border-t border-flora-sage-mid pt-6 lg:border-t-0 lg:border-l lg:pl-6 lg:pt-0" aria-label="Navigasi berita">
            <h2 class="text-lg font-semibold text-flora-moss">Berita & kegiatan</h2>
            <p class="text-sm leading-relaxed text-flora-bark">Baca kabar lain dari Kebun Raya Bundayati.</p>
            <a href="{{ route('berita.index') }}" class="public-text-link">Kembali ke daftar berita</a>
        </aside>
    </div>
</article>

@endsection
