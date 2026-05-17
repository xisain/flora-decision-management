@extends('layout.admin')
@section('content')

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="mb-4 flex gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
            </div>
            <div>
                <p class="font-semibold text-red-700 mb-1">Terdapat {{ $errors->count() }} Kesalahan</p>
                <ul class="list-disc list-inside space-y-0.5 text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="px-4 py-6 mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">Tim Explorasi</h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">Kelola Tim Explorasi</p>
            </div>
            <a href="{{ route('tim-explorasi.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Tim
            </a>
        </div>

        {{-- Table Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Search bar --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <form method="GET" action="{{ route('tim-explorasi.index') }}">
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari nama tim atau lokasi..."
                            class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white placeholder-gray-400 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)] focus:border-transparent transition">
                        @if ($search)
                            <a href="{{ route('tim-explorasi.index') }}"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Empty state --}}
            @if ($team->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-users text-gray-400 text-xl"></i>
                    </div>
                    @if ($search)
                        <p class="text-sm font-medium text-gray-500">Tidak ada hasil untuk "{{ $search }}"</p>
                        <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                    @else
                        <p class="text-sm font-medium text-gray-500">Belum ada tim explorasi</p>
                        <p class="text-xs text-gray-400 mt-1">Klik "Tambah Tim" untuk membuat tim baru</p>
                    @endif
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Nama Tim
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Lokasi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">Anggota
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>

                    @foreach ($team as $i => $tim)
                        <tbody x-data="{ open: false }">

                            {{-- Row utama --}}
                            <tr class="hover:bg-emerald-50/60 transition border-b border-gray-100 group">
                                <td class="px-5 py-4 text-gray-400 text-xs">
                                    {{ $team->firstItem() + $i }}
                                </td>

                                {{-- Nama Tim --}}
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">{{ $tim->nama_tim }}</p>
                                    @if ($tim->deskripsi_explorasi)
                                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">
                                            {{ $tim->deskripsi_explorasi }}</p>
                                    @endif
                                </td>

                                {{-- Lokasi --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-600">
                                        <i class="fa-solid fa-location-dot text-[var(--flora-moss)] text-xs"></i>
                                        {{ $tim->lokasi_explorasi ?? '-' }}
                                    </span>
                                </td>

                                {{-- Anggota + toggle --}}
                                <td class="px-5 py-4">
                                    <button type="button" @click="open = !open"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-[var(--flora-moss)] hover:opacity-75 transition">
                                        <i class="fa-solid fa-users text-xs"></i>
                                        {{ $tim->anggotaTimExplorasi->count() }} Anggota
                                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200"
                                            :class="open ? 'rotate-180' : ''"></i>
                                    </button>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('tim-explorasi.edit', $tim->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('tim-explorasi.destroy', $tim->id) }}" method="POST" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn-delete p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Accordion anggota --}}
                            <tr x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" style="display: none;">
                                <td colspan="5" class="px-5 pb-4 pt-1 bg-gray-50/50">
                                    <div class="rounded-xl border border-gray-100 overflow-hidden">

                                        @if ($tim->anggotaTimExplorasi->isEmpty())
                                            <div class="px-5 py-4 text-sm text-gray-400 text-center">
                                                Belum ada anggota
                                            </div>
                                        @else
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="bg-gray-50 border-b border-gray-100 text-left">
                                                        <th
                                                            class="px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                                            Nama</th>
                                                        <th
                                                            class="px-4 py-2.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                                            Peran</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    @foreach ($tim->anggotaTimExplorasi as $anggota)
                                                        @php
                                                            $collector = $anggota->Collector;
                                                            $nama =
                                                                $collector?->user?->name ??
                                                                ($collector?->full_name ?? 'Unknown');
                                                            $initial = $collector?->initial_collector_name ?? '?';
                                                        @endphp
                                                        <tr class="hover:bg-gray-50/60 transition">
                                                            <td class="px-4 py-3 text-gray-700 font-medium">
                                                                <div class="flex items-center gap-2.5">
                                                                    <span
                                                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[var(--flora-moss)]/10 text-[var(--flora-moss)] text-xs font-bold shrink-0">
                                                                        {{ strtoupper(substr($initial, 0, 2)) }}
                                                                    </span>
                                                                    <span>
                                                                        {{ $nama }}
                                                                        <span
                                                                            class="text-gray-400 text-xs font-normal">({{ $initial }})</span>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[var(--flora-moss)]/10 text-[var(--flora-moss)]">
                                                                    {{ $anggota->Peran }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    @endforeach

                </table>

                {{-- Pagination --}}
                @if ($team->hasPages())
                    <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/60 flex items-center justify-between gap-4">
                        <p class="text-xs text-gray-400">
                            Menampilkan {{ $team->firstItem() }}–{{ $team->lastItem() }}
                            dari {{ $team->total() }} tim
                        </p>
                        <div class="flora-pagination">
                            {{ $team->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
 @push('scripts')
        <script>
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Tim ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#ef4444',
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
