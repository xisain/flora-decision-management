<nav class="navbar">
    <a class="nav-brand" @click="navTo('dashboard')">
        <div class="brand-icon">
            <img src="/storage/images/logo.png" alt="Tanaman Logo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <div class="brand-text">
            <span class="brand-name">Flora</span>
            <span class="brand-sub">Plant Decision Manager</span>
        </div>
    </a>

    <ul class="nav-links" :class="{ open: mobileOpen }">
        <li>
            <a :class="{ active: activeNav === 'tanaman' }" @click="navTo('tanaman')">
                Tanaman
            </a>
        </li>
        <li>
            <a :class="{ active: activeNav === 'koleksi' }" @click="navTo('koleksi')">
                Koleksi
            </a>
        </li>
        <li>
            <a :class="{ active: activeNav === 'berita' }" @click="navTo('berita')">
                Berita
            </a>
        </li>
    </ul>

    <div class="nav-actions">
        @guest
            <a class="btn-add" href="{{ route('login') }}">
                Login
            </a>
            <a class="btn-add" style="background: var(--flora-teal);" href="{{ route('register') }}">
                Register
            </a>
        @endguest

        @auth
            <div class="relative" x-data="{ userDropdown: false }">
                <button class="btn-add flex items-center gap-2" @click="userDropdown = !userDropdown">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div x-show="userDropdown" @click.away="userDropdown = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        Dashboard
                    </a>
                    <hr class="my-1 border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth

        <button class="mobile-toggle" @click="mobileOpen = !mobileOpen">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>
    </div>
</nav>
