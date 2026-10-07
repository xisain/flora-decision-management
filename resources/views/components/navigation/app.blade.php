<nav class="public-nav public-container" aria-label="Navigasi utama" x-data="{ mobileOpen: false }"
     @keydown.escape.window="if (mobileOpen) { mobileOpen = false; $refs.menuButton.focus() }" @click.outside="mobileOpen = false">
    <a class="public-brand" href="{{ route('landing') }}">
        @if (is_file(public_path('storage/images/logo.png')))
            <img src="{{ asset('storage/images/logo.png') }}" alt="" width="40" height="40">
        @endif
        <span>Flora<small>Kebun Raya Bundayati</small></span>
    </a>
    <button type="button" class="public-menu-button" x-ref="menuButton"
            @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-controls="public-menu">
        <span x-text="mobileOpen ? 'Tutup menu' : 'Menu'">Menu</span>
    </button>
    <div id="public-menu" class="public-menu" :class="{ 'is-open': mobileOpen }">
        <a href="{{ route('landing') }}#tentang" @click="mobileOpen = false">Tentang</a>
        <a href="{{ route('landing') }}#koleksi" @click="mobileOpen = false">Pelestarian</a>
        <a href="{{ route('public.tempat-menarik.index') }}" @click="mobileOpen = false">Tempat menarik</a>
        <a href="{{ route('berita.index') }}" @click="mobileOpen = false">Berita</a>
        <div class="public-account">
            @guest
                <a href="{{ route('login') }}">Masuk</a>
                <a class="public-button" href="{{ route('register') }}">Daftar akun</a>
            @endguest
            @auth
                <span class="public-user-name">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="public-button" type="submit">Keluar</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
