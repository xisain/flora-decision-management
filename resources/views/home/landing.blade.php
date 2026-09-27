@extends('layout.app')

@section('content')

<style>
    /* Default: entrance offset saat scroll ke bawah */
    [data-aos] {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        will-change: opacity, transform;
    }

    /* Saat scroll ke atas, elemen yang belum masuk viewport datang dari arah berlawanan */
    html[data-scroll-dir="up"] [data-aos]:not(.aos-in) {
        transform: translateY(-28px);
    }

    [data-aos].aos-in {
        opacity: 1;
        transform: translateY(0);
    }

    [data-aos-delay="1"].aos-in { transition-delay: 0.1s; }
    [data-aos-delay="2"].aos-in { transition-delay: 0.2s; }
    [data-aos-delay="3"].aos-in { transition-delay: 0.3s; }
    [data-aos-delay="4"].aos-in { transition-delay: 0.4s; }

    @media (prefers-reduced-motion: reduce) {
        [data-aos] {
            opacity: 1;
            transform: none;
            transition: none;
        }
    }
</style>

<script>
    function navTo(section) {
        const el = document.getElementById(section);
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // ===== Deteksi arah scroll (naik / turun) =====
        let lastScrollY = window.scrollY;
        let dirTicking = false;

        function updateScrollDir() {
            const currentY = window.scrollY;
            const direction = currentY > lastScrollY ? 'down' : 'up';
            document.documentElement.setAttribute('data-scroll-dir', direction);
            lastScrollY = currentY <= 0 ? 0 : currentY;
            dirTicking = false;
        }

        window.addEventListener('scroll', () => {
            if (!dirTicking) {
                window.requestAnimationFrame(updateScrollDir);
                dirTicking = true;
            }
        }, { passive: true });

        // ===== Animasi elemen saat masuk/keluar viewport =====
        const targets = document.querySelectorAll('[data-aos]');

        if (!('IntersectionObserver' in window)) {
            targets.forEach(el => el.classList.add('aos-in'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // replay: elemen animasi masuk & keluar setiap kali melewati viewport,
                // baik saat scroll ke bawah maupun ke atas
                entry.target.classList.toggle('aos-in', entry.isIntersecting);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -80px 0px' });

        targets.forEach(el => observer.observe(el));
    });
</script>

{{-- =====================================================
    HERO
    Background image + hover youtube video
===================================================== --}}
<section
    x-data="{ hover: false }"
    @mouseenter="hover = true"
    @mouseleave="hover = false"
    class="relative min-h-[720px] flex items-center justify-center overflow-hidden"
>
    {{-- IMAGE BACKGROUND --}}
    <div class="absolute inset-0 transition-opacity duration-700" :class="hover ? 'opacity-0' : 'opacity-100'">
        <img src="{{ asset('storage/images/banner.jpg') }}" class="w-full h-full object-cover" alt="Kebun Raya Bundayati">
    </div>

    {{-- VIDEO BACKGROUND --}}
    <div class="absolute inset-0 transition-opacity duration-700" :class="hover ? 'opacity-100' : 'opacity-0'">
        <iframe
            class="w-full h-full scale-125 pointer-events-none"
            src="https://www.youtube.com/embed/PUR9dI_zxMk?autoplay=1&mute=1&controls=0&loop=1&playlist=PUR9dI_zxMk&rel=0&modestbranding=1"
            frameborder="0"
            allow="autoplay; encrypted-media">
        </iframe>
    </div>

    {{-- OVERLAY --}}
    <div class="absolute inset-0 bg-black/45"></div>

    {{-- HERO CONTENT (visible immediately, no scroll animation needed) --}}
    <div class="relative z-10 text-center px-6 max-w-4xl">
        <p class="text-sm uppercase tracking-[0.3em] text-white/80 mb-5">
            Kebun Raya Kalimantan Utara
        </p>

        <h1 class="text-5xl md:text-7xl font-semibold text-white leading-tight">
            Kebun Raya<br>Bundayati
        </h1>

        <p class="mt-6 text-lg text-white/90 max-w-2xl mx-auto">
            Pusat konservasi tumbuhan, penelitian flora, dan edukasi keanekaragaman hayati.
        </p>

        <button @click="navTo('tentang')" class="mt-10 px-7 py-3 rounded-lg bg-white text-[#085041] font-medium">
            Jelajahi Kebun Raya
        </button>
    </div>
</section>


{{-- =====================================================
    TENTANG
===================================================== --}}
<section id="tentang" class="bg-white px-6 md:px-16 lg:px-32 py-24">
    <div class="max-w-4xl mx-auto text-center" data-aos>
        <p class="text-xs uppercase tracking-widest text-[#085041] font-semibold">
            Tentang Kebun Raya
        </p>

        <h2 class="mt-4 text-4xl font-semibold text-[#085041]">
            Menjaga Keanekaragaman Flora Nusantara
        </h2>

        <p class="mt-6 text-[#444441] leading-relaxed">
            Kebun Raya Bundayati merupakan kawasan konservasi tumbuhan ex-situ yang memiliki
            peran dalam pelestarian flora, penelitian, serta edukasi lingkungan bagi masyarakat.
        </p>
    </div>
</section>


{{-- =====================================================
    STATISTIK
===================================================== --}}
<section class="bg-[#F1EFE8] px-6 md:px-32 py-20">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([
            ['jumlah' => '500+', 'label' => 'Koleksi Tanaman'],
            ['jumlah' => '20+', 'label' => 'Famili Tanaman'],
            ['jumlah' => '300 Ha', 'label' => 'Area Konservasi'],
            ['jumlah' => '10+', 'label' => 'Program Edukasi'],
        ] as $i => $stat)
            <div data-aos data-aos-delay="{{ $i % 4 }}">
                <h3 class="text-4xl font-semibold text-[#085041]">{{ $stat['jumlah'] }}</h3>
                <p class="text-sm text-[#444441]">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>


{{-- =====================================================
    KOLEKSI TANAMAN
===================================================== --}}
<section id="koleksi" class="bg-white px-6 md:px-32 py-24">
    <div class="mb-12" data-aos>
        <p class="text-xs uppercase tracking-widest text-[#085041]">Koleksi</p>
        <h2 class="mt-3 text-4xl font-semibold text-[#085041]">Koleksi Tanaman Unggulan</h2>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        @foreach([
            ['nama' => 'Shorea sp.', 'status' => 'Tanaman Konservasi'],
            ['nama' => 'Anggrek Kalimantan', 'status' => 'Flora Lokal'],
            ['nama' => 'Tanaman Endemik', 'status' => 'Prioritas Pelestarian'],
        ] as $i => $tanaman)
            <div class="rounded-xl overflow-hidden bg-[#F1EFE8]" data-aos data-aos-delay="{{ $i % 4 }}">
                <div class="h-56 bg-[#C0DD97]"></div>
                <div class="p-6">
                    <h3 class="font-semibold text-xl text-[#085041]">{{ $tanaman['nama'] }}</h3>
                    <p class="mt-2 text-sm text-[#444441]">{{ $tanaman['status'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>


{{-- =====================================================
    TEMPAT MENARIK
===================================================== --}}
<section class="bg-[#085041] px-6 md:px-32 py-24">
    <div class="text-center mb-12" data-aos>
        <h2 class="text-4xl font-semibold text-white">Jelajahi Kawasan</h2>
    </div>

    <div class="grid md:grid-cols-4 gap-6">
        @foreach(['Hutan Koleksi', 'Rumah Bibit', 'Jalur Edukasi', 'Area Penelitian'] as $i => $zona)
            <div class="rounded-xl bg-white/10 p-8 text-center text-white hover:bg-white/20 transition" data-aos data-aos-delay="{{ $i % 4 }}">
                <h3 class="font-medium">{{ $zona }}</h3>
            </div>
        @endforeach
    </div>
</section>


{{-- =====================================================
    KONSERVASI
===================================================== --}}
<section class="bg-white px-6 md:px-32 py-24">
    <div class="grid md:grid-cols-2 gap-16 items-center">
        <div data-aos>
            <p class="uppercase tracking-widest text-xs text-[#085041]">Konservasi</p>
            <h2 class="mt-4 text-4xl font-semibold text-[#085041]">Proses Pelestarian Flora</h2>
            <p class="mt-5 text-[#444441] leading-relaxed">
                Mulai dari eksplorasi tanaman, identifikasi, pembibitan, hingga pemeliharaan koleksi.
            </p>
        </div>

        <div class="space-y-5">
            @foreach(['Eksplorasi Flora', 'Identifikasi Tanaman', 'Pembibitan', 'Pemeliharaan Koleksi'] as $i => $proses)
                <div class="rounded-lg bg-[#F1EFE8] p-5 text-[#085041] font-medium" data-aos data-aos-delay="{{ $i % 4 }}">
                    {{ $proses }}
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- =====================================================
    GALERI
===================================================== --}}
<section class="bg-[#F1EFE8] px-6 md:px-32 py-24">
    <h2 class="text-4xl font-semibold text-[#085041] mb-10" data-aos>Galeri Kebun Raya</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        @for ($i = 0; $i < 8; $i++)
            <div class="aspect-square rounded-xl bg-[#C0DD97]" data-aos data-aos-delay="{{ $i % 4 }}"></div>
        @endfor
    </div>
</section>


{{-- =====================================================
    BERITA
===================================================== --}}
<section id="berita" class="bg-white px-6 md:px-32 py-24">
    <h2 class="text-4xl font-semibold text-[#085041] mb-10" data-aos>Berita Terbaru</h2>

    <div class="grid md:grid-cols-3 gap-8">
        @for ($i = 0; $i < 3; $i++)
            <article class="rounded-xl border overflow-hidden" data-aos data-aos-delay="{{ $i % 4 }}">
                <div class="h-40 bg-[#EAF3DE]"></div>
                <div class="p-6">
                    <h3 class="font-semibold text-[#085041]">Kegiatan Kebun Raya Bundayati</h3>
                    <p class="mt-3 text-sm text-[#444441]">Informasi terbaru kegiatan konservasi.</p>
                </div>
            </article>
        @endfor
    </div>
</section>


{{-- =====================================================
    KUNJUNGAN
===================================================== --}}
<section class="bg-[#085041] px-6 md:px-32 py-20 text-white">
    <div class="grid md:grid-cols-3 gap-8">
        <div data-aos data-aos-delay="0">
            <h3 class="font-semibold">Lokasi</h3>
            <p class="text-white/70 mt-2">Kalimantan Utara</p>
        </div>

        <div data-aos data-aos-delay="1">
            <h3 class="font-semibold">Jam Operasional</h3>
            <p class="text-white/70 mt-2">Senin - Minggu</p>
        </div>

        <div data-aos data-aos-delay="2">
            <h3 class="font-semibold">Kontak</h3>
            <p class="text-white/70 mt-2">Informasi Kebun Raya</p>
        </div>
    </div>
</section>

@endsection