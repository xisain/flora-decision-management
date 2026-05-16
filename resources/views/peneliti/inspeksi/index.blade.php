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
                <form action="{{ route('peneliti.inspeksi.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-3 w-full">

                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Tgl. Inspeksi
                        </label>
                        <div class="flex items-center gap-1">
                            <input type="date" name="tanggal_inspeksi_dari"
                                value="{{ request('tanggal_inspeksi_dari') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                focus:border-transparent bg-white" />

                            <span class="text-gray-400 text-xs">–</span>

                            <input type="date" name="tanggal_inspeksi_sampai"
                                value="{{ request('tanggal_inspeksi_sampai') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                focus:border-transparent bg-white" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Stage Inspeksi
                        </label>
                        <select name="stage" id="stage" class="py-2 px-3 text-sm border border-gray-200 rounded-lg">
                            <option value="">Semua Stage</option>
                            <option value="checkup" @selected(request('stage') === 'checkup')>Checkup</option>
                            <option value="labeling" @selected(request('stage') === 'labeling')>Labeling</option>
                            <option value="aklimatisasi" @selected(request('stage') === 'aklimatisasi')>Aklimatisasi</option>
                            <option value="evaluasi" @selected(request('stage') === 'evaluasi')>Evaluasi</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2 self-end">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[var(--flora-teal)]
                                   rounded-lg hover:bg-[var(--flora-moss)] transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
                        </button>
                        @if (request()->hasAny(['tanggal_inspeksi_dari', 'tanggal_inspeksi_sampai']))
                            <a href="{{ route('peneliti.inspeksi.index') }}"
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
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            {{-- Kolom nomor urut --}}
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>

                            {{-- Kolom data — sesuaikan dengan modul --}}
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Tanggal Inspeksi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Jumlah Tanaman
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Stage Inspeksi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Catatan
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Penanggung Jawab
                            </th>

                            {{-- Kolom aksi selalu di kanan --}}
                            <th
                                class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($data as $index => $item)
                            <tr class="hover:bg-emerald-50/60 transition border-b border-gray-200 group">
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $data->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal_inspeksi)->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->inspeksiTanaman->count() }} Tanaman
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    @php
                                        $stageClass = match ($item->stage) {
                                            'checkup' => 'bg-sky-50 text-sky-700 border-sky-100',
                                            'labeling' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'aklimatisasi' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'evaluasi' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            default => 'bg-gray-50 text-gray-600 border-gray-100',
                                        };

                                        $stageLabel = match ($item->stage) {
                                            'checkup' => 'Checkup',
                                            'labeling' => 'Labeling',
                                            'aklimatisasi' => 'Aklimatisasi',
                                            'evaluasi' => 'Evaluasi',
                                            default => Str::title($item->stage),
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium {{ $stageClass }}">
                                        {{ $stageLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->catatan }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->user->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('peneliti.inspeksi.show', $item->id) }}" title="Detail"
                                        class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data Inspeksi</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                @if (request()->hasAny(['tanggal_inspeksi_dari', 'tanggal_inspeksi_sampai', 'stage']))
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
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
