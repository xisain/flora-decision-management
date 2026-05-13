@extends('layout.admin')
@section('content')
<div class="py-6 px-4 mx-auto">

    {{-- Info Tanaman --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-[var(--flora-moss)] uppercase tracking-widest">
                Informasi Tanaman
            </h2>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Nama Lokal</span>
                    <p class="font-medium text-gray-800">{{ $inspeksi->tanaman->tanamanPenerimaan->tanamanInfo->nama_lokal }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Nama Ilmiah</span>
                    <p class="font-medium text-gray-800 italic">{{ $inspeksi->tanaman->tanamanPenerimaan->tanamanInfo->scientific_name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Nomor Akses</span>
                    <p class="font-medium text-gray-800">{{ $inspeksi->tanaman->tanamanPenerimaan->nomor_akses }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Nomor Urut</span>
                    <p class="font-medium text-gray-800">{{ $inspeksi->tanaman->nomor_urut }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Status</span>
                    <p class="font-medium">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $inspeksi->status == 'hidup' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($inspeksi->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <span class="text-gray-500">Catatan</span>
                    <p class="font-medium text-gray-800">{{ $inspeksi->catatan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Nilai Evaluasi --}}
    <form action="{{ route('peneliti.inspeksi.updateEvaluasi', $inspeksi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-[var(--flora-moss)] uppercase tracking-widest">
                    Nilai Evaluasi Criteria
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-8">
                                #
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[160px]">
                                Kriteria
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                Skala
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[220px]">
                                Nilai
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($criteria as $index => $c)
                            @php $nilai = $nilaiMap->get($c->id); @endphp

                            <input type="hidden" name="nilai[{{ $index }}][criteria_id]" value="{{ $c->id }}">
                            <input type="hidden" name="nilai[{{ $index }}][id]" value="{{ $nilai->id ?? '' }}">

                            <tr class="hover:bg-gray-50 transition">
                                {{-- No --}}
                                <td class="px-4 py-3 text-gray-400 text-xs">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Nama Kriteria --}}
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-700">{{ $c->nama_criteria }}</p>
                                    @if($c->satuan)
                                        <p class="text-xs text-gray-400">{{ $c->satuan }}</p>
                                    @endif
                                </td>

                                {{-- Skala --}}
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $c->skala === 'ordinal' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($c->skala) }}
                                    </span>
                                </td>

                                {{-- Input Nilai --}}
                                <td class="px-4 py-3">
                                    @if($c->skala === 'ordinal')
                                        <select name="nilai[{{ $index }}][criteria_ordinal_id]"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)] bg-white">
                                            <option value="">-- Pilih --</option>
                                            @foreach($c->ordinals as $ordinal)
                                                <option value="{{ $ordinal->id }}"
                                                    {{ ($nilai->criteria_ordinal_id ?? null) == $ordinal->id ? 'selected' : '' }}>
                                                    {{ $ordinal->label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="nilai[{{ $index }}][nilai_numeric]" value="">

                                    @elseif($c->skala === 'numerik')
                                        <input type="number"
                                            name="nilai[{{ $index }}][nilai_numeric]"
                                            step="0.01"
                                            value="{{ intval($nilai->nilai_numeric) ?? '' }}"
                                            placeholder="Masukkan nilai..."
                                            class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]">
                                        <input type="hidden" name="nilai[{{ $index }}][criteria_ordinal_id]" value="">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3">
            <button type="submit"
                class="px-5 py-2 bg-[var(--flora-moss)] text-white text-sm font-medium rounded-lg hover:opacity-90 transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('peneliti.inspeksi.index') }}"
                class="px-5 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
