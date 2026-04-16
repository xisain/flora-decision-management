<!DOCTYPE html>
<html class="h-full bg-gray-100" x-data="{
          sidebarOpen: localStorage.getItem('sidebar_open') !== null ? localStorage.getItem('sidebar_open') === 'true' : true,
          dropdownOpen: {{ request()->routeIs('admin.category.*') || request()->routeIs('admin.course.*') ? 'true' : 'false' }},
          userOpen: false,

          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebar_open', this.sidebarOpen);

              // Sync ke server
              fetch('{{ route('sidebar.toggle') }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({ open: this.sidebarOpen })
              });
          }
      }" lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('header')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/1d34baef3f.js" crossorigin="anonymous"></script>
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-transition {
            transition: width 0.25s ease-in-out, margin-left 0.25s ease-in-out;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full flex bg-gray-100" x-cloak>
    @include('components.navigation.admin')
    <div class="flex-1 flex flex-col min-h-screen sidebar-transition transition-all duration-300 ease-in-out" :class="sidebarOpen ? 'ml-64' : 'ml-20'">

        <!-- NAVBAR -->
        <header class="flex items-center justify-between px-6 py-4 relative z-30" x-data="{ userOpen: false }">
            <button @click="toggleSidebar()" class="text-gray-700 text-xl focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>

            @auth
            <div class="relative">
                <button @click="userOpen = !userOpen" @click.outside="userOpen = false"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-[var(--flora-teal-pale)] border-2 border-[var(--flora-teal-light)] flex items-center justify-center text-[var(--flora-teal)] font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                       :class="{ 'rotate-180': userOpen }"></i>
                </button>

                <div x-show="userOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 origin-top-right">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-xs text-gray-400">Masuk sebagai</p>
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </header>

        <!-- CONTENT -->
        <main class="flex-1 px-6 py-3">
                @yield('content')
        </main>
    </div>
</body>
@stack('scripts')
</html>
