@extends('layout.admin')
@section('content')
    @php
        $oldRows = old('ordinal', [
            [
                'label' => '',
                'nilai' => '',
                'operator' => 'eq',
                'range_from' => '',
                'range_to' => '',
            ],
        ]);
        $oldSkala = old('skala', '');
        $oldPreferenceFunction = old('preference_function', '');
    @endphp
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
    <div class="px-4 py-6 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Tambah Kriteria Baru
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Tambahkan kriteria baru untuk digunakan dalam proses evaluasi dan perankingan tanaman.
                </p>
            </div>
            <a href="{{ route('criteria.index') }}"
                class="inline-flex items-center gap-2 text-sm text-[var(--flora-stone)] hover:text-[var(--flora-moss)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('criteria.store') }}" method="POST" x-data="criteriaForm()">
            @csrf

            {{-- ============================================================ --}}
            {{-- CARD 1 — INFORMASI KRITERIA --}}
            {{-- ============================================================ --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        Informasi Kriteria
                    </h2>
                </div>
                <div class="px-6 py-6 space-y-5">

                    {{-- Nama Kriteria + Tipe --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="nama_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Kriteria <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_kriteria" name="nama_kriteria" value="{{ old('nama_kriteria') }}"
                                placeholder="Contoh: Tinggi Batang"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                       placeholder-gray-400 focus:outline-none focus:ring-2
                                       focus:ring-[var(--flora-moss)] focus:border-transparent transition
                                       @error('nama_kriteria') border-red-400 @enderror">
                            @error('nama_kriteria')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipe <span class="text-red-500">*</span>
                            </label>
                            <select id="tipe" name="tipe"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                       bg-white focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                       focus:border-transparent transition
                                       @error('tipe') border-red-400 @enderror">
                                <option value="" disabled selected>-- Pilih Tipe --</option>
                                <option value="benefit" {{ old('tipe') == 'benefit' ? 'selected' : '' }}>
                                    Benefit — semakin besar semakin baik
                                </option>
                                <option value="cost" {{ old('tipe') == 'cost' ? 'selected' : '' }}>
                                    Cost — semakin kecil semakin baik
                                </option>
                            </select>
                            @error('tipe')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Satuan + Skala --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">
                                Satuan
                            </label>
                            <input type="text" id="satuan" name="satuan" value="{{ old('satuan') }}"
                                placeholder="Contoh: cm, bulan, kg (kosongkan jika tidak ada)"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                       placeholder-gray-400 focus:outline-none focus:ring-2
                                       focus:ring-[var(--flora-moss)] focus:border-transparent transition
                                       @error('satuan') border-red-400 @enderror">
                            @error('satuan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="skala" class="block text-sm font-medium text-gray-700 mb-1">
                                Skala <span class="text-red-500">*</span>
                            </label>
                            <select id="skala" name="skala" x-model="skala"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                       bg-white focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                       focus:border-transparent transition
                                       @error('skala') border-red-400 @enderror">
                                <option value="" disabled selected>-- Pilih Skala --</option>
                                <option value="numerik" {{ old('skala') == 'numerik' ? 'selected' : '' }}>
                                    Numerik — input angka langsung
                                </option>
                                <option value="ordinal" {{ old('skala') == 'ordinal' ? 'selected' : '' }}>
                                    Ordinal — pilihan bertingkat
                                </option>
                            </select>
                            @error('skala')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Bobot --}}
                    <div>
                        <label for="bobot" class="block text-sm font-medium text-gray-700 mb-1">
                            Bobot <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="bobot" name="bobot" value="{{ old('bobot', 0) }}" min="0"
                            max="1" step="0.01" placeholder="0.00 – 1.00"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2
                                   focus:ring-[var(--flora-moss)] focus:border-transparent transition
                                   @error('bobot') border-red-400 @enderror">
                        <p class="mt-1.5 text-xs text-gray-400">
                            Total bobot semua kriteria aktif harus = 1.00
                        </p>
                        @error('bobot')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fungsi Preferensi --}}
                    <div>
                        <label for="preference_function" class="block text-sm font-medium text-gray-700 mb-1">
                            Fungsi Preferensi <span class="text-red-500">*</span>
                        </label>
                        <select id="preference_function" name="preference_function" x-model="preferenceFunction"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   bg-white focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                   focus:border-transparent transition
                                   @error('preference_function') border-red-400 @enderror">
                            <option value="" disabled selected>-- Pilih Fungsi Preferensi --</option>
                            <option value="usual" {{ old('preference_function') == 'usual' ? 'selected' : '' }}>Usual
                            </option>
                            <option value="quasi" {{ old('preference_function') == 'quasi' ? 'selected' : '' }}>Quasi
                            </option>
                            <option value="linear" {{ old('preference_function') == 'linear' ? 'selected' : '' }}>Linear
                            </option>
                            <option value="level" {{ old('preference_function') == 'level' ? 'selected' : '' }}>Level
                            </option>
                            <option value="gaussian"{{ old('preference_function') == 'gaussian' ? 'selected' : '' }}>
                                Gaussian</option>
                            <option value="v_shape" {{ old('preference_function') == 'v_shape' ? 'selected' : '' }}>
                                V-Shape</option>
                        </select>
                        @error('preference_function')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- CARD 2 — PARAMETER FUNGSI PREFERENSI --}}
            {{-- ============================================================ --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6"
                x-show="preferenceFunction !== ''" x-transition x-cloak>
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        Parameter Fungsi Preferensi
                    </h2>
                </div>
                <div class="px-6 py-6 space-y-5">

                    {{-- Usual — tidak butuh parameter --}}
                    <div x-show="preferenceFunction === 'usual'" x-transition x-cloak>
                        <div
                            class="flex items-center gap-2 px-4 py-3 rounded-lg bg-gray-50
                                    text-sm text-gray-500 border border-gray-200">
                            <i class="fa-solid fa-circle-info text-[var(--flora-moss)]"></i>
                            Fungsi <strong>Usual</strong> tidak memerlukan parameter tambahan.
                            Jika selisih d &gt; 0 maka preferensi = 1, jika d ≤ 0 maka preferensi = 0.
                        </div>
                    </div>

                    {{-- Param Q — quasi, linear, level --}}
                    <div x-show="['quasi','linear','level'].includes(preferenceFunction)" x-transition x-cloak>
                        <label for="param_q" class="block text-sm font-medium text-gray-700 mb-1">
                            Parameter Q — Indifference Threshold
                        </label>
                        <input type="number" id="param_q" name="param_q" value="{{ old('param_q') }}"
                            step="0.0001" min="0" placeholder="Contoh: 5"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2
                                   focus:ring-[var(--flora-moss)] focus:border-transparent transition
                                   @error('param_q') border-red-400 @enderror">
                        <p class="mt-1.5 text-xs text-gray-400">
                            Selisih ≤ Q dianggap tidak ada perbedaan (preferensi = 0).
                        </p>
                        @error('param_q')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Param P — linear, level, v_shape --}}
                    <div x-show="['linear','level','v_shape'].includes(preferenceFunction)" x-transition x-cloak>
                        <label for="param_p" class="block text-sm font-medium text-gray-700 mb-1">
                            Parameter P — Preference Threshold
                        </label>
                        <input type="number" id="param_p" name="param_p" value="{{ old('param_p') }}"
                            step="0.0001" min="0" placeholder="Contoh: 10"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2
                                   focus:ring-[var(--flora-moss)] focus:border-transparent transition
                                   @error('param_p') border-red-400 @enderror">
                        <p class="mt-1.5 text-xs text-gray-400">
                            Selisih ≥ P dianggap preferensi penuh (preferensi = 1).
                        </p>
                        @error('param_p')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Param Sigma — gaussian --}}
                    <div x-show="preferenceFunction === 'gaussian'" x-transition x-cloak>
                        <label for="param_sigma" class="block text-sm font-medium text-gray-700 mb-1">
                            Parameter Sigma (σ)
                        </label>
                        <input type="number" id="param_sigma" name="param_sigma" value="{{ old('param_sigma') }}"
                            step="0.0001" min="0" placeholder="Contoh: 2.5"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2
                                   focus:ring-[var(--flora-moss)] focus:border-transparent transition
                                   @error('param_sigma') border-red-400 @enderror">
                        <p class="mt-1.5 text-xs text-gray-400">
                            Mengatur sensitivitas kurva Gaussian terhadap perbedaan nilai.
                        </p>
                        @error('param_sigma')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- CARD 3 — SKALA ORDINAL (muncul jika skala = ordinal) --}}
            {{-- ============================================================ --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6"
                x-show="skala === 'ordinal'" x-transition x-cloak>
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        Skala Ordinal
                    </h2>
                    <button type="button" @click="addRow()"
                        class="inline-flex items-center gap-1.5 text-sm font-medium
                               text-[var(--flora-moss)] hover:opacity-75 transition">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Baris
                    </button>
                </div>
                <div class="px-6 py-6 space-y-3">

                    {{-- Header kolom --}}
                    <div class="grid grid-cols-12 gap-3 mb-1">
                        <div class="col-span-1 text-xs font-medium text-gray-400 uppercase">#</div>
                        <div class="col-span-3 text-xs font-medium text-gray-400 uppercase">Label</div>
                        <div class="col-span-1 text-xs font-medium text-gray-400 uppercase">Nilai</div>
                        <div class="col-span-3 text-xs font-medium text-gray-400 uppercase">Operator</div>
                        <div class="col-span-2 text-xs font-medium text-gray-400 uppercase">Range From</div>
                        <div class="col-span-2 text-xs font-medium text-gray-400 uppercase">Range To</div>
                    </div>

                    <template x-for="(row, index) in rows" :key="index">
                        <div class="grid grid-cols-12 gap-3 items-center">

                            {{-- Nomor urut --}}
                            <div class="col-span-1 text-sm text-gray-400 text-center" x-text="index + 1"></div>

                            {{-- Label --}}
                            <div class="col-span-3">
                                <input type="text" :name="`ordinal[${index}][label]`" x-model="row.label"
                                    placeholder="Contoh: Baik"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                           text-gray-800 placeholder-gray-400 focus:outline-none
                                           focus:ring-2 focus:ring-[var(--flora-moss)]
                                           focus:border-transparent transition">
                            </div>

                            {{-- Nilai --}}
                            <div class="col-span-1">
                                <input type="number" :name="`ordinal[${index}][nilai]`" x-model="row.nilai"
                                    placeholder="1" min="1"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                           text-gray-800 placeholder-gray-400 focus:outline-none
                                           focus:ring-2 focus:ring-[var(--flora-moss)]
                                           focus:border-transparent transition">
                            </div>

                            {{-- Operator --}}
                            <div class="col-span-3">
                                <select :name="`ordinal[${index}][operator]`" x-model="row.operator"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                           text-gray-800 bg-white focus:outline-none focus:ring-2
                                           focus:ring-[var(--flora-moss)] focus:border-transparent transition">
                                    <option value="eq">= Sama dengan</option>
                                    <option value="lt">&lt; Kurang dari</option>
                                    <option value="lte">&lt;= Kurang dari sama dengan</option>
                                    <option value="gt">&gt; Lebih dari</option>
                                    <option value="gte">&gt;= Lebih dari sama dengan</option>
                                    <option value="between">Between (Antara)</option>
                                </select>
                            </div>

                            {{-- Range From — muncul jika operator != eq --}}
                            <div class="col-span-2">
                                <input type="number" :name="`ordinal[${index}][range_from]`" x-model="row.range_from"
                                    placeholder="Dari" step="0.0001" x-show="row.operator !== 'eq'" x-transition
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                           text-gray-800 placeholder-gray-400 focus:outline-none
                                           focus:ring-2 focus:ring-[var(--flora-moss)]
                                           focus:border-transparent transition">
                                <span x-show="row.operator === 'eq'" class="text-xs text-gray-300 px-3">—</span>
                            </div>

                            {{-- Range To — muncul hanya jika operator = between --}}
                            <div class="col-span-2 flex items-center gap-2">
                                <input type="number" :name="`ordinal[${index}][range_to]`" x-model="row.range_to"
                                    placeholder="Sampai" step="0.0001" x-show="row.operator === 'between'" x-transition
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                           text-gray-800 placeholder-gray-400 focus:outline-none
                                           focus:ring-2 focus:ring-[var(--flora-moss)]
                                           focus:border-transparent transition">
                                <span x-show="row.operator !== 'between'" class="text-xs text-gray-300 px-3">—</span>

                                {{-- Hapus baris --}}
                                <button type="button" @click="removeRow(index)" :disabled="rows.length === 1"
                                    class="flex-shrink-0 p-1.5 text-gray-400 hover:text-red-500
                                           disabled:opacity-30 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Hidden urutan --}}
                            <input type="hidden" :name="`ordinal[${index}][urutan]`" :value="index + 1">

                        </div>
                    </template>

                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6"
                x-show="skala === 'numerik'" x-transition x-cloak>
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        Skala Numerik
                    </h2>
                </div>
                <div class="px-6 py-6">
                    <div
                        class="flex items-center gap-2 px-4 py-3 rounded-lg bg-gray-50
                                text-sm text-gray-500 border border-gray-200">
                        <i class="fa-solid fa-circle-info text-[var(--flora-moss)]"></i>
                        Skala <strong>Numerik</strong> tidak memerlukan konfigurasi tambahan.
                        Nilai akan diinput langsung sebagai angka saat proses inspeksi berlangsung.
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- FORM FOOTER --}}
            {{-- ============================================================ --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('criteria.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300
                           rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-[var(--flora-moss)] rounded-lg
                           hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                           focus:ring-offset-2 transition shadow-sm">
                    Simpan Kriteria
                </button>
            </div>

        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('criteriaForm', () => ({
                    skala: @json($oldSkala),
                    preferenceFunction: @json($oldPreferenceFunction),
                    rows: @json($oldRows),

                    addRow() {
                        this.rows.push({
                            label: '',
                            nilai: '',
                            operator: 'eq',
                            range_from: '',
                            range_to: '',
                        });
                    },

                    removeRow(index) {
                        if (this.rows.length > 1) {
                            this.rows.splice(index, 1);
                        }
                    },

                    init() {

                        this.$watch('skala', (val) => {
                            if (val === 'ordinal' && this.rows.length === 0) {
                                this.rows = [{
                                    label: '',
                                    nilai: '',
                                    operator: 'eq',
                                    range_from: '',
                                    range_to: ''
                                }];
                            }
                        });
                    }
                }))
            })
        </script>
    @endpush
@endsection
