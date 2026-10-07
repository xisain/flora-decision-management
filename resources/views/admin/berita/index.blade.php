@extends('layout.admin')
@section('content')

{{-- Jaga agar teks pencarian tetap tampil di input setelah form disubmit,
     meski controller tidak eksplisit mengirim variabel $search --}}
@php
    $search = $search ?? request('search');
@endphp

{{-- ==================================================== --}}
{{-- ALERT: ERROR VALIDASI                                 --}}
{{-- ==================================================== --}}
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

{{-- ==================================================== --}}
{{-- ALERT: SUKSES                                         --}}
{{-- ==================================================== --}}
@if (session('success'))
    <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
        <div class="shrink-0 pt-0.5">
            <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
        </div>
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
@endif

<div class="px-4 py-6 mx-auto">

    {{-- ==================================================== --}}
    {{-- HEADER HALAMAN                                        --}}
    {{-- ==================================================== --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                Berita
            </h1>
            <p class="text-sm text-[var(--flora-stone)] mt-1">
                Kelola data Berita
            </p>
        </div>

        <a href="{{ route('admin.berita.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                   bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
            <i class="fa-solid fa-plus text-xs"></i>
            Tambah Berita
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        {{-- ==================================================== --}}
        {{-- FILTER / PENCARIAN                                    --}}
        {{-- ==================================================== --}}
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
            <form method="GET" action="{{ route('admin.berita.index') }}"
                class="flex flex-col sm:flex-row sm:items-center gap-3">

                {{-- Input pencarian --}}
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari Berita..."
                        class="w-full pl-8 {{ !empty($search) ? 'pr-8' : 'pr-4' }} py-2 text-sm border border-gray-200
                               rounded-lg bg-white placeholder-gray-400 text-gray-700 focus:outline-none
                               focus:ring-2 focus:ring-[var(--flora-moss)] focus:border-transparent transition">

                    @if (!empty($search))
                        <a href="{{ route('admin.berita.index') }}" title="Hapus pencarian"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                {{-- Tombol filter & reset --}}
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white
                               bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors
                               whitespace-nowrap">
                        <i class="fa-solid fa-filter text-xs"></i>
                        Filter
                    </button>

                    @if (request()->hasAny(['search']))
                        <a href="{{ route('admin.berita.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-600
                                   bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if ($Berita->isEmpty())

            {{-- ==================================================== --}}
            {{-- EMPTY STATE (di luar tabel)                           --}}
            {{-- ==================================================== --}}
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-newspaper text-gray-400 text-xl"></i>
                </div>

                @if (!empty($search))
                    <p class="text-sm font-medium text-gray-500">Tidak ada hasil untuk "{{ $search }}"</p>
                    <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                @else
                    <p class="text-sm font-medium text-gray-500">Belum ada data Berita</p>
                    <p class="text-xs text-gray-400 mt-1">Klik "Tambah Berita" untuk memulai</p>
                @endif
            </div>

        @else

            {{-- ==================================================== --}}
            {{-- TABEL DATA                                            --}}
            {{-- ==================================================== --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8">
                                #
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Gambar
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Judul Dan Author
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Publikasi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Visitor
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($Berita as $index => $item)
                            <tr class="hover:bg-gray-50/60 transition border-b border-gray-100 group">
                                <td class="px-5 py-4 text-gray-400 text-xs">
                                    {{ $Berita->firstItem() + $index }}
                                </td>

                                {{-- Gambar --}}
                                <td class="px-5 py-4">
                                    @if ($item->image_url)
                                        <img src="{{ Str::startsWith($item->image_url, ['http://', 'https://'])
                                                        ? $item->image_url
                                                        : Storage::url($item->image_url) }}"
                                            alt="{{ $item->judul }}"
                                            class="w-16 h-12 object-cover rounded-lg border border-gray-100"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';">
                                    @else
                                        <div class="w-16 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-image text-gray-300 text-xs"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-gray-700">
                                    <p class="font-medium text-gray-800">{{ $item->judul }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ optional($item->user)->name ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $item->status }}
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    <p class="text-center">{{ $item->visitor }}</p>
                                </td>

                                {{-- Kolom aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">

                                        <a href="{{ route('admin.berita.show', $item->id) }}" title="Detail"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)]
                                                   hover:bg-[var(--flora-teal)]/10 transition-colors">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        <a href="{{ route('admin.berita.edit', $item->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>

                                        <form method="POST" action="{{ route('admin.berita.destroy', $item->id) }}"
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
                        @empty

                            {{-- EMPTY STATE (dalam tabel — dipakai bersama @forelse) --}}
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-newspaper text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data Berita</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                @if (request()->hasAny(['search']))
                                                    Coba ubah filter pencarian
                                                @else
                                                    Mulai dengan menambahkan Berita baru
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

        @endif
    </div>
</div>

@endsection