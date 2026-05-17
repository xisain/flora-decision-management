@extends('layout.admin')

@section('content')
    @php
        $totalInspeksiCount =
            ($checkupCount ?? 0) + ($labelingCount ?? 0) + ($aklimatisasiCount ?? 0) + ($evaluasiCount ?? 0);

        $stats = [
            [
                'title' => 'Total Penerimaan',
                'value' => $dataPenerimaaanCount ?? 0,
                'icon' => 'box',
                'iconBg' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600',
                'badge' => 'Aktif',
                'badgeClass' => 'bg-emerald-50 text-emerald-700',
            ],
            [
                'title' => 'Total Tanaman Diterima',
                'value' => $dataPenerimaanTanamanCount ?? 0,
                'icon' => 'leaf',
                'iconBg' => 'bg-sky-50',
                'iconColor' => 'text-sky-600',
                'badge' => 'Data',
                'badgeClass' => 'bg-sky-50 text-sky-700',
            ],
            [
                'title' => 'Total Penyemaian',
                'value' => $penyemaianTanamanCount ?? 0,
                'icon' => 'seed',
                'iconBg' => 'bg-amber-50',
                'iconColor' => 'text-amber-600',
                'badge' => 'Semai',
                'badgeClass' => 'bg-amber-50 text-amber-700',
            ],
            [
                'title' => 'Total Inspeksi',
                'value' => $totalInspeksiCount,
                'icon' => 'clipboard',
                'iconBg' => 'bg-violet-50',
                'iconColor' => 'text-violet-600',
                'badge' => 'Tahapan',
                'badgeClass' => 'bg-violet-50 text-violet-700',
            ],
        ];

        $stages = [
            [
                'title' => 'Checkup',
                'value' => $checkupCount ?? 0,
                'description' => 'Tanaman masuk tahap pemeriksaan awal',
                'color' => 'bg-emerald-500',
            ],
            [
                'title' => 'Labeling',
                'value' => $labelingCount ?? 0,
                'description' => 'Tanaman sudah masuk proses pelabelan',
                'color' => 'bg-sky-500',
            ],
            [
                'title' => 'Aklimatisasi',
                'value' => $aklimatisasiCount ?? 0,
                'description' => 'Tanaman sedang proses adaptasi',
                'color' => 'bg-amber-500',
            ],
            [
                'title' => 'Evaluasi',
                'value' => $evaluasiCount ?? 0,
                'description' => 'Tanaman masuk tahap penilaian akhir',
                'color' => 'bg-violet-500',
            ],
        ];

        $tasks = [
            [
                'title' => 'Tanaman belum masuk penyemaian',
                'count' => $belumPenyemaianCount ?? 0,
                'level' => 'URGENT',
                'class' => 'bg-red-50 border-red-100 text-red-700',
                'badge' => 'bg-red-100 text-red-700',
            ],
            [
                'title' => 'Tanaman belum diinspeksi',
                'count' => $belumInspeksiCount ?? 0,
                'level' => 'MEDIUM',
                'class' => 'bg-amber-50 border-amber-100 text-amber-700',
                'badge' => 'bg-amber-100 text-amber-700',
            ],
            [
                'title' => 'Inspeksi belum lengkap nilai kriterianya',
                'count' => $inspeksiBelumLengkapCount ?? 0,
                'level' => 'LOW',
                'class' => 'bg-slate-50 border-slate-100 text-slate-700',
                'badge' => 'bg-slate-200 text-slate-700',
            ],
        ];
    @endphp
    <div class="mx-auto  px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-4">
        {{-- Header --}}
            <div class="flex flex-col gap-1">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-600">
                    Halo, {{ Auth::user()->name ?? 'Peneliti' }}!
                </p>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                            Dashboard Peneliti
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Pantau ringkasan penerimaan, penyemaian, inspeksi, dan evaluasi tanaman.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats as $stat)
                    <div
                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">

                        <div class="flex items-start justify-between gap-3">
                            <div class="flex flex-col gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $stat['iconBg'] }}">
                                    @switch($stat['icon'])
                                        @case('box')
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                                            </svg>
                                        @break

                                        @case('leaf')
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 3C7 3 3 7.03 3 12s4 9 9 9 9-4.03 9-9-4-9-9-9zm0 0v18M3 12h18" />
                                            </svg>
                                        @break

                                        @case('seed')
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-3.866 0-7 2.686-7 6 0 2.761 2.239 5 5 5 3.314 0 6-3.134 6-7 0-2.21-1.79-4-4-4z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8V4m0 0c2.5 0 4 1.5 4 4" />
                                            </svg>
                                        @break

                                        @default
                                            <svg class="h-5 w-5 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                                </div>
                            </div>

                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $stat['badgeClass'] }}">
                                {{ $stat['badge'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Progress Tahapan --}}
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
                @foreach ($stages as $stage)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $stage['title'] }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $stage['description'] }}
                                </p>
                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-50">
                                <span class="text-sm font-bold text-slate-800">
                                    {{ number_format($stage['value']) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full {{ $stage['color'] }}"
                                style="width: {{ $totalInspeksiCount > 0 ? min(100, ($stage['value'] / $totalInspeksiCount) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Middle Section --}}
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                {{-- Pekerjaan yang Perlu Diproses --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Pekerjaan yang Perlu Diproses
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Ringkasan pekerjaan yang membutuhkan perhatian.
                            </p>
                        </div>

                        <a href="#"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach ($tasks as $task)
                            <div
                                class="flex items-center justify-between gap-4 rounded-xl border px-4 py-3 {{ $task['class'] }}">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/70">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-slate-800">
                                            {{ $task['count'] }} {{ $task['title'] }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Periksa data untuk melanjutkan proses.
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-bold {{ $task['badge'] }}">
                                    {{ $task['level'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Ranking Tanaman Terbaik --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Ranking Tanaman Terbaik
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Berdasarkan hasil evaluasi dan nilai net flow.
                            </p>
                        </div>

                        <a href="{{ route('detail.rank') }}"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                            Detail
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($rankingTanaman ?? [] as $item)
                            <div>
                                <div class="mb-1.5 flex items-center justify-between gap-3">
                                    <div class="flex min-w-0 items-center gap-2">
                                        <span class="text-xs font-bold text-slate-400">
                                            {{ $item['ranking'] ?? '-' }}.
                                        </span>

                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-700">
                                                {{ $item['nomor_akses'] ?? '-' }}
                                            </p>

                                            <p class="truncate text-xs text-slate-400">
                                                {{ $item['scientific_name'] ?? 'Tanaman' }}

                                                @if (!empty($item['author_name']))
                                                    · {{ $item['author_name'] }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <span class="shrink-0 text-xs text-slate-400">
                                        Net Flow {{ number_format($item['net_flow'] ?? 0, 3) }}
                                    </span>
                                </div>

                                <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-emerald-500"
                                        style="width: {{ min(100, abs($item['net_flow'] ?? 0) * 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center">
                                <p class="text-sm font-medium text-slate-600">
                                    Belum ada data ranking tanaman.
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Data akan muncul setelah evaluasi tanaman selesai.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Aksi Cepat
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Akses cepat untuk menambahkan data baru.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('peneliti.penerimaan.create') }}"
                        class="flex items-center gap-3 rounded-2xl bg-emerald-600 px-5 py-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                        </svg>
                        Tambah Penerimaan
                    </a>

                    <a href="{{ route('peneliti.penyemaian.create') }}"
                        class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100 hover:shadow-md">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3C7 3 3 7.03 3 12s4 9 9 9 9-4.03 9-9-4-9-9-9zm0 0v18M3 12h18" />
                        </svg>
                        Tambah Penyemaian
                    </a>

                    <a href="{{ route('peneliti.inspeksi.create') }}"
                        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:shadow-md">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Tambah Inspeksi
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
