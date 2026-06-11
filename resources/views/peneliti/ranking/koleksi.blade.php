@extends('layout.admin')

@section('content')
    @if (session('success'))
        <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="px-4 py-6 mx-auto" x-data="koleksiManager()">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Koleksi Kebun Raya
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Tanaman terpilih dari hasil perankingan PROMETHEE II
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('peneliti.ranking.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600
                        bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali ke Perankingan
                </a>

                @if ($koleksi->count() > 0)
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                                bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                            <i class="fa-solid fa-file-export text-xs"></i>
                            Export Data
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-xl shadow-lg z-10 overflow-hidden">
                            <div class="px-3 py-2 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Format Export</p>
                            </div>
                            <a href="{{ route('peneliti.koleksi.export') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <i class="fa-solid fa-file-csv text-emerald-500 w-4"></i>
                                Export Semua (CSV)
                            </a>
                            <button type="button" @click="exportSelected()"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                                :class="selectedIds.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                :disabled="selectedIds.length === 0">
                                <i class="fa-solid fa-filter text-emerald-500 w-4"></i>
                                Export Terpilih (CSV)
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Toolbar (select mode) --}}
        <div x-show="selectedIds.length > 0" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mb-4 flex items-center justify-between gap-4 px-5 py-3 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-center gap-2.5 text-sm text-red-700">
                <i class="fa-solid fa-check-circle text-red-500"></i>
                <span><span x-text="selectedIds.length" class="font-bold"></span> item dipilih</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="clearAll()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-500
                        bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                    Batal
                </button>
                <button type="button" @click="exportSelected()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-emerald-700
                        bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
                    <i class="fa-solid fa-file-export text-[10px]"></i>
                    Export Terpilih
                </button>
                <button type="button" @click="deleteSelected()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600
                        bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                    <i class="fa-solid fa-trash text-[10px]"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>

        {{-- Export form (hidden) --}}
        <form id="export-form" method="GET" action="{{ route('peneliti.koleksi.export') }}" class="hidden">
            <div id="export-inputs"></div>
        </form>

        {{-- Table Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card Sub-header --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if ($koleksi->count() > 0)
                        <label class="flex items-center gap-2 cursor-pointer group" title="Pilih semua">
                            <input type="checkbox" @change="toggleAll($event)"
                                class="w-4 h-4 rounded border-gray-300 text-emerald-600 cursor-pointer">
                            <span class="text-xs text-gray-400 group-hover:text-gray-600 transition-colors">Pilih Semua</span>
                        </label>
                    @endif
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">
                        Daftar Koleksi Tanaman
                    </p>
                </div>
                <span class="text-xs text-gray-400">{{ $koleksi->count() }} tanaman</span>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8"></th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Nomor Akses</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Nama Ilmiah</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Nama Lokal</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Suku</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Habitus</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Locality</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Vak No</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">Ditambahkan</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($koleksi as $index => $item)
                            @php
                                $nomorAkses = $item->tanaman?->NomorAkses ?? '-';
                                $penerimaanTanaman = $item->tanaman?->tanamanPenerimaan;
                                $tanamanInfo = $penerimaanTanaman?->tanamanInfo;
                            @endphp

                            <tr class="hover:bg-gray-50/60 transition border-b border-gray-100 group"
                                :class="selectedIds.includes({{ $item->id }}) ? 'bg-emerald-50/30' : ''">

                                {{-- Checkbox --}}
                                <td class="px-5 py-4">
                                    <input type="checkbox"
                                        class="w-4 h-4 rounded border-gray-300 text-emerald-600 cursor-pointer"
                                        :checked="selectedIds.includes({{ $item->id }})"
                                        @change="toggleItem({{ $item->id }}, $event)">
                                </td>

                                {{-- No --}}
                                <td class="px-5 py-4 text-gray-400 text-xs">{{ $index + 1 }}</td>

                                {{-- Nomor Akses --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs
                                        bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono font-semibold">
                                        {{ $nomorAkses }}
                                    </span>
                                </td>

                                {{-- Nama Ilmiah --}}
                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-semibold italic text-gray-700">
                                            {{ $tanamanInfo?->scientific_name ?? '-' }}
                                        </p>
                                        @if ($tanamanInfo?->author_name ?? false)
                                            <p class="text-xs text-gray-400">{{ $tanamanInfo->author_name }}</p>
                                        @endif
                                    </div>
                                </td>

                                {{-- Nama Lokal --}}
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $tanamanInfo?->nama_lokal ?? '-' }}
                                </td>

                                {{-- Suku --}}
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $tanamanInfo?->suku ?? '-' }}
                                </td>

                                {{-- Habitus --}}
                                <td class="px-5 py-4 text-gray-600 text-xs font-mono">
                                    {{ $penerimaanTanaman?->habitus ?? '-' }}
                                </td>

                                {{-- Locality --}}
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $penerimaanTanaman?->locality ?? '-' }}
                                </td>

                                {{-- Vak No --}}
                                <td class="px-5 py-4 text-gray-600 text-xs font-mono">
                                    {{ $penerimaanTanaman?->vak_no ?? '-' }}
                                </td>

                                {{-- Ditambahkan --}}
                                <td class="px-5 py-4">
                                    <div>
                                        <p class="text-xs text-gray-600">{{ $item->user?->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $item->created_at?->format('d M Y') }}</p>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                        <form method="POST" action="{{ route('peneliti.koleksi.destroy', $item->id) }}"
                                            class="form-delete-koleksi">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                title="Hapus dari koleksi">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-leaf text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada koleksi kebun raya</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                Pilih tanaman dari halaman
                                                <a href="{{ route('peneliti.ranking.index') }}" class="text-emerald-600 hover:underline">perankingan</a>
                                                untuk ditambahkan ke koleksi.
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            @if ($koleksi->count() > 0)
                <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/60 flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-400">
                        <span class="font-medium text-gray-600">{{ $koleksi->count() }}</span> tanaman dalam koleksi kebun raya
                    </p>
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
        <script>
            function koleksiManager() {
                return {
                    selectedIds: [],

                    toggleItem(id, event) {
                        if (event.target.checked) {
                            this.selectedIds = [...this.selectedIds, id];
                        } else {
                            this.selectedIds = this.selectedIds.filter(i => i !== id);
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
                        this.selectedIds = [];
                        document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
                    },

                    exportSelected() {
                        if (this.selectedIds.length === 0) return;

                        const form = document.getElementById('export-form');
                        const container = document.getElementById('export-inputs');
                        container.innerHTML = '';

                        this.selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            container.appendChild(input);
                        });

                        form.submit();
                    },

                    deleteSelected() {
                        if (this.selectedIds.length === 0) return;

                        Swal.fire({
                            title: 'Hapus dari Koleksi?',
                            text: `${this.selectedIds.length} tanaman akan dihapus dari koleksi kebun raya.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Delete one by one using existing destroy routes
                                this.selectedIds.forEach(id => {
                                    const form = document.querySelector(`.form-delete-koleksi[data-id="${id}"]`);
                                    if (form) form.submit();
                                });
                            }
                        });
                    }
                };
            }

            // Delete confirmation
            document.querySelectorAll('.form-delete-koleksi').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus dari Koleksi?',
                        text: 'Tanaman ini akan dihapus dari koleksi kebun raya.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
