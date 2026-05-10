@extends('layout.admin')
@section('content')
    @php
        $checkupData = $checkup
            ->map(function ($i) {
                return [
                    'id' => $i->id,
                    'nomor_akses' => $i->nomor_akses,
                    'scientific_name' => $i->tanamanPenerimaan->tanamanInfo->scientific_name,
                    'author_name' => $i->tanamanPenerimaan->tanamanInfo->author_name,
                ];
            })
            ->values();

        $labelingData = $labeling
            ->map(function ($i) {
                return [
                    'id' => $i->id,
                    'nomor_akses' => $i->nomor_akses,
                    'scientific_name' => $i->tanamanPenerimaan->tanamanInfo->scientific_name,
                    'author_name' => $i->tanamanPenerimaan->tanamanInfo->author_name,
                ];
            })
            ->values();
        $AklimatisasiData = $aklimatisasi
            ->map(function ($i) {
                return [
                    'id' => $i->id,
                    'nomor_akses' => $i->nomor_akses,
                    'scientific_name' => $i->tanamanPenerimaan->tanamanInfo->scientific_name,
                    'author_name' => $i->tanamanPenerimaan->tanamanInfo->author_name,
                ];
            })
            ->values();

        $evaluasiData = $evaluasi
            ->map(function ($i) {
                return [
                    'id' => $i->id,
                    'nomor_akses' => $i->nomor_akses,
                    'scientific_name' => $i->tanamanPenerimaan->tanamanInfo->scientific_name,
                    'author_name' => $i->tanamanPenerimaan->tanamanInfo->author_name,
                ];
            })
            ->values();
    @endphp
    <div class="px-4 py-6 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Tambah Inspeksi Baru
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    [Deskripsi singkat halaman ini]
                </p>
            </div>
            <a href="{{ route('peneliti.inspeksi.index') }}"
                class="inline-flex items-center gap-2 text-sm text-[var(--flora-stone)] hover:text-[var(--flora-moss)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
        {{-- {{ $data }} --}}
        <form action="{{ route('peneliti.inspeksi.store') }}" method="POST" class="px-6 py-6 space-y-6"
            x-data="inspeksiForm()">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="tanggal_inspeksi" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Inspeksi <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input type="date" id="tanggal_inspeksi" name="tanggal_inspeksi"
                                value="{{ old('tanggal_inspeksi') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition @error('tanggal_inspeksi') border-red-400 @enderror">
                            @error('tanggal_inspeksi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <textarea id="catatan" name="catatan" rows="4" placeholder="[Placeholder...]"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition resize-none @error('catatan') border-red-400 @enderror">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-[var(--flora-moss)]">
                            Pilih Tahapan Inspeksi
                        </h2>
                        <p class="text-sm text-gray-500">
                            Pilih salah satu tahapan inspeksi tanaman
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button type="button" @click="changeStage('checkup')" x-transition
                            :class="selectedStage === 'checkup'
                                ?
                                'border-[var(--flora-moss)] bg-green-50 ring-2 ring-[var(--flora-moss)]' :
                                'border-gray-200 hover:border-[var(--flora-moss)]'"
                            class="text-left border rounded-2xl p-5 transition-all duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div :class="selectedStage === 'checkup'
                                    ?
                                    'bg-[var(--flora-moss)] text-white' :
                                    'bg-gray-100 text-gray-500'"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                                    1
                                </div>

                                <svg x-show="selectedStage === 'checkup'" class="w-5 h-5 text-[var(--flora-moss)]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <h3 class="font-semibold text-gray-800 mb-1">
                                Checkup
                            </h3>

                            <p class="text-sm text-gray-500">
                                Pemeriksaan awal kondisi tanaman.
                            </p>
                        </button>
                        {{-- Stage 2  --}}
                        <button type="button" @click="changeStage('labeling')" x-transition
                            :class="selectedStage === 'labeling'
                                ?
                                'border-[var(--flora-moss)] bg-green-50 ring-2 ring-[var(--flora-moss)]' :
                                'border-gray-200 hover:border-[var(--flora-moss)]'"
                            class="text-left border rounded-2xl p-5 transition-all duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div :class="selectedStage === 'labeling'
                                    ?
                                    'bg-[var(--flora-moss)] text-white' :
                                    'bg-gray-100 text-gray-500'"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                                    2
                                </div>

                                <svg x-show="selectedStage === 'labeling'" class="w-5 h-5 text-[var(--flora-moss)]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <h3 class="font-semibold text-gray-800 mb-1">
                                Labeling
                            </h3>

                            <p class="text-sm text-gray-500">
                                Pemeriksaan kondisi tanaman dan pelabelan fisik.
                            </p>
                        </button>

                        {{-- Stage 3 --}}
                        <button type="button" @click="changeStage('aklimatisasi')" x-transition
                            :class="selectedStage === 'aklimatisasi'
                                ?
                                'border-[var(--flora-moss)] bg-green-50 ring-2 ring-[var(--flora-moss)]' :
                                'border-gray-200 hover:border-[var(--flora-moss)]'"
                            class="text-left border rounded-2xl p-5 transition-all duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div :class="selectedStage === 'aklimatisasi'
                                    ?
                                    'bg-[var(--flora-moss)] text-white' :
                                    'bg-gray-100 text-gray-500'"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                                    3
                                </div>

                                <svg x-show="selectedStage === 'aklimatisasi'" class="w-5 h-5 text-[var(--flora-moss)]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <h3 class="font-semibold text-gray-800 mb-1">
                                Aklimatisasi
                            </h3>

                            <p class="text-sm text-gray-500">
                                Pemindahan tanaman ke polybag dan rumah paranet.
                            </p>
                        </button>

                        {{-- Stage 4 --}}
                        <button type="button" @click="changeStage('evaluasi')" x-transition
                            :class="selectedStage === 'evaluasi'
                                ?
                                'border-[var(--flora-moss)] bg-green-50 ring-2 ring-[var(--flora-moss)]' :
                                'border-gray-200 hover:border-[var(--flora-moss)]'"
                            class="text-left border rounded-2xl p-5 transition-all duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div :class="selectedStage === 'evaluasi'
                                    ?
                                    'bg-[var(--flora-moss)] text-white' :
                                    'bg-gray-100 text-gray-500'"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                                    4
                                </div>

                                <svg x-show="selectedStage === 'evaluasi'" class="w-5 h-5 text-[var(--flora-moss)]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <h3 class="font-semibold text-gray-800 mb-1">
                                Evaluasi
                            </h3>

                            <p class="text-sm text-gray-500">
                                Evaluasi kondisi tanaman dan pengisian penilaian.
                            </p>
                        </button>
                    </div>

                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="px-6 py-4">

                    {{-- STEP 1 --}}
                    <div x-show="currentStep === 1" x-transition>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-[var(--flora-moss)]">
                                Pilih Tanaman
                            </h2>

                            <p class="text-sm text-gray-500">
                                Pilih tanaman untuk inspeksi
                            </p>
                        </div>

                        {{-- Ganti bagian <table> --}}
                        <div class="overflow-x-auto">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm text-gray-500">
                                    <span class="font-semibold text-[var(--flora-moss)]"
                                        x-text="selectedPlants.length"></span>
                                    tanaman dipilih
                                </p>
                                <button type="button" @click="clearSelection()" x-show="selectedPlants.length > 0"
                                    class="text-xs text-red-400 hover:text-red-600 transition">
                                    Hapus Pilihan
                                </button>
                            </div>

                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-3 w-8">
                                            <input type="checkbox" x-ref="selectAllCheckbox"
                                                @change="toggleSelectAll($event)" :checked="isAllSelected()"
                                                class="rounded border-gray-300 text-[var(--flora-moss)] focus:ring-[var(--flora-moss)]">
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nomor
                                            Akses</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama
                                            Scientific</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Author</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Status</th>
                                    </tr>
                                </thead>
                                {{-- CHECKUP --}}
                                <tbody class="divide-y divide-gray-100" x-show="selectedStage === 'checkup'" x-cloak
                                    x-transition>
                                    @forelse ($checkup as $index => $item)
                                        <tr class="hover:bg-gray-50 transition"
                                            :class="selectedPlants.some(p => p.id === {{ $item->id }}) ? 'bg-green-50' : ''">
                                            <td class="px-4 py-3">
                                                <input type="checkbox"
                                                    @change="togglePlant({
                                                        id: {{ $item->id }},
                                                        nomor_akses: '{{ $item->nomor_akses }}',
                                                        scientific_name: '{{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}',
                                                        author_name: '{{ $item->tanamanPenerimaan->tanamanInfo->author_name }}'
                                                    })"
                                                    :checked="selectedPlants.some(p => p.id === {{ $item->id }})"
                                                    class="rounded border-gray-300 text-[var(--flora-moss)] focus:ring-[var(--flora-moss)]">
                                            </td>
                                            <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-800">{{ $item->nomor_akses }}</td>
                                            <td class="px-4 py-3 italic text-gray-700">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}</td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->author_name }}</td>
                                            <td class="px-4 py-3">
                                                @php $status = $item->penyemaianTanaman->first()?->status @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $status === 'hidup'
                                ? 'bg-green-100 text-green-700'
                                : ($status === 'mati'
                                    ? 'bg-red-100 text-red-700'
                                    : ($status === 'recovery'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-gray-100 text-gray-600')) }}">
                                                    {{ Str::upper($status ?? '-') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                                Tidak ada data tanaman untuk tahap Checkup
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tbody class="divide-y divide-gray-100" x-show="selectedStage === 'labeling'" x-cloak
                                    x-transition>
                                    @forelse ($labeling as $index => $item)
                                        <tr class="hover:bg-gray-50 transition"
                                            :class="selectedPlants.some(p => p.id === {{ $item->id }}) ? 'bg-green-50' : ''">
                                            <td class="px-4 py-3">
                                                <input type="checkbox"
                                                    @change="togglePlant({
                                                        id: {{ $item->id }},
                                                        nomor_akses: '{{ $item->nomor_akses }}',
                                                        scientific_name: '{{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}',
                                                        author_name: '{{ $item->tanamanPenerimaan->tanamanInfo->author_name }}'
                                                    })"
                                                    :checked="selectedPlants.some(p => p.id === {{ $item->id }})"
                                                    class="rounded border-gray-300 text-[var(--flora-moss)] focus:ring-[var(--flora-moss)]">
                                            </td>
                                            <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-800">{{ $item->nomor_akses }}</td>
                                            <td class="px-4 py-3 italic text-gray-700">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}</td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->author_name }}</td>
                                            <td class="px-4 py-3">
                                                @php $status = $item->penyemaianTanaman->first()?->status @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $status === 'hidup'
                                ? 'bg-green-100 text-green-700'
                                : ($status === 'mati'
                                    ? 'bg-red-100 text-red-700'
                                    : ($status === 'recovery'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-gray-100 text-gray-600')) }}">
                                                    {{ Str::upper($status ?? '-') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                                Tidak ada data tanaman untuk tahap Aklimatisasi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                {{-- AKLIMATISASI --}}
                                <tbody class="divide-y divide-gray-100" x-show="selectedStage === 'aklimatisasi'" x-cloak
                                    x-transition>
                                    @forelse ($aklimatisasi as $index => $item)
                                        <tr class="hover:bg-gray-50 transition"
                                            :class="selectedPlants.some(p => p.id === {{ $item->id }}) ? 'bg-green-50' : ''">
                                            <td class="px-4 py-3">
                                                <input type="checkbox"
                                                    @change="togglePlant({
                                                        id: {{ $item->id }},
                                                        nomor_akses: '{{ $item->nomor_akses }}',
                                                        scientific_name: '{{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}',
                                                        author_name: '{{ $item->tanamanPenerimaan->tanamanInfo->author_name }}'
                                                    })"
                                                    :checked="selectedPlants.some(p => p.id === {{ $item->id }})"
                                                    class="rounded border-gray-300 text-[var(--flora-moss)] focus:ring-[var(--flora-moss)]">
                                            </td>
                                            <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-800">{{ $item->nomor_akses }}</td>
                                            <td class="px-4 py-3 italic text-gray-700">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}</td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->author_name }}</td>
                                            <td class="px-4 py-3">
                                                @php $status = $item->penyemaianTanaman->first()?->status @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $status === 'hidup'
                                ? 'bg-green-100 text-green-700'
                                : ($status === 'mati'
                                    ? 'bg-red-100 text-red-700'
                                    : ($status === 'recovery'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-gray-100 text-gray-600')) }}">
                                                    {{ Str::upper($status ?? '-') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                                Tidak ada data tanaman untuk tahap Aklimatisasi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                {{-- EVALUASI --}}
                                <tbody class="divide-y divide-gray-100" x-show="selectedStage === 'evaluasi'" x-cloak
                                    x-transition>
                                    @forelse ($evaluasi as $index => $item)
                                        <tr class="hover:bg-gray-50 transition"
                                            :class="selectedPlants.some(p => p.id === {{ $item->id }}) ? 'bg-green-50' : ''">
                                            <td class="px-4 py-3">
                                                <input type="checkbox"
                                                    @change="togglePlant({
                                                        id: {{ $item->id }},
                                                        nomor_akses: '{{ $item->nomor_akses }}',
                                                        scientific_name: '{{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}',
                                                        author_name: '{{ $item->tanamanPenerimaan->tanamanInfo->author_name }}'
                                                    })"
                                                    :checked="selectedPlants.some(p => p.id === {{ $item->id }})"
                                                    class="rounded border-gray-300 text-[var(--flora-moss)] focus:ring-[var(--flora-moss)]">
                                            </td>
                                            <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-800">{{ $item->nomor_akses }}</td>
                                            <td class="px-4 py-3 italic text-gray-700">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->scientific_name }}</td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $item->tanamanPenerimaan->tanamanInfo->author_name }}</td>
                                            <td class="px-4 py-3">
                                                @php $status = $item->penyemaianTanaman->first()?->status @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $status === 'hidup'
                                ? 'bg-green-100 text-green-700'
                                : ($status === 'mati'
                                    ? 'bg-red-100 text-red-700'
                                    : ($status === 'recovery'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-gray-100 text-gray-600')) }}">
                                                    {{ Str::upper($status ?? '-') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                                Tidak ada data tanaman untuk tahap Evaluasi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div x-show="currentStep === 2" x-transition>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-[var(--flora-moss)]">
                                Data Tanaman Terpilih
                            </h2>
                            <p class="text-sm text-gray-500">
                                Tanaman yang akan diproses inspeksi
                            </p>
                        </div>
                        <div class="space-y-3">
                            <template x-for="plant in selectedPlants" :key="plant.id">
                                <div class="border border-gray-200 rounded-2xl p-5 space-y-5">
                                    <div class="flex items-start justify-between gap-5">
                                        <div>
                                            <h3 class="font-semibold text-gray-800" x-text="plant.nomor_akses">
                                            </h3>
                                            <p class="text-sm text-gray-500" x-text="plant.scientific_name">
                                            </p>
                                            <p class="text-sm text-gray-500" x-text="plant.author_name">
                                            </p>
                                        </div>

                                        {{-- STATUS --}}
                                        <div class="w-52">
                                            <label class="block text-sm mb-1">
                                                Status
                                            </label>
                                            <select x-model="plant.status" :name="`plants[${plant.id}][status]`"
                                                class="w-full border rounded-xl px-3 py-2">
                                                <option value="">Pilih Status</option>
                                                <option value="hidup">Hidup</option>
                                                <option value="mati">Mati</option>
                                                <option value="recovery">Recovery</option>
                                                <option value="dormant">Dormant</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- CHECKUP --}}
                                    <template x-if="selectedStage === 'checkup'">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm mb-1">
                                                    Label Tanaman
                                                </label>
                                                <input type="text" :name="`plants[${plant.id}][label]`"
                                                    class="w-full border rounded-xl px-3 py-2">
                                            </div>
                                        </div>
                                    </template>
                                    {{-- AKLIMATISASI --}}
                                    <template x-if="selectedStage === 'aklimatisasi'">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm mb-1">
                                                    Nomor Polybag
                                                </label>
                                                <input type="text" :name="`plants[${plant.id}][polybag]`"
                                                    class="w-full border rounded-xl px-3 py-2">
                                            </div>
                                        </div>
                                    </template>
                                    {{-- EVALUASI --}}
                                    <template x-if="selectedStage === 'evaluasi'">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm mb-1">
                                                    Tinggi Tanaman
                                                </label>
                                                <input type="number" step="0.01"
                                                    :name="`plants[${plant.id}][tinggi]`"
                                                    class="w-full border rounded-xl px-3 py-2">
                                            </div>
                                        </div>
                                    </template>
                                    {{-- HIDDEN --}}
                                    <input type="hidden" :name="`plants[${plant.id}][id]`" :value="plant.id">
                                </div>
                            </template>
                        </div>
                        <input type="hidden" name="stage" :value="selectedStage">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                {{-- Back --}}
                <button type="button" @click="prevStep()" x-show="currentStep > 1"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Kembali
                </button>
                <div class="ml-auto flex items-center gap-3">
                    {{-- Next --}}
                    <button type="button" @click="nextStep()" x-show="currentStep < 2"
                        class="px-5 py-2.5 rounded-xl bg-[var(--flora-moss)] text-white hover:opacity-90 transition">
                        Selanjutnya
                    </button>
                    {{-- Submit --}}
                    <button type="submit" x-show="currentStep === 2"
                        class="px-5 py-2.5 rounded-xl bg-[var(--flora-moss)] text-white hover:opacity-90 transition">
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('inspeksiForm', () => ({

                    selectedStage: 'checkup',
                    currentStep: 1,
                    selectedPlants: [],

                    // Data per stage (di-render dari Blade ke JS)
                    stageData: {
                        checkup: @json($checkupData),
                        labeling: @json($labelingData),

                    },

                    get currentStageData() {
                        return this.stageData[this.selectedStage] ?? []
                    },

                    changeStage(stage) {
                        this.selectedStage = stage
                        this.selectedPlants = []
                        this.currentStep = 1
                        this.$nextTick(() => this.syncIndeterminate())
                    },

                    togglePlant(plant) {
                        const exists = this.selectedPlants.find(p => p.id === plant.id)
                        if (exists) {
                            this.selectedPlants = this.selectedPlants.filter(p => p.id !== plant.id)
                        } else {
                            this.selectedPlants.push({
                                ...plant,
                                status: '',
                                kondisi: '',
                                catatan: ''
                            })
                        }
                        this.$nextTick(() => this.syncIndeterminate())
                    },

                    // Select all untuk stage aktif
                    toggleSelectAll(event) {
                        if (event.target.checked) {
                            const current = this.currentStageData
                            current.forEach(plant => {
                                if (!this.selectedPlants.find(p => p.id === plant.id)) {
                                    this.selectedPlants.push({
                                        ...plant,
                                        status: '',
                                        kondisi: '',
                                        catatan: ''
                                    })
                                }
                            })
                        } else {
                            const currentIds = this.currentStageData.map(p => p.id)
                            this.selectedPlants = this.selectedPlants.filter(p => !currentIds.includes(p
                                .id))
                        }
                        this.$nextTick(() => this.syncIndeterminate())
                    },

                    isAllSelected() {
                        const current = this.currentStageData
                        return current.length > 0 && current.every(p => this.selectedPlants.some(sp => sp
                            .id === p.id))
                    },

                    isSomeSelected() {
                        const current = this.currentStageData
                        return current.some(p => this.selectedPlants.some(sp => sp.id === p.id)) && !this
                            .isAllSelected()
                    },

                    // Sync indeterminate state (tidak bisa pakai binding biasa)
                    syncIndeterminate() {
                        const el = this.$refs.selectAllCheckbox
                        if (el) el.indeterminate = this.isSomeSelected()
                    },

                    clearSelection() {
                        const currentIds = this.currentStageData.map(p => p.id)
                        this.selectedPlants = this.selectedPlants.filter(p => !currentIds.includes(p.id))
                        this.$nextTick(() => this.syncIndeterminate())
                    },

                    nextStep() {
                        this.currentStep++
                    },

                    prevStep() {
                        this.currentStep--
                    }
                }))
            })
        </script>
    @endpush
@endsection
