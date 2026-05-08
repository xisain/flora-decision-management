{{--
|=============================================================================
| TEMPLATE: INDEX VIEW — STANDAR FDM BULUNGAN
|=============================================================================
|
| CARA PAKAI:
|   1. Salin file ini ke folder modul yang sesuai (misal: admin/nama-modul/index.blade.php)
|   2. Ganti semua teks [DALAM KURUNG] sesuai modul
|   3. Hapus bagian yang tidak diperlukan (misal: search bar, accordion, filter tanggal)
|   4. Hapus komentar panduan ini setelah selesai
|
| PANDUAN CEPAT:
|   - [NamaModul]      : Nama modul yang mudah dibaca, contoh: "Tim Explorasi"
|   - [namaModul]      : Nama variabel controller, contoh: $team, $collector
|   - [route.prefix]   : Prefix route, contoh: 'tim-explorasi', 'collector'
|   - [fa-icon]        : FontAwesome icon untuk empty state, contoh: fa-users, fa-seedling
|   - [N]              : Jumlah kolom tabel (untuk colspan empty state)
|
|=============================================================================
--}}
@extends('layout.admin')
@section('content')

    {{-- ================================================================== --}}
    {{-- ALERT: ERROR VALIDASI                                              --}}
    {{-- Tampil saat form di-submit dengan data tidak valid (redirect back) --}}
    {{-- ================================================================== --}}
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

    {{-- ================================================================== --}}
    {{-- ALERT: SUKSES                                                       --}}
    {{-- Tampil setelah operasi CRUD berhasil (via session flash)           --}}
    {{-- ================================================================== --}}
    @if (session('success'))
        <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="px-4 py-6 mx-auto">

        {{-- ================================================================ --}}
        {{-- PAGE HEADER                                                      --}}
        {{-- Kiri: Judul + subjudul | Kanan: Tombol Tambah                   --}}
        {{-- ================================================================ --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    [NamaModul]
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data [namaModul]
                </p>
            </div>
            <a href="{{ route('[route.prefix].create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                       bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah [NamaModul]
            </a>
        </div>

        {{-- ================================================================ --}}
        {{-- TABLE CARD                                                        --}}
        {{-- ================================================================ --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- ============================================================ --}}
            {{-- [OPSIONAL] SEARCH BAR                                         --}}
            {{-- Gunakan jika modul butuh pencarian teks bebas                --}}
            {{-- Hapus blok ini jika tidak diperlukan                         --}}
            {{-- ============================================================ --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <form method="GET" action="{{ route('[route.prefix].index') }}">
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari [NamaModul]..."
                            class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white
                                   placeholder-gray-400 text-gray-700 focus:outline-none
                                   focus:ring-2 focus:ring-[var(--flora-moss)] focus:border-transparent transition">
                        @if (!empty($search))
                            <a href="{{ route('[route.prefix].index') }}"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            {{-- [/OPSIONAL SEARCH BAR] --}}

            {{-- ============================================================ --}}
            {{-- [OPSIONAL] FILTER TANGGAL                                    --}}
            {{-- Gunakan untuk modul yang punya field tanggal penting          --}}
            {{-- Hapus blok ini jika tidak diperlukan                         --}}
            {{-- ============================================================ --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3
                        px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <form action="{{ route('[route.prefix].index') }}" method="GET"
                      class="flex flex-wrap items-center gap-2 w-full">

                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Tgl. [NamaField]
                        </label>
                        <div class="flex items-center gap-1">
                            <input type="date" name="[field]_dari" value="{{ request('[field]_dari') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white">
                            <span class="text-gray-400 text-xs">–</span>
                            <input type="date" name="[field]_sampai" value="{{ request('[field]_sampai') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white">
                        </div>
                    </div>

                    <div class="flex items-end gap-2 self-end">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[var(--flora-teal)]
                                   rounded-lg hover:bg-[var(--flora-moss)] transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
                        </button>
                        @if (request()->hasAny(['[field]_dari', '[field]_sampai']))
                            <a href="{{ route('[route.prefix].index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100
                                       rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                                <i class="fa-solid fa-xmark text-xs mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            {{-- [/OPSIONAL FILTER TANGGAL] --}}

            {{-- ============================================================ --}}
            {{-- EMPTY STATE (standalone — gunakan jika tidak ada tabel)       --}}
            {{-- Dipakai bersama pola @if ($data->isEmpty()) ... @else         --}}
            {{-- ============================================================ --}}
            @if ($data->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-[fa-icon] text-gray-400 text-xl"></i>
                    </div>
                    {{-- Tampilkan pesan yang berbeda saat filter/search aktif --}}
                    @if (!empty($search))
                        <p class="text-sm font-medium text-gray-500">Tidak ada hasil untuk "{{ $search }}"</p>
                        <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                    @else
                        <p class="text-sm font-medium text-gray-500">Belum ada data [NamaModul]</p>
                        <p class="text-xs text-gray-400 mt-1">Klik "Tambah [NamaModul]" untuk memulai</p>
                    @endif
                </div>
            @else

            {{-- ============================================================ --}}
            {{-- TABEL DATA                                                    --}}
            {{-- ============================================================ --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50 text-left">
                            {{-- Kolom nomor urut --}}
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8">#</th>

                            {{-- Kolom data — sesuaikan dengan modul --}}
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                [Kolom 1]
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                [Kolom 2]
                            </th>

                            {{-- Kolom aksi selalu di kanan --}}
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($data as $index => $item)
                            <tr class="hover:bg-gray-50/60 transition border-b border-gray-100 group">

                                {{-- Nomor urut — gunakan firstItem() jika pakai paginator --}}
                                <td class="px-5 py-4 text-gray-400 text-xs">
                                    {{ $data->firstItem() + $index }}
                                    {{-- ATAU jika tidak pakai paginator: {{ $index + 1 }} --}}
                                </td>

                                {{-- Kolom data — sesuaikan --}}
                                <td class="px-5 py-4 text-gray-700">
                                    <p class="font-medium text-gray-800">{{ $item->nama }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->sub_info }}</p>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $item->field_lain }}
                                </td>

                                {{-- ====================================== --}}
                                {{-- KOLOM AKSI — Icon-only button group    --}}
                                {{-- ====================================== --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">

                                        {{-- Tombol Detail/Show --}}
                                        <a href="{{ route('[route.prefix].show', $item->id) }}" title="Detail"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('[route.prefix].edit', $item->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>

                                        {{-- Tombol Hapus — selalu gunakan SweetAlert --}}
                                        <form method="POST" action="{{ route('[route.prefix].destroy', $item->id) }}"
                                            class="form-delete">
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

                        {{-- ================================================ --}}
                        {{-- EMPTY STATE (dalam tabel — dipakai bersama @forelse) --}}
                        {{-- ================================================ --}}
                        @empty
                            <tr>
                                <td colspan="[N]" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-[fa-icon] text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data [NamaModul]</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                @if (request()->hasAny(['search']))
                                                    Coba ubah filter pencarian
                                                @else
                                                    Mulai dengan menambahkan [namaModul] baru
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @endif {{-- end isEmpty check --}}

            {{-- ============================================================ --}}
            {{-- [OPSIONAL] PAGINATION                                         --}}
            {{-- Tampil hanya jika ada lebih dari satu halaman                --}}
            {{-- ============================================================ --}}
            @if (isset($data) && method_exists($data, 'hasPages') && $data->hasPages())
                <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/60 flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }}
                        dari {{ $data->total() }} data
                    </p>
                    <div class="flora-pagination">
                        {{ $data->withQueryString()->links() }}
                    </div>
                </div>
            @endif

        </div>{{-- end card --}}
    </div>{{-- end container --}}

    {{-- ================================================================== --}}
    {{-- SCRIPTS — SweetAlert konfirmasi hapus                              --}}
    {{-- Selalu sertakan ini jika ada tombol hapus                          --}}
    {{-- ================================================================== --}}
    @push('scripts')
        <script>
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: 'Data yang dihapus tidak dapat dikembalikan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
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
