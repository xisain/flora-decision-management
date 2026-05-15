<aside
    class="fixed inset-y-0 left-0 bg-white text-white z-40 sidebar-transition transition-all duration-300 ease-in-out flex flex-col justify-between rounded-r-2xl shadow-xl border border-r-gray-200"
    :class="sidebarOpen ? 'w-64' : 'w-20'">
    <div>
        {{-- Logo --}}
        <div class="flex flex-col items-center py-4">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="w-12 h-12" />
            <span class="mt-2 text-gray-800 font-semibold text-sm transition-opacity duration-200"
                :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                FDM Bulungan
            </span>
        </div>

        {{-- Navigation --}}
        <nav class="px-4 py-2 space-y-2">
            <a href="{{ route('peneliti.home') }}"
                class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('peneliti.home') ? 'bg-[var(--flora-teal)] text-white font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                <i class="fa-solid fa-house" :class="{ 'text-lg': !sidebarOpen }"></i>
                <span class="ml-3 transition-opacity duration-200"
                    :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                    Dashboard
                </span>
            </a>

            {{-- Penerimaan --}}
            <a href="{{ Route::has('peneliti.penerimaan.index') ? route('peneliti.penerimaan.index') : '#' }}"
                class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('peneliti.penerimaan.*') ? 'bg-[var(--flora-teal)] text-white font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                <i class="fa-solid fa-clipboard" :class="{ 'text-lg': !sidebarOpen }"></i>
                <span class="ml-3 transition-opacity duration-200"
                    :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                    Penerimaan
                </span>
            </a>

            {{-- Penyemaian --}}
            <a href="{{ Route::has('peneliti.penyemaian.index') ? route('peneliti.penyemaian.index') : '#' }}"
                class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('peneliti.penyemaian.*') ? 'bg-(--flora-teal) text-white font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                <i class="fa-solid fa-seedling" :class="{ 'text-lg': !sidebarOpen }"></i>
                <span class="ml-3 transition-opacity duration-200"
                    :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                    Penyemaian
                </span>
            </a>

            <a href="{{ Route::has('peneliti.inspeksi.index') ? route('peneliti.inspeksi.index') : '#' }}"
                class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('peneliti.inspeksi.*') ? 'bg-(--flora-teal) text-white font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                <i class="fa-solid fa-magnifying-glass" :class="{ 'text-lg': !sidebarOpen }"></i>
                <span class="ml-3 transition-opacity duration-200"
                    :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                    Inspeksi
                </span>
            </a>
        </nav>
    </div>

    {{-- User Profile & Logout --}}
    @auth
        <div class="border-t border-gray-100 p-4">
            <div class="flex items-center gap-3" :class="{ 'justify-center': !sidebarOpen }">
                {{-- Avatar --}}
                <div
                    class="w-9 h-9 rounded-full bg-[var(--flora-teal-pale)] border-2 border-[var(--flora-teal-light)] flex items-center justify-center flex-shrink-0 text-[var(--flora-teal)] font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                {{-- Name & role --}}
                <div class="flex-1 min-w-0 transition-opacity duration-200"
                    :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" :class="{ 'hidden': !sidebarOpen }">
                    @csrf
                    <button type="submit" title="Logout"
                        class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>

            {{-- Collapsed logout --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-2" :class="{ 'hidden': sidebarOpen }">
                @csrf
                <button type="submit" title="Logout"
                    class="w-full flex justify-center text-gray-400 hover:text-red-500 transition-colors p-2 rounded-xl hover:bg-red-50">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                </button>
            </form>
        </div>
    @endauth
</aside>
