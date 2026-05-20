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

    @if (session('success'))
        <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="px-4 py-6 mx-auto" x-data="rankingSelector()">

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
            <div class="flex items-center gap-3">
                <a href="{{ route('peneliti.koleksi.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-[var(--flora-moss)]
                        bg-white border border-[var(--flora-teal)]/30 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                    <i class="fa-solid fa-seedling text-xs"></i>
                    Koleksi Kebun Raya
                </a>
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-[var(--flora-moss)]
                        bg-[var(--flora-teal)]/10 border border-[var(--flora-teal)]/20 rounded-lg">
                    <i class="fa-solid fa-chart-bar text-xs"></i>
                    {{ count($service) }} Alternatif
                </div>
            </div>
        </div>

        {{-- Action Toolbar (shows when items selected) --}}
        <div x-show="selectedCount > 0" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mb-4 flex items-center justify-between gap-4 px-5 py-3 bg-emerald-50 border border-emerald-200 rounded-xl">
            <div class="flex items-center gap-2.5 text-sm text-emerald-700">
                <i class="fa-solid fa-check-circle text-emerald-500"></i>
                <span><span x-text="selectedCount" class="font-bold"></span> tanaman dipilih</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="clearAll()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-500
                        bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                    Batal Pilih
                </button>
                <button type="button" @click="submitKoleksi()"
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-medium text-white
                        bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors">
                    <i class="fa-solid fa-seedling text-[10px]"></i>
                    Tambah ke Koleksi Kebun Raya
                </button>
            </div>
        </div>

        {{-- Hidden form to submit selected plants --}}
        <form id="koleksi-form" method="POST" action="{{ route('peneliti.koleksi.store') }}" class="hidden">
            @csrf
            <div id="koleksi-inputs"></div>
        </form>

        {{-- Table Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card Sub-header / Legend --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Select All Checkbox --}}
                    @if(count($service) > 0)
                    <label class="flex items-center gap-2 cursor-pointer group" title="Pilih semua">
                        <input type="checkbox" id="select-all" @change="toggleAll($event)"
                            class="w-4 h-4 rounded border-gray-300 text-emerald-600 cursor-pointer">
                        <span class="text-xs text-gray-400 group-hover:text-gray-600 transition-colors">Pilih Semua</span>
                    </label>
                    @endif
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">
                        Hasil Kalkulasi Net Flow
                    </p>
                </div>
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
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8"></th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Ranking</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Tanaman</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">ID Inspeksi</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Leaving Flow (Φ+)</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
                                Entering Flow (Φ−)</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">
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

                                $realTanamanId = $inspeksiMap[$tanamanId]?->tanaman?->id ?? $tanamanId;
                            @endphp

                            <tr class="hover:bg-gray-50/60 transition border-b border-gray-100 group"
                                :class="isSelected({{ $inspeksiId }}) ? 'bg-emerald-50/40' : ''">

                                {{-- Checkbox --}}
                                <td class="px-5 py-4">
                                    <input type="checkbox"
                                        class="w-4 h-4 rounded border-gray-300 text-emerald-600 cursor-pointer"
                                        :checked="isSelected({{ $inspeksiId }})"
                                        @change="toggle({{ $inspeksiId }}, {
                                            tanaman_id: {{ $realTanamanId }},
                                            inspeksi_tanaman_id: {{ $inspeksiId }},
                                            ranking: {{ $ranking }},
                                            net_flow: {{ $netFlow }},
                                            leaving_flow: {{ $leavingFlow }},
                                            entering_flow: {{ $enteringFlow }}
                                        }, $event)">
                                </td>

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
                                            #{{ $inspeksiIdMap[$inspeksiId] ?? '#' }}
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
                                <td colspan="8" class="px-5 py-16 text-center">
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

    @push('scripts')
        <script>
            function rankingSelector() {
                return {
                    selected: {},

                    get selectedCount() {
                        return Object.keys(this.selected).length;
                    },

                    isSelected(inspeksiId) {
                        return !!this.selected[inspeksiId];
                    },

                    toggle(inspeksiId, data, event) {
                        if (event.target.checked) {
                            this.selected[inspeksiId] = data;
                        } else {
                            delete this.selected[inspeksiId];
                            this.selected = { ...this.selected };
                        }
                    },

                    toggleAll(event) {
                        const checkboxes = document.querySelectorAll('tbody input[type=checkbox]');
                        checkboxes.forEach(cb => {
                            cb.checked = event.target.checked;
                            cb.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                    },

                    clearAll() {
                        this.selected = {};
                        document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
                    },

                    submitKoleksi() {
                        if (this.selectedCount === 0) return;

                        const form = document.getElementById('koleksi-form');
                        const container = document.getElementById('koleksi-inputs');
                        container.innerHTML = '';

                        const entries = Object.values(this.selected);
                        entries.forEach((item, i) => {
                            const fields = ['tanaman_id', 'inspeksi_tanaman_id', 'ranking', 'net_flow', 'leaving_flow', 'entering_flow'];
                            fields.forEach(field => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = `selected[${i}][${field}]`;
                                input.value = item[field];
                                container.appendChild(input);
                            });
                        });

                        Swal.fire({
                            title: 'Tambah ke Koleksi Kebun Raya?',
                            text: `${this.selectedCount} tanaman akan ditambahkan ke koleksi kebun raya.`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Tambahkan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#4d7c5a',
                            cancelButtonColor: '#6b7280',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
@endsection
