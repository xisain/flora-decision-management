@extends('layout.admin')
@section('content')

    <div class="px-4 py-6 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-(--flora-moss) tracking-tight">
                    Penyemaian Koleksi
                </h1>
                <p class="text-sm mt-1 text-(--flora-stone)">taruh teks disini</p>
            </div>
            <a href="{{ route('peneliti.penyemaian.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                   bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Seeding
            </a>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-200 bg-gray-50/text-red-600">
                <form action="{{ route('peneliti.penyemaian.index') }}" method="get"
                    class="flex flex-wrap items-center gap-2 w-full">
                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Tgl. Penyemaian
                        </label>
                        <div class="flex items-center gap-1">
                            <input type="date" name="penyemaian_dari" value="{{ request('penyemaian_dari') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                   focus:border-transparent bg-white">
                            <span class="text-gray-400 text-xs">-</span>
                            <input type="date" name="penyemaian_sampai" value="{{ request('penyemaian_sampai') }}"
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
                        @if (request()->hasAny(['search', 'penyemaian_dari', 'penyemaian_sampai']))
                            <a href="{{ route('peneliti.penyemaian.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100
                               rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                                <i class="fa-solid fa-xmark text-xs mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                #</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                Tgl Semai</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                Jumlah Semai</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                Penanggung Jawab</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                Catatan Semai</th>
                            <th
                                class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($data as $index => $p)
                            <tr class="hover:bg-gray-50/70 transition-colors duration-150 group">
                                <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $index + 1 }}</td>
                                <td class="px-5 py-3.5 text-gray-700 text-xs">
                                    {{ \Carbon\Carbon::parse($p->tanggal_penyemaian)->format('d-m-Y') }}</td>
                                <td class="px-5 py-3.5 text-gray-400 text-xs">{{ count($p->penyemaianTanaman) }} Tanaman
                                </td>
                                <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $p->user->name }}</td>
                                <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $p->catatan }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('peneliti.penyemaian.show', $p->id) }}" title="Detail"
                                        class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('peneliti.penyemaian.edit', $p->id) }}" title="Edit"
                                        class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('peneliti.penyemaian.destroy', $p->id) }}" class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="delete_tanaman_penyemaian" value="0" class="delete-peneriaam-tanaman">
                                        <input type="hidden" name="penyemaian_id" value="{{ $p->id }}">
                                        <button type="submit" class="btn-delete p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                                </td>

                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
