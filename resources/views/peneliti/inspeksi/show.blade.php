@extends('layout.admin')
@section('content')
    {{-- {{ $inspeksiTanaman }} --}}
    <div class="mx-auto px-4 py-6">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                    Informasi Inspeksi
                </h2>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Tanggal Inspeksi</dt>
                        <dd class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($data->tanggal_inspeksi)->translatedFormat('d F Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Stage</dt>
                        <dd class="text-sm text-gray-700 capitalize">{{ $data->stage }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Peneliti</dt>
                        <dd class="text-sm text-gray-700">{{ $data->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Dibuat</dt>
                        <dd class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y, H:i') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Catatan</dt>
                        <dd class="text-sm text-gray-700 leading-relaxed">{{ $data->catatan }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                    Inspected Tanaman
                </h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50 text-left">
                                {{-- Kolom data — sesuaikan dengan modul --}}
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Nomor Akses
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Nama Tanaman
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                    Status Tanaman
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inspeksiTanaman as $index => $it)
                                <tr class="hover:bg-gray-50/60 transition border-b border-gray-100 group">
                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $it->tanaman->nomorAkses }}
                                    </td>
                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $it->tanaman->tanamanPenerimaan->tanamanInfo->scientific_name }}
                                    </td>
                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $it->status }}
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                    @if (isset($inspeksiTanaman) && method_exists($inspeksiTanaman, 'hasPages') && $inspeksiTanaman->hasPages())
                        <div
                            class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/60 flex items-center justify-between gap-4">
                            <p class="text-xs text-gray-400">
                                Menampilkan {{ $inspeksiTanaman->firstItem() }}–{{ $inspeksiTanaman->lastItem() }}
                                dari {{ $inspeksiTanaman->total() }} data
                            </p>
                            <div class="flora-pagination">
                                {{ $inspeksiTanaman->withQueryString()->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div> {{-- card 2 --}}
        @if ($data->stage === 'evaluasi')
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        Hasil Evaluasi
                    </h2>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50 text-left">
                                    <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                        Nomor Akses</th>
                                    @forelse ($criteria as $crit)
                                        <th
                                            class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                            {{ $crit->nama_criteria }}</th>
                                    @empty
                                    @endforelse
                                    <th class="px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($inspeksiTanaman as $it)
                                    <tr class="hover:bg-gray-50/60 transition border-b border-gray-100">
                                        <td class="px-5 py-4 text-gray-700">
                                            {{ $it->tanaman->nomorAkses }}
                                        </td>
                                        @foreach ($criteria as $crit)
                                            @php
                                                $nilaiRow = isset($inspeksiCriteria[$it->id])
                                                    ? $inspeksiCriteria[$it->id]->firstWhere('criteria_id', $crit->id)
                                                    : null;
                                            @endphp
                                            <td class="px-5 py-4 text-gray-700">
                                                @if ($nilaiRow)
                                                    @if ($nilaiRow->nilai_numeric !== null)
                                                        {{ intval($nilaiRow->nilai_numeric) }} {{ $crit->satuan }}
                                                    @elseif($nilaiRow->criteriaOrdinal)
                                                        {{ $nilaiRow->criteriaOrdinal->label }} ({{ $nilaiRow->criteriaOrdinal->urutan }})
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endforeach
                                         <td class="px-5 py-4 text-gray-700">
                                            <a href="{{ route('peneliti.inspeksi.editEvaluasi', $it->id) }}" title="Edit"
                                                    class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $criteria->count() + 1 }}"
                                            class="px-5 py-4 text-center text-gray-400">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div> {{-- mx-auto px-4 py-6 --}}
@endsection
