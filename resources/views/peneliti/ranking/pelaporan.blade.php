@extends('layout.admin')

@section('content')
    <div class="px-4 py-6 mx-auto">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Pelaporan PROMETHEE II
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Histori hasil kalkulasi perankingan net flow, leaving flow, dan entering flow
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peneliti.ranking.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600
                        bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i class="fa-solid fa-chart-bar text-xs"></i>
                    Perankingan
                </a>

                @if ($pelaporan->total() > 0)
                    <a href="{{ route('peneliti.pelaporan.export') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                            bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                        <i class="fa-solid fa-file-csv text-xs"></i>
                        Export CSV
                    </a>
                @endif
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-4">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <form action="{{ route('peneliti.pelaporan.index') }}" method="GET"
                    class="flex flex-wrap items-end gap-3">

                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Nomor Akses
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor akses..."
                            class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                   focus:border-transparent bg-white w-48">
                    </div>

                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Tanggal
                        </label>
                        <div class="flex items-center gap-1">
                            <input type="date" name="dari" value="{{ request('dari') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white">
                            <span class="text-gray-400 text-xs">–</span>
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[var(--flora-teal)]
                                   rounded-lg hover:bg-[var(--flora-moss)] transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
                        </button>
                        @if (request()->hasAny(['search', 'dari', 'sampai']))
                            <a href="{{ route('peneliti.pelaporan.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100
                                       rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                                <i class="fa-solid fa-xmark text-xs mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card Sub-header --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">
                        Data Pelaporan PROMETHEE II
                    </p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-400">
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
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Ranking</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Tanaman</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">ID Inspeksi</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Leaving Flow (Φ+)
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Entering Flow (Φ−)
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Net Flow (Φ)
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Dicatat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pelaporan as $index => $item)
                            @php
                                $nomorAkses = $item->tanaman?->NomorAkses ?? '-';
                                $tanamanInfo = $item->tanaman?->tanamanPenerimaan?->tanamanInfo;
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition border-b border-gray-100">

                                {{-- No --}}
                                <td class="px-5 py-4 text-gray-400 text-xs">
                                    {{ $pelaporan->firstItem() + $index }}
                                </td>

                                {{-- Ranking --}}
                                <td class="px-5 py-4">
                                    @if ($item->ranking === 1)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">
                                            <i class="fa-solid fa-trophy text-[10px]"></i> #{{ $item->ranking }}
                                        </span>
                                    @elseif ($item->ranking === 2)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                            <i class="fa-solid fa-medal text-[10px]"></i> #{{ $item->ranking }}
                                        </span>
                                    @elseif ($item->ranking === 3)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-500 border border-orange-200">
                                            <i class="fa-solid fa-medal text-[10px]"></i> #{{ $item->ranking }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-100">
                                            #{{ $item->ranking }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Tanaman --}}
                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-medium text-gray-700">{{ $nomorAkses }}</p>
                                        <p class="text-xs text-gray-400 italic">{{ $tanamanInfo?->scientific_name ?? '-' }}</p>
                                    </div>
                                </td>

                                {{-- ID Inspeksi --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs
                                        bg-gray-100 text-gray-500 border border-gray-200 font-mono">
                                        <a href="{{ route('peneliti.inspeksi.show', $item->inspeksiTanaman?->inspeksi_id ?? '#') }}"
                                            class="hover:underline">
                                            #{{ $item->inspeksiTanaman?->inspeksi_id ?? '-' }}
                                        </a>
                                    </span>
                                </td>

                                {{-- Leaving Flow --}}
                                <td class="px-5 py-4 text-right">
                                    <span class="font-mono text-sm text-blue-600">
                                        {{ number_format($item->leaving_flow, 4) }}
                                    </span>
                                </td>

                                {{-- Entering Flow --}}
                                <td class="px-5 py-4 text-right">
                                    <span class="font-mono text-sm text-purple-600">
                                        {{ number_format($item->entering_flow, 4) }}
                                    </span>
                                </td>

                                {{-- Net Flow --}}
                                <td class="px-5 py-4 text-right">
                                    @if ($item->net_flow > 0)
                                        <span class="inline-flex items-center justify-end gap-1 font-mono text-sm font-semibold text-emerald-600">
                                            <i class="fa-solid fa-arrow-up text-[10px]"></i>
                                            {{ number_format($item->net_flow, 4) }}
                                        </span>
                                    @elseif ($item->net_flow < 0)
                                        <span class="inline-flex items-center justify-end gap-1 font-mono text-sm font-semibold text-red-500">
                                            <i class="fa-solid fa-arrow-down text-[10px]"></i>
                                            {{ number_format(abs($item->net_flow), 4) }}
                                        </span>
                                    @else
                                        <span class="font-mono text-sm text-gray-400">0.0000</span>
                                    @endif
                                </td>

                                {{-- Dicatat --}}
                                <td class="px-5 py-4">
                                    <div>
                                        <p class="text-xs text-gray-600">{{ $item->user?->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $item->created_at?->format('d M Y') }}</p>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-chart-column text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data pelaporan</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                Data akan muncul setelah tanaman ditambahkan ke koleksi dari halaman
                                                <a href="{{ route('peneliti.ranking.index') }}" class="text-emerald-600 hover:underline">perankingan</a>.
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer & Pagination --}}
            @if ($pelaporan->hasPages() || $pelaporan->total() > 0)
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/60 flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-400">
                        Total <span class="font-medium text-gray-600">{{ $pelaporan->total() }}</span> data pelaporan
                    </p>
                    @if ($pelaporan->hasPages())
                        <div class="flora-pagination">
                            {{ $pelaporan->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

    </div>
@endsection
