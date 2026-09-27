<nav x-data="{ mobileOpen: false }"
     class="bg-white px-6 md:px-16 lg:px-24 xl:px-32 py-4 flex items-center justify-between relative font-[Geist,sans-serif]">

    <div class="flex items-center gap-10 md:gap-16 lg:gap-20">
        <!-- Brand -->
        <a class="flex items-center gap-3 cursor-pointer" @click="navTo('dashboard')">
            <div class="w-9 h-9 shrink-0">
                <img src="/storage/images/logo.png"
                     alt="Flora Logo"
                     class="w-full h-full object-contain">
            </div>

            <div class="flex flex-col leading-tight">
                <span class="text-base font-semibold text-zinc-900 tracking-tight">
                    Flora
                </span>
                <span class="text-[11px] text-zinc-500">
                    Plant Decision Manager
                </span>
            </div>
        </a>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center gap-8">
            <a class="text-sm text-zinc-500 hover:text-zinc-800 cursor-pointer transition-colors"
               @click="navTo('tanaman')">
                Tanaman
            </a>

            <a class="text-sm text-zinc-500 hover:text-zinc-800 cursor-pointer transition-colors"
               @click="navTo('koleksi')">
                Koleksi
            </a>

            <a class="text-sm text-zinc-500 hover:text-zinc-800 cursor-pointer transition-colors"
               @click="navTo('berita')">
                Berita
            </a>
        </div>
    </div>

    <!-- Desktop Auth Actions -->
    <div class="hidden md:flex items-center gap-3">

        @guest
            <a href="{{ route('login') }}"
               class="text-sm text-zinc-500 hover:text-zinc-800 transition-colors px-4 py-2">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="flex items-center gap-2.5 bg-linear-to-r from-zinc-950 to-zinc-500 text-zinc-50 hover:text-zinc-200 text-sm font-medium pl-5 pr-2 py-2 rounded-full transition-colors">
                Register

                <span class="w-7 h-7 rounded-full bg-white flex items-center justify-center shrink-0">
                    <svg width="12"
                         height="10"
                         viewBox="0 0 12 10"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M.6 4.602h10m-4-4 4 4-4 4"
                              stroke="#3f3f47"
                              stroke-width="1.2"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
        @endguest

        @auth
            <div class="relative" x-data="{ userDropdown: false }">

                <button @click="userDropdown = !userDropdown"
                        class="flex items-center gap-2.5 bg-linear-to-r from-zinc-950 to-zinc-500 text-zinc-50 hover:text-zinc-200 text-sm font-medium pl-5 pr-2 py-2 rounded-full cursor-pointer border-0 transition-colors">

                    <svg width="16"
                         height="16"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="shrink-0">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>

                    <span>{{ Auth::user()->name }}</span>

                    <span class="w-7 h-7 rounded-full bg-white flex items-center justify-center shrink-0">
                        <svg width="10"
                             height="6"
                             viewBox="0 0 10 6"
                             fill="none"
                             xmlns="http://www.w3.org/2000/svg"
                             :class="userDropdown ? 'rotate-180' : ''"
                             class="transition-transform">
                            <path d="m1 1 4 4 4-4"
                                  stroke="#3f3f47"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>

                <div x-show="userDropdown"
                     @click.away="userDropdown = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-zinc-200 py-2 z-50">

                    <a href=""
                       class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">
                        Dashboard
                    </a>

                    <hr class="my-1 border-zinc-100">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>

    <!-- Hamburger -->
    <button @click="mobileOpen = !mobileOpen"
            class="md:hidden flex flex-col gap-1.5 cursor-pointer bg-transparent border-0 p-1">

        <span class="block w-6 h-0.5 bg-zinc-800 transition-transform duration-300"
              :style="mobileOpen ? 'transform: translateY(8px) rotate(45deg)' : ''">
        </span>

        <span class="block w-6 h-0.5 bg-zinc-800 transition-opacity duration-300"
              :style="mobileOpen ? 'opacity: 0' : 'opacity: 1'">
        </span>

        <span class="block w-6 h-0.5 bg-zinc-800 transition-transform duration-300"
              :style="mobileOpen ? 'transform: translateY(-8px) rotate(-45deg)' : ''">
        </span>
    </button>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="absolute top-full left-0 w-full bg-white border-t border-zinc-200 flex flex-col p-5 gap-1 md:hidden z-50">

        <a class="px-4 py-2.5 rounded-lg text-sm text-zinc-500 hover:bg-zinc-50 cursor-pointer transition-colors"
           @click="navTo('tanaman'); mobileOpen = false">
            Tanaman
        </a>

        <a class="px-4 py-2.5 rounded-lg text-sm text-zinc-500 hover:bg-zinc-50 cursor-pointer transition-colors"
           @click="navTo('koleksi'); mobileOpen = false">
            Koleksi
        </a>

        <a class="px-4 py-2.5 rounded-lg text-sm text-zinc-500 hover:bg-zinc-50 cursor-pointer transition-colors"
           @click="navTo('berita'); mobileOpen = false">
            Berita
        </a>

        @guest
            <div class="flex flex-col gap-2 mt-3 border-t border-zinc-100 pt-3">

                <a href="{{ route('login') }}"
                   class="px-4 py-2.5 rounded-lg text-sm text-zinc-500 hover:bg-zinc-50 transition-colors">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="flex items-center justify-center gap-2.5 bg-linear-to-r from-zinc-950 to-zinc-500 text-zinc-50 text-sm font-medium px-5 py-2.5 rounded-full w-fit transition-colors">
                    Register

                    <span class="w-7 h-7 rounded-full bg-white flex items-center justify-center shrink-0">
                        <svg width="12"
                             height="10"
                             viewBox="0 0 12 10"
                             fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M.6 4.602h10m-4-4 4 4-4 4"
                                  stroke="#3f3f47"
                                  stroke-width="1.2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            </div>
        @endguest

        @auth
            <div class="mt-3 border-t border-zinc-100 pt-3">

                <p class="px-4 py-2 text-xs text-zinc-400">
                    {{ Auth::user()->name }}
                </p>

                <a href=""
                   class="block px-4 py-2.5 rounded-lg text-sm text-zinc-500 hover:bg-zinc-50 transition-colors">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="w-full text-left px-4 py-2.5 rounded-lg text-sm text-red-600 hover:bg-red-50 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>