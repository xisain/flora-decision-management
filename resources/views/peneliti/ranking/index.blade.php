@extends('layout.admin')

@section('content')
    @if ($incomplete)
        <div class="mb-6 border border-amber-200 bg-amber-50 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 flex items-start gap-3">
                <div class="shrink-0 mt-0.5 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-800">Data kriteria belum lengkap</p>
                    <p class="text-xs text-amber-600 mt-0.5">
                        Perankingan tidak dapat dihitung. Lengkapi nilai kriteria berikut terlebih dahulu:
                    </p>
                    <ul class="mt-3 space-y-1.5">
                        @foreach ($warnings as $warning)
                            <li class="flex items-start gap-2 text-xs text-amber-700">
                                <i class="fa-solid fa-seedling text-amber-400 mt-0.5 shrink-0"></i>
                                <span>
                                    <span class="font-medium">Tanaman ID {{ $warning['tanaman_id'] }}</span>
                                    &mdash; kriteria belum diisi:
                                    <span class="font-mono bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">
                                        {{ implode(', ', $warning['missing_criteria']) }}
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="px-4 py-6 mx-auto">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Perankingan Tanaman
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Hasil perankingan menggunakan metode PROMETHEE II
                </p>
            </div>
            <div
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-[var(--flora-moss)]
                    bg-[var(--flora-teal)]/10 border border-[var(--flora-teal)]/20 rounded-lg">
                <i class="fa-solid fa-chart-bar text-xs"></i>
                {{ count($service) }} Alternatif
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card Sub-header / Legend --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">
                    Hasil Kalkulasi Net Flow
                </p>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span> Leaving Flow (Φ+)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-purple-400 inline-block"></span> Entering Flow (Φ−)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span> Net Flow (Φ)
                    </span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Ranking
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Tanaman
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">ID
                                Inspeksi</th>
                            <th
                                class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Leaving Flow (Φ+)</th>
                            <th
                                class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Entering Flow (Φ−)</th>
                            <th
                                class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Net Flow (Φ)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($service as $index => $item)
                            @php
                                $tanamanId = is_array($item) ? $item['tanaman_id'] : $item->tanaman_id;
                                $inspeksiId = is_array($item)
                                    ? $item['inspeksi_tanaman_id']
                                    : $item->inspeksi_tanaman_id;
                                $leavingFlow = is_array($item) ? $item['leaving_flow'] : $item->leaving_flow;
                                $enteringFlow = is_array($item) ? $item['entering_flow'] : $item->entering_flow;
                                $netFlow = is_array($item) ? $item['net_flow'] : $item->net_flow;
                                $ranking = is_array($item) ? $item['ranking'] : $item->ranking;

                                $nomorTanaman =
                                    $inspeksiMap[$tanamanId]?->tanaman?->NomorAkses ?? 'Tanaman #' . $tanamanId;
                                $namaScientific =
                                    $inspeksiMap[$tanamanId]?->tanaman?->tanamanPenerimaan?->tanamanInfo
                                        ?->scientific_name;

                            @endphp

                            <tr class="hover:bg-gray-50/60 transition border-b border-gray-100 group">

                                {{-- No urut --}}
                                <td class="px-5 py-4 text-gray-400 text-xs">{{ $index + 1 }}</td>

                                {{-- Ranking Badge --}}
                                <td class="px-5 py-4">
                                    @if ($ranking === 1)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">
                                            <i class="fa-solid fa-trophy text-[10px]"></i> #{{ $ranking }}
                                        </span>
                                    @elseif ($ranking === 2)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                            <i class="fa-solid fa-medal text-[10px]"></i> #{{ $ranking }}
                                        </span>
                                    @elseif ($ranking === 3)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-500 border border-orange-200">
                                            <i class="fa-solid fa-medal text-[10px]"></i> #{{ $ranking }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-100">
                                            #{{ $ranking }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Tanaman --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-[var(--flora-teal)]/10 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-seedling text-[var(--flora-teal)] text-[10px]"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-700">{{ $nomorTanaman }}</p>
                                            <p class="text-xs text-gray-400">{{ $namaScientific }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- ID Inspeksi --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs
                                             bg-gray-100 text-gray-500 border border-gray-200 font-mono">
                                        <a href="{{ route('peneliti.inspeksi.show', $inspeksiIdMap[$inspeksiId] ?? '#') }}"
                                            class="hover:underline">
                                            #{{ $inspeksiId }}
                                        </a>
                                    </span>
                                </td>

                                {{-- Leaving Flow --}}
                                <td class="px-5 py-4 text-right">
                                    <span class="font-mono text-sm text-blue-600">
                                        {{ number_format($leavingFlow, 4) }}
                                    </span>
                                </td>

                                {{-- Entering Flow --}}
                                <td class="px-5 py-4 text-right">
                                    <span class="font-mono text-sm text-purple-600">
                                        {{ number_format($enteringFlow, 4) }}
                                    </span>
                                </td>

                                {{-- Net Flow --}}
                                <td class="px-5 py-4 text-right">
                                    @if ($netFlow > 0)
                                        <span
                                            class="inline-flex items-center justify-end gap-1 font-mono text-sm font-semibold text-emerald-600">
                                            <i class="fa-solid fa-arrow-up text-[10px]"></i>
                                            {{ number_format($netFlow, 4) }}
                                        </span>
                                    @elseif ($netFlow < 0)
                                        <span
                                            class="inline-flex items-center justify-end gap-1 font-mono text-sm font-semibold text-red-500">
                                            <i class="fa-solid fa-arrow-down text-[10px]"></i>
                                            {{ number_format(abs($netFlow), 4) }}
                                        </span>
                                    @else
                                        <span class="font-mono text-sm text-gray-400">0.0000</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-seedling text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data perankingan</p>
                                            <p class="text-xs mt-1 text-gray-400">Pastikan data inspeksi dan kriteria sudah
                                                tersedia</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table Footer — summary --}}
            @if (count($service) > 0)
                @php
                    $serviceCol = collect($service);
                    $bestItem = $serviceCol->firstWhere('ranking', 1);
                    $bestNetFlow = is_array($bestItem) ? $bestItem['net_flow'] ?? null : $bestItem->net_flow ?? null;
                    $bestId = is_array($bestItem) ? $bestItem['tanaman_id'] ?? '-' : $bestItem->tanaman_id ?? '-';
                    $bestInspeksi = \App\Models\InspeksiTanaman::with('tanaman')->find($bestId);
                    $bestNama = $bestInspeksi?->tanaman?->nama ?? 'Tanaman #' . $bestId;
                @endphp
                <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/60 flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-400">
                        Total <span class="font-medium text-gray-600">{{ count($service) }}</span> alternatif dievaluasi
                    </p>
                    @if ($bestNetFlow !== null)
                        <p class="text-xs text-gray-400">
                            Terbaik:
                            <span class="font-medium text-gray-600">{{ $bestNama }}</span>
                            <span
                                class="font-mono font-semibold text-emerald-600 ml-1">+{{ number_format($bestNetFlow, 4) }}</span>
                        </p>
                    @endif
                </div>
            @endif

        </div>

    </div>

@endsection
