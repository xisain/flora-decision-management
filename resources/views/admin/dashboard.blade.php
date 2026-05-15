@extends('layout.admin')

@section('content')
    @php
        $stats = [
            [
                'title' => 'Total User',
                'value' => $userCount ?? 0,
                'description' => 'Seluruh akun pengguna sistem',
                'icon' => 'users',
                'iconBg' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600',
                'badge' => 'User',
                'badgeClass' => 'bg-emerald-50 text-emerald-700',
            ],
            [
                'title' => 'Total Collector',
                'value' => $collectorCount ?? 0,
                'description' => 'Data kolektor tanaman',
                'icon' => 'collector',
                'iconBg' => 'bg-sky-50',
                'iconColor' => 'text-sky-600',
                'badge' => 'Collector',
                'badgeClass' => 'bg-sky-50 text-sky-700',
            ],
            [
                'title' => 'Total Criteria',
                'value' => $criteriaCount ?? 0,
                'description' => 'Kriteria penilaian tanaman',
                'icon' => 'criteria',
                'iconBg' => 'bg-amber-50',
                'iconColor' => 'text-amber-600',
                'badge' => 'Criteria',
                'badgeClass' => 'bg-amber-50 text-amber-700',
            ],
            [
                'title' => 'Tim Eksplorasi',
                'value' => $timExplorasiCount ?? 0,
                'description' => 'Tim lapangan yang terdaftar',
                'icon' => 'team',
                'iconBg' => 'bg-violet-50',
                'iconColor' => 'text-violet-600',
                'badge' => 'Team',
                'badgeClass' => 'bg-violet-50 text-violet-700',
            ],
        ];

        $floraStats = [
            [
                'title' => 'Total Tanaman',
                'value' => $tanamanCount ?? 0,
                'description' => 'Seluruh tanaman dalam sistem',
                'color' => 'bg-emerald-500',
            ],
            [
                'title' => 'Total Penerimaan',
                'value' => $penerimaanCount ?? 0,
                'description' => 'Data penerimaan tanaman',
                'color' => 'bg-sky-500',
            ],
            [
                'title' => 'Eksplorasi',
                'value' => $eksplorasiCount ?? 0,
                'description' => 'Tanaman dari kegiatan eksplorasi',
                'color' => 'bg-amber-500',
            ],
            [
                'title' => 'Introduksi',
                'value' => $introduksiCount ?? 0,
                'description' => 'Tanaman dari kegiatan introduksi',
                'color' => 'bg-violet-500',
            ],
        ];

        $managementMenus = [
            [
                'title' => 'Manajemen User',
                'description' => 'Kelola akun admin, peneliti, dan role pengguna.',
                'route' => Route::has('user.index') ? route('user.index') : '#',
                'button' => 'Kelola User',
                'color' => 'text-emerald-700 bg-emerald-50 border-emerald-100',
            ],
            [
                'title' => 'Manajemen Collector',
                'description' => 'Kelola data kolektor dan identitas koleksi tanaman.',
                'route' => Route::has('collector.index') ? route('collector.index') : '#',
                'button' => 'Kelola Collector',
                'color' => 'text-sky-700 bg-sky-50 border-sky-100',
            ],
            [
                'title' => 'Manajemen Criteria',
                'description' => 'Atur kriteria, bobot, tipe, skala, dan fungsi preferensi.',
                'route' => Route::has('criteria.index') ? route('criteria.index') : '#',
                'button' => 'Kelola Criteria',
                'color' => 'text-amber-700 bg-amber-50 border-amber-100',
            ],
            [
                'title' => 'Tim Eksplorasi',
                'description' => 'Kelola anggota dan data tim eksplorasi lapangan.',
                'route' => Route::has('tim-explorasi.index') ? route('tim-explorasi.index') : '#',
                'button' => 'Kelola Tim',
                'color' => 'text-violet-700 bg-violet-50 border-violet-100',
            ],
        ];
    @endphp

        <div class="mx-auto space-y-6 px-4 py-6 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-600">
                    Halo, {{ Auth::user()->name ?? 'Admin' }}!
                </p>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                            Dashboard Admin
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Pantau data utama sistem dan kelola master data Flora Decision Management.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-medium text-slate-500 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Sistem Aktif
                    </div>
                </div>
            </div>

            {{-- Main Stats --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex flex-col gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $stat['iconBg'] }}">
                                    @switch($stat['icon'])
                                        @case('users')
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 100-8 4 4 0 000 8zM9 10a4 4 0 100-8 4 4 0 000 8z" />
                                            </svg>
                                        @break

                                        @case('collector')
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5.121 17.804A9 9 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14c-3.314 0-6 1.343-6 3v1h12v-1c0-1.657-2.686-3-6-3z" />
                                            </svg>
                                        @break

                                        @case('criteria')
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7l2 2 4-4" />
                                            </svg>
                                        @break

                                        @default
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                                            </svg>
                                    @endswitch
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        {{ $stat['title'] }}
                                    </p>
                                    <p class="mt-1 text-3xl font-bold text-slate-900">
                                        {{ number_format($stat['value']) }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $stat['description'] }}
                                    </p>
                                </div>
                            </div>

                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $stat['badgeClass'] }}">
                                {{ $stat['badge'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Flora Data Overview --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-5 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Ringkasan Data Flora
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Statistik umum data tanaman, penerimaan, eksplorasi, dan introduksi.
                        </p>
                    </div>

                    <a href="#"
                        class="w-fit rounded-full px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                        Lihat Detail
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($floraStats as $item)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $item['title'] }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $item['description'] }}
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white">
                                    <span class="text-sm font-bold text-slate-900">
                                        {{ number_format($item['value']) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                                <div class="h-full rounded-full {{ $item['color'] }}"
                                    style="width: {{ min(100, $item['value'] > 0 ? 75 : 0) }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Management Section --}}
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                @foreach ($managementMenus as $menu)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="mb-3 inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $menu['color'] }}">
                                    Master Data
                                </div>

                                <h3 class="text-base font-bold text-slate-900">
                                    {{ $menu['title'] }}
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    {{ $menu['description'] }}
                                </p>
                            </div>

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="{{ $menu['route'] }}"
                                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                {{ $menu['button'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bottom Section --}}
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                {{-- Criteria Status --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-1">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Status Criteria
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Ringkasan kriteria aktif dan tidak aktif.
                    </p>

                    <div class="mt-5 space-y-4">
                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Aktif</span>
                                <span class="text-sm font-bold text-emerald-700">
                                    {{ number_format($activeCriteriaCount ?? 0) }}
                                </span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-emerald-500"
                                    style="width: {{ ($criteriaCount ?? 0) > 0 ? min(100, (($activeCriteriaCount ?? 0) / $criteriaCount) * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Tidak Aktif</span>
                                <span class="text-sm font-bold text-red-600">
                                    {{ number_format($inactiveCriteriaCount ?? 0) }}
                                </span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-red-400"
                                    style="width: {{ ($criteriaCount ?? 0) > 0 ? min(100, (($inactiveCriteriaCount ?? 0) / $criteriaCount) * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Aktivitas Terbaru
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Data terbaru dari pengguna, criteria, atau master data sistem.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @forelse ($recentActivities ?? [] as $activity)
                            <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-emerald-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-slate-800">
                                            {{ $activity['title'] ?? 'Aktivitas sistem' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $activity['description'] ?? 'Data baru saja diperbarui.' }}
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 text-xs text-slate-400">
                                    {{ $activity['time'] ?? '-' }}
                                </span>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center">
                                <p class="text-sm font-medium text-slate-600">
                                    Belum ada aktivitas terbaru.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Aktivitas akan muncul setelah ada perubahan data.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Aksi Cepat Admin
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Shortcut untuk mengelola data utama sistem.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="{{ Route::has('user.create') ? route('user.create') : '#' }}"
                        class="flex items-center gap-3 rounded-2xl bg-emerald-600 px-5 py-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3M5 20a6 6 0 1112 0H5z" />
                        </svg>
                        Tambah User
                    </a>

                    <a href="{{ Route::has('collector.create') ? route('collector.create') : '#' }}"
                        class="flex items-center gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-5 py-4 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-100 hover:shadow-md">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3zm6 8v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1" />
                        </svg>
                        Tambah Collector
                    </a>

                    <a href="{{ Route::has('criteria.create') ? route('criteria.create') : '#' }}"
                        class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-semibold text-amber-700 shadow-sm transition hover:bg-amber-100 hover:shadow-md">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Criteria
                    </a>

                    <a href="{{ Route::has('tim-explorasi.create') ? route('tim-explorasi.create') : '#' }}"
                        class="flex items-center gap-3 rounded-2xl border border-violet-200 bg-violet-50 px-5 py-4 text-sm font-semibold text-violet-700 shadow-sm transition hover:bg-violet-100 hover:shadow-md">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                        Tambah Tim
                    </a>
                </div>
            </div>
        </div>
@endsection
