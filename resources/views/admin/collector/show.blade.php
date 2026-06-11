@extends('layout.admin')
@section('content')
    <div class="px-4 py-6 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    [NamaModul]
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data [namaModul]
                </p>
            </div>
        </div>
        {{-- Informasi Collector --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                    Informasi Collector
                </h2>
            </div>

            <div class="divide-y divide-gray-100">

                {{-- Baris 1: Avatar + Info Dasar Collector --}}
                <div class="px-6 py-4 flex items-center gap-6 flex-wrap">

                    {{-- Avatar + Nama --}}
                    <div class="flex items-center gap-3 flex-1 min-w-[200px]">
                        <div
                            class="w-10 h-10 rounded-full bg-green-50 border border-green-200 flex items-center justify-center shrink-0">
                            <span class="text-sm font-semibold text-green-700">{{ $find->initial_collector_name }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $find->full_name }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                            </p>
                        </div>
                    </div>

                    {{-- Inisial --}}
                    <div class="min-w-[80px]">
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest mb-1">Inisial</p>
                        <p class="text-sm text-gray-700">{{ $find->initial_collector_name }}</p>
                    </div>

                    {{-- Terdaftar --}}
                    <div class="min-w-[120px]">
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest mb-1">Terdaftar</p>
                        <p class="text-sm text-gray-700 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($find->created_at)->translatedFormat('d F Y') }}
                        </p>
                    </div>

                </div>

                {{-- Baris 2: Info Akun User (hanya jika ada relasi user) --}}
                @if ($find->user)
                    <div class="px-6 py-4 flex items-center gap-6 flex-wrap bg-gray-50/50">

                        <div class="flex items-center gap-2 min-w-[200px] flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-xs text-gray-400 uppercase tracking-widest font-medium">Akun Terdaftar</span>
                        </div>

                        {{-- Email --}}
                        <div class="min-w-[160px]">
                            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest mb-1">Email</p>
                            <p class="text-sm text-gray-700 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $find->user->email }}
                            </p>
                        </div>

                        {{-- No HP --}}
                        <div class="min-w-[120px]">
                            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest mb-1">No. HP</p>
                            <p class="text-sm text-gray-700 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $find->user->phone_number ?? '-' }}
                            </p>
                        </div>

                        {{-- Status Akun --}}
                        <div class="min-w-[100px]">
                            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest mb-1">Status Akun</p>
                            @if ($find->user->account_status)
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-full px-2.5 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                    </div>
                @endif

            </div>
        </div>
        @if ($find->penerimaanTanaman)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        Tabel Koleksi
                    </h2>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50 text-left">
                                {{-- Kolom nomor urut --}}
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest w-8">#
                                </th>

                                {{-- Kolom data — sesuaikan dengan modul --}}
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Nomor Akses
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Nama Ilmiah
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Tipe Tanaman
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Locality
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    No Vak
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Jumlah Material
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($find->penerimaanTanaman as $index => $pt)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3.5 text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-mono text-xs text-gray-700">
                                        <a href="{{ route('peneliti.penerimaan.show',$pt->id) }}" class=" text-blue-400 hover:text-blue-700">{{ $pt->nomor_akses }}</a>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-700 font-bold italic">
                                        {{ $pt->tanamanInfo->scientific_name ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-700">{{ $pt->habitus }}</td>
                                    <td class="px-5 py-3.5 text-gray-700">{{ $pt->locality }}</td>
                                    <td class="px-5 py-3.5 text-gray-600 font-mono text-xs">{{ $pt->vak_no }}</td>
                                    <td class="px-5 py-3.5 text-gray-700">{{ $pt->jumlah_material }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                </div>
            </div>
        @endif
    </div>
@endsection
