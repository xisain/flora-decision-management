@extends('layout.app')

@section('title', 'Kebun Raya Bundayati | Flora')
@section('html-class', 'landing-scroll')

@section('content')
<div class="landing-page">
    <section class="landing-hero hero-background" aria-labelledby="hero-title"
             x-data="{ videoPlaying: false, imageFailed: false }"
             x-init="$nextTick(() => { const image = $el.querySelector('.hero-background-image'); if (image.complete) imageFailed = image.naturalWidth === 0; })"
             @mouseenter="if (window.matchMedia('(hover: hover) and (prefers-reduced-motion: no-preference)').matches) videoPlaying = true"
             @mouseleave="videoPlaying = false" @keydown.escape="videoPlaying = false">
        <img class="hero-background-image" src="{{ asset('storage/images/banner.jpg') }}"
             alt="Kawasan Kebun Raya Bundayati" fetchpriority="high" width="1920" height="1080"
             x-show="!imageFailed" x-on:error="imageFailed = true">
        <template x-if="videoPlaying">
            <iframe id="hero-video" class="hero-background-video" tabindex="-1" aria-hidden="true"
                    title="Video kawasan Kebun Raya Bundayati"
                    src="https://www.youtube.com/embed/PUR9dI_zxMk?autoplay=1&mute=1&controls=0&loop=1&playlist=PUR9dI_zxMk&rel=0"
                    allow="autoplay; encrypted-media" referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </template>
        <div class="hero-background-scrim" aria-hidden="true"></div>
        <p class="hero-image-state" x-cloak x-show="imageFailed">Foto kawasan belum tersedia.</p>
        <div class="hero-background-content public-container">
            <p class="section-label">Kebun Raya Kalimantan Utara</p>
            <h1 id="hero-title">Kebun Raya Bundayati</h1>
            <div class="hero-background-summary">
                <p>Pusat konservasi tumbuhan, penelitian flora, dan edukasi lingkungan di Kalimantan Utara.</p>
                <a class="public-button hero-background-cta" href="#tentang">Jelajahi Kebun Raya</a>
            </div>
            <button class="hero-video-toggle" type="button" @click="videoPlaying = !videoPlaying"
                    aria-label="Putar atau hentikan video latar" :aria-pressed="videoPlaying"
                    x-text="videoPlaying ? 'Hentikan video latar' : 'Putar video latar'">Putar video latar</button>
        </div>
    </section>
    <section id="tentang" class="landing-about public-container" aria-labelledby="about-title">
        <div>
            <p class="section-label">Tentang kebun raya</p>
            <h2 id="about-title">Ruang untuk tumbuh.<br>Ruang untuk belajar.</h2>
        </div>
        <div class="about-copy">
            <p>Kebun Raya Bundayati merupakan kawasan konservasi tumbuhan ex-situ yang memiliki peran dalam pelestarian flora, penelitian, serta edukasi lingkungan bagi masyarakat.</p>
            <a href="{{ route('tentang') }}" class="public-text-link">Baca tentang Kebun Raya Bundayati</a>
        </div>
    </section>
    <section id="koleksi" class="landing-conservation" aria-labelledby="conservation-title">
        <div class="public-container conservation-layout">
            <div class="conservation-copy">
                <p class="section-label">Pelestarian flora</p>
                <h2 id="conservation-title">Merawat kehidupan,<br>selangkah demi selangkah.</h2>
                <p>Mulai dari eksplorasi tanaman, identifikasi, pembibitan, hingga pemeliharaan koleksi.</p>
            </div>
            <ol class="conservation-process">
                @foreach (['Eksplorasi flora', 'Identifikasi tanaman', 'Pembibitan', 'Pemeliharaan koleksi'] as $proses)
                    <li><span aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $proses }}</h3></li>
                @endforeach
            </ol>
        </div>
    </section>
    <section id="tempat-menarik" class="landing-places public-container" aria-labelledby="places-title">
        <div class="places-heading">
            <div>
                <p class="section-label">Di dalam kawasan</p>
                <h2 id="places-title">Tempat menarik</h2>
            </div>
            <a class="public-text-link" href="{{ route('public.tempat-menarik.index') }}">Lihat semua tempat menarik</a>
        </div>
        <div class="places-list">
            @forelse (($places ?? collect()) as $place)
                <x-tempat-menarik-card :place="$place" :photo-url="$placePhotoUrls[$place->id] ?? null" />
            @empty
                <div class="places-empty">
                    <h3>Tempat menarik sedang disiapkan.</h3>
                    <p>Foto dan informasi tempat di Kebun Raya Bundayati akan tampil setelah diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </section>
    <section id="berita" class="landing-news public-container" aria-labelledby="news-title">
        <div class="news-heading">
            <div><p class="section-label">Kabar dari kebun</p><h2 id="news-title">Cerita & kegiatan terbaru</h2></div>
            <a href="{{ route('berita.index') }}" class="public-text-link">Lihat semua berita</a>
        </div>
        <div class="news-list {{ $berita->count() === 1 ? 'news-list-single' : ($berita->count() === 2 ? 'news-list-pair' : '') }}">
            @forelse ($berita as $item)
                <article class="news-article {{ $loop->first ? 'news-article-featured' : '' }}">
                    <div class="news-image" x-data="{ imageFailed: false, imageLoaded: false }"
                         x-init="$nextTick(() => { const image = $el.querySelector('img'); if (image?.complete) { imageLoaded = image.naturalWidth > 0; imageFailed = !imageLoaded; } })">
                        @if ($imageUrls[$item->id] ?? null)
                            <p class="image-state" x-show="!imageLoaded && !imageFailed" role="status">Memuat foto berita…</p>
                            <p class="image-state" x-cloak x-show="imageFailed">Foto berita tidak tersedia.</p>
                            <img src="{{ $imageUrls[$item->id] }}"
                                 alt="{{ $item->judul }}" crossorigin="anonymous" loading="lazy" width="800" height="600"
                                 x-show="imageLoaded && !imageFailed" x-on:load="imageLoaded = true" x-on:error="imageFailed = true">
                        @else
                            <p class="image-state">Belum ada foto untuk berita ini.</p>
                        @endif
                    </div>
                    <div class="news-copy">
                        <p class="news-meta">
                            @if ($item->kategoriBerita)
                                <span>{{ $item->kategoriBerita->nama_kategori }}</span>
                            @endif
                            @if ($item->created_at)
                                <time datetime="{{ $item->created_at->toDateString() }}">{{ $item->created_at->translatedFormat('d F Y') }}</time>
                            @endif
                        </p>
                        <h3><a href="{{ route('berita.detail', $item->slugs) }}">{{ $item->judul }}</a></h3>
                        @if (filled($excerpts[$item->id] ?? null))
                            <p class="news-excerpt">{{ $excerpts[$item->id] }}</p>
                        @endif
                        <a class="public-text-link" href="{{ route('berita.detail', $item->slugs) }}" aria-label="Baca berita: {{ $item->judul }}">Baca berita</a>
                    </div>
                </article>
            @empty
                <div class="news-empty">
                    <h3>Belum ada kabar terbaru.</h3>
                    <p>Berita kegiatan kebun raya akan ditampilkan di sini setelah diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
