<footer class="public-footer">
    <div class="public-container footer-layout">
        <div>
            <a class="public-brand" href="{{ route('landing') }}">Flora<small>Kebun Raya Bundayati</small></a>
            <p>Konservasi tumbuhan, penelitian flora, dan edukasi lingkungan di Kalimantan Utara.</p>
        </div>
        <nav aria-label="Navigasi footer">
            <a href="{{ route('tentang') }}">Tentang kebun raya</a>
            <a href="{{ route('landing') }}#koleksi">Pelestarian flora</a>
            <a href="{{ route('public.tempat-menarik.index') }}">Tempat menarik</a>
            <a href="{{ route('berita.index') }}">Berita & kegiatan</a>
        </nav>
    </div>
    <div class="public-container footer-bottom">&copy; {{ date('Y') }} Flora · Kebun Raya Bundayati</div>
</footer>
