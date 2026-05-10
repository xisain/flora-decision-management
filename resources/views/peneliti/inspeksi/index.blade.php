@extends('layout.admin')
@section('content')
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
    @if (session('success'))
        <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    <div class="px-4 py-6 mx-auto">
        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Inspeksi
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data Inspeksi
                </p>
            </div>
            <a href="{{ route('peneliti.inspeksi.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
            bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Inspeksi
            </a>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <form method="GET" action="{{ route('peneliti.inspeksi.index') }}">
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari Inspeksi..."
                            class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white
                                   placeholder-gray-400 text-gray-700 focus:outline-none
                                   focus:ring-2 focus:ring-[var(--flora-moss)] focus:border-transparent transition">
                        @if (!empty($search))
                            <a href="{{ route('peneliti.inspeksi.index') }}"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50 text-left">
                            {{-- Kolom nomor urut --}}
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8">#</th>

                            {{-- Kolom data — sesuaikan dengan modul --}}
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Tanggal Inspeksi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Jumlah Tanaman
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Stage Inspeksi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Catatan
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                Penanggung Jawab
                            </th>

                            {{-- Kolom aksi selalu di kanan --}}
                            <th
                                class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($data as $index => $item)
                            <tr class="hover:bg-gray-50/60 transition border-b border-gray-100 group">
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->tanggal_inspeksi }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ count($item->inspeksiTanaman) }} Tanaman
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{  Str::upper($item->stage) }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->catatan }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->user->name }}
                                </td>
                            </tr>
                        @empty
                            <tr class="">
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-[fa-icon] text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data Inspeksi</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                @if (request()->hasAny(['search']))
                                                    Coba ubah filter pencarian
                                                @else
                                                    Mulai dengan menambahkan Inspeksi baru
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
        </div>
    </div>
@endsection
