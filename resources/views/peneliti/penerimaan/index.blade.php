@extends('layout.admin')
@section('content')
<div class="px-4 py-6 mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                Penerimaan Koleksi Kebun Raya
            </h1>
            <p class="text-sm text-[var(--flora-stone)] mt-1">Koleksi Penerimaan Tanaman</p>
        </div>
        <a href="{{ route('peneliti.penerimaan.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                   bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors">
            <i class="fa-solid fa-plus text-xs"></i> Tambah Penerimaan
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        {{-- ✅ FILTER FORM (diperbaiki) --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3
                    px-5 py-4 border-b border-gray-200 bg-gray-50/60">
            <form action="{{ route('peneliti.penerimaan.index') }}" method="GET"
                  class="flex flex-wrap items-center gap-2 w-full">


                {{-- ✅ Tanggal Penerimaan DARI --}}
                <div class="flex flex-col gap-0.5">
                    <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                        Tgl. Penerimaan
                    </label>
                    <div class="flex items-center gap-1">
                        <input type="date" name="penerimaan_dari" value="{{ request('penerimaan_dari') }}"
                            class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                   focus:border-transparent bg-white">
                        <span class="text-gray-400 text-xs">–</span>
                        <input type="date" name="penerimaan_sampai" value="{{ request('penerimaan_sampai') }}"
                            class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                   focus:border-transparent bg-white">
                    </div>
                </div>

                {{-- ✅ Tanggal Eksplorasi DARI–SAMPAI --}}
                <div class="flex flex-col gap-0.5">
                    <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                        Tgl. Eksplorasi
                    </label>
                    <div class="flex items-center gap-1">
                        <input type="date" name="eksplorasi_dari" value="{{ request('eksplorasi_dari') }}"
                            class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                   focus:border-transparent bg-white">
                        <span class="text-gray-400 text-xs">–</span>
                        <input type="date" name="eksplorasi_sampai" value="{{ request('eksplorasi_sampai') }}"
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

                    {{-- ✅ Tombol reset filter --}}
                    @if(request()->hasAny(['search','penerimaan_dari','penerimaan_sampai','eksplorasi_dari','eksplorasi_sampai']))
                        <a href="{{ route('peneliti.penerimaan.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100
                                   rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-xmark text-xs mr-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">#</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Penerimaan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Eksplorasi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Koleksi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Material</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Penanggung Jawab</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($penerimaan as $index => $p)
                        <tr class="hover:bg-gray-50/70 transition-colors duration-150 group">
                            <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $index + 1 }}</td>
                            <td class="px-5 py-3.5 text-gray-700 text-xs">
                                {{ \Carbon\Carbon::parse($p->tanggal_penerimaan)->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-700 text-xs">
                                {{ \Carbon\Carbon::parse($p->tanggal_explorasi)->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-700 text-xs">
                                {{ $p->penerimaanTanaman?->count() }} Koleksi
                            </td>
                            <td class="px-5 py-3.5 text-gray-700 text-xs">
                                {{ $p->penerimaanTanaman?->sum('jumlah_material') }} Material
                            </td>
                            <td class="px-5 py-3.5 text-gray-700 text-xs">{{ $p->user?->name }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('peneliti.penerimaan.show', $p->id) }}" title="Detail"
                                        class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('peneliti.penerimaan.edit', $p->id) }}" title="Edit"
                                        class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('peneliti.penerimaan.destroy', $p->id) }}" class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="delete_tanaman_penerimaan" value="0" class="delete-peneriaam-tanaman">
                                        <input type="hidden" name="penerimaan_id" value="{{ $p->id }}">
                                        <button type="submit" class="btn-delete p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        {{-- ✅ EMPTY STATE --}}
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-inbox text-2xl text-gray-300"></i>
                                    </div>
                                    @if(request()->hasAny(['search','penerimaan_dari','penerimaan_sampai','eksplorasi_dari','eksplorasi_sampai']))
                                        <p class="text-sm font-medium text-gray-500">Tidak ada data yang cocok dengan filter</p>
                                        <p class="text-xs text-gray-400">Coba ubah atau reset filter pencarian</p>
                                        <a href="{{ route('peneliti.penerimaan.index') }}"
                                            class="mt-1 px-4 py-2 text-xs font-medium text-white bg-[var(--flora-teal)]
                                                   rounded-lg hover:bg-[var(--flora-moss)] transition-colors">
                                            Reset Filter
                                        </a>
                                    @else
                                        <p class="text-sm font-medium text-gray-500">Belum ada data penerimaan</p>
                                        <p class="text-xs text-gray-400">Mulai tambahkan penerimaan koleksi pertama</p>
                                        <a href="{{ route('peneliti.penerimaan.create') }}"
                                            class="mt-1 px-4 py-2 text-xs font-medium text-white bg-[var(--flora-teal)]
                                                   rounded-lg hover:bg-[var(--flora-moss)] transition-colors">
                                            Tambah Penerimaan
                                        </a>
                                    @endif
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
