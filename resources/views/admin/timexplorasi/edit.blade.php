@extends('layout.admin')
@section('content')
    <div class="mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-(--flora-moss)">Test</h1>
                <p class="text-sm mt-1 text-(--flora-stone)">text kecil</p>
            </div>
        </div>
        @php
            $anggotaEdit = $members->map(function ($m) {
                return [
                    'collector_id' => $m->collector_id,
                    'nama' => $m->Collector->user->name ?? ($m->Collector->full_name ?? 'Unknown'),
                    'peran' => $m->Peran,
                ];
            });
        @endphp
        <form action="{{ route('tim-explorasi.update', $team->id) }}" method="POST" enctype="multipart/form-data"
            x-data="{
                anggota: {{ Js::from(old('anggota') ?? $anggotaEdit) }},

                // ✅ INISIALISASI SEARCHES YANG BENAR (array of string)
                searches: {{ Js::from(
                    old('anggota') ? collect(old('anggota'))->pluck('nama')->all() : $anggotaEdit->pluck('nama')->all(),
                ) }},

                collectors: {{ Js::from(
                    $collector->map(
                        fn($c) => [
                            'id' => $c->id,
                            'nama' => $c->user->name ?? ($c->full_name ?? 'Unknown'),
                            'Initial' => '(' . ($c->initial_collector_name ?? '') . ')',
                        ],
                    ),
                ) }},

                openDropdown: null,

                errors: {{ Js::from($errors->toArray()) }},

                hasError(field) {
                    return this.errors[field] !== undefined
                },
                getError(field) {
                    const err = this.errors[field]
                    return Array.isArray(err) ? err[0] : (err ?? '')
                },

                get filteredCollectors() {
                    return (index) => {
                        const q = (this.searches[index] ?? '').toLowerCase()
                        const usedIds = this.anggota
                            .map((a, i) => i !== index ? a.collector_id : null)
                            .filter(Boolean)
                        return this.collectors.filter(c =>
                            c.nama.toLowerCase().includes(q) && !usedIds.includes(c.id)
                        )
                    }
                },

                pilihCollector(index, collector) {
                    this.anggota[index].collector_id = collector.id
                    this.anggota[index].nama = collector.nama
                    this.searches[index] = collector.nama
                    this.openDropdown = null
                },

                tambahAnggota() {
                    this.anggota.push({ collector_id: '', nama: '', peran: '' })
                    this.searches.push('')
                },

                hapusAnggota(index) {
                    if (this.anggota.length > 1) {
                        this.anggota.splice(index, 1)
                        this.searches.splice(index, 1)
                        if (this.openDropdown === index) this.openDropdown = null
                    }
                }
            }">
            @csrf
            @method('patch')

            {{-- ======================== Informasi Tim ======================== --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        Informasi Tim
                    </h2>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="nama_tim" class="block text-sm font-medium text-gray-700 mb-1">Nama Tim</label>
                            <input type="text" name="nama_tim" id="nama_tim"
                                value="{{ old('nama_tim', $team->nama_tim) }}" placeholder="Masukan Nama Tim"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-(--flora-moss) focus:border-transparent transition @error('nama_tim') border-red-400 @enderror">
                            @error('nama_tim')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="lokasi_explorasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi
                                Explorasi</label>
                            <input type="text" name="lokasi_explorasi" id="lokasi_explorasi"
                                value="{{ old('lokasi_explorasi', $team->lokasi_explorasi) }}"
                                placeholder="Masukan Lokasi Explorasi"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-(--flora-moss) focus:border-transparent transition @error('lokasi_explorasi') border-red-400 @enderror">
                            @error('lokasi_explorasi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="deskripsi_explorasi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi
                            Explorasi</label>
                        <textarea name="deskripsi_explorasi" id="deskripsi_explorasi" rows="4" placeholder="Masukan Deskripsi Explorasi"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-(--flora-moss) focus:border-transparent transition @error('deskripsi_explorasi') border-red-400 @enderror">{{ old('deskripsi_explorasi', $team->deskripsi_team) }}</textarea>
                        @error('deskripsi_explorasi')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ======================== Anggota Tim ======================== --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">Anggota Tim</h2>
                        {{-- Error level array (min:1) --}}
                        @error('anggota')
                            <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="button" @click="tambahAnggota()"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-(--flora-moss) hover:opacity-75 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Anggota
                    </button>
                </div>

                <div class="px-6 py-6 space-y-4">
                    <template x-for="(item, index) in anggota" :key="index">
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 space-y-4"
                            :class="(hasError('anggota.' + index + '.collector_id') || hasError('anggota.' + index +
                                '.peran')) ?
                            'border-red-200 bg-red-50/30' : 'border-gray-100 bg-gray-50'">

                            {{-- Label baris + tombol hapus --}}
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest"
                                    x-text="'Anggota #' + (index + 1)"></span>
                                <button type="button" @click="hapusAnggota(index)" x-show="anggota.length > 1"
                                    class="inline-flex items-center gap-1 text-xs text-red-400 hover:text-red-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- ===== Search + Dropdown Collector ===== --}}
                                <div class="relative z-10">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota</label>

                                    <input type="hidden" :name="'anggota[' + index + '][collector_id]'"
                                        :value="item.collector_id">

                                    <div class="relative">
                                        <input type="text" x-model="searches[index]" @focus="openDropdown = index"
                                            @input="openDropdown = index; item.collector_id = ''"
                                            @blur="setTimeout(() => { if (openDropdown === index) openDropdown = null }, 150)"
                                            placeholder="Cari nama anggota..." autocomplete="off"
                                            class="w-full border rounded-lg px-3 py-2.5 pr-8 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-(--flora-moss) focus:border-transparent transition"
                                            :class="item.collector_id ?
                                                'border-green-400 bg-green-50' :
                                                (hasError('anggota.' + index + '.collector_id') ?
                                                    'border-red-400 bg-white' : 'border-gray-300')">

                                        <div class="absolute inset-y-0 right-2.5 flex items-center pointer-events-none">
                                            <template x-if="item.collector_id">
                                                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </template>
                                            <template x-if="!item.collector_id">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Error collector_id --}}
                                    <p x-show="hasError('anggota.' + index + '.collector_id')"
                                        x-text="getError('anggota.' + index + '.collector_id')"
                                        class="mt-1 text-xs text-red-500"></p>

                                    {{-- Dropdown list --}}
                                    <div x-show="openDropdown === index"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">

                                        <template x-if="filteredCollectors(index).length === 0">
                                            <div class="px-4 py-3 text-sm text-gray-400 text-center">
                                                Tidak ada collector ditemukan
                                            </div>
                                        </template>

                                        <template x-for="collector in filteredCollectors(index)" :key="collector.id">
                                            <button type="button" @mousedown.prevent="pilihCollector(index, collector)"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition text-left">
                                                <span
                                                    class="w-7 h-7 rounded-full bg-(--flora-moss)/10 text-(--flora-moss) text-xs font-semibold flex items-center justify-center shrink-0"
                                                    x-text="collector.nama.charAt(0).toUpperCase()"></span>
                                                <span x-text="collector.nama"></span>
                                                <span class="text-gray-400 text-xs" x-text="collector.Initial"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- ===== Peran / Jabatan ===== --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Peran / Jabatan</label>
                                    <input type="text" :name="'anggota[' + index + '][peran]'" x-model="item.peran"
                                        placeholder="Masukan Peran atau Jabatan"
                                        class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-(--flora-moss) focus:border-transparent transition"
                                        :class="hasError('anggota.' + index + '.peran') ? 'border-red-400' : 'border-gray-300'">

                                    {{-- Error peran --}}
                                    <p x-show="hasError('anggota.' + index + '.peran')"
                                        x-text="getError('anggota.' + index + '.peran')"
                                        class="mt-1 text-xs text-red-500"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 bg-(--flora-moss) text-white text-sm font-medium rounded-lg hover:opacity-90 transition">
                    Simpan
                </button>
            </div>

        </form>
    </div>
@endsection
