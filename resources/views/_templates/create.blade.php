{{--
|=============================================================================
| TEMPLATE: CREATE VIEW — STANDAR FDM BULUNGAN
|=============================================================================
|
| CARA PAKAI:
|   1. Salin file ini ke folder modul yang sesuai (misal: admin/nama-modul/create.blade.php)
|   2. Ganti semua teks [DALAM KURUNG] sesuai modul
|   3. Pilih pola form yang sesuai:
|      - FORM BIASA       : Satu card dengan banyak field (paling umum)
|      - FORM MULTI-CARD  : Beberapa card yang dikelompokkan per seksi
|      - FORM MULTI-STEP  : Wizard dengan step indicator (untuk form kompleks)
|   4. Hapus pola yang tidak dipakai
|   5. Hapus komentar panduan ini setelah selesai
|
| PANDUAN CEPAT:
|   - [NamaModul]      : Nama modul yang mudah dibaca, contoh: "Kolektor"
|   - [namaModul]      : Nama variabel/entitas kecil, contoh: "kolektor"
|   - [route.prefix]   : Prefix route, contoh: 'collector', 'tim-explorasi'
|   - [SectionName]    : Nama seksi dalam form, contoh: "Informasi Dasar"
|
|=============================================================================
--}}
@extends('layout.admin')
@section('content')

    <div class="px-4 py-6 mx-auto">

        {{-- ================================================================ --}}
        {{-- PAGE HEADER                                                      --}}
        {{-- Kiri: Judul + subjudul | Kanan: Tombol Kembali                  --}}
        {{-- WAJIB ada di semua halaman create                               --}}
        {{-- ================================================================ --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Tambah [NamaModul] Baru
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    [Deskripsi singkat halaman ini]
                </p>
            </div>
            <a href="{{ route('[route.prefix].index') }}"
               class="inline-flex items-center gap-2 text-sm text-[var(--flora-stone)] hover:text-[var(--flora-moss)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>

        {{-- ================================================================ --}}
        {{-- POLA A: FORM BIASA (Single Card)                                --}}
        {{-- Gunakan untuk form sederhana dengan sedikit field               --}}
        {{-- ================================================================ --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card Header — label seksi form --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                    Informasi [NamaModul]
                </h2>
            </div>

            <form action="{{ route('[route.prefix].store') }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf

                {{-- ===== Field: Input Teks ===== --}}
                <div>
                    <label for="[field_name]" class="block text-sm font-medium text-gray-700 mb-1">
                        [Label Field]
                        <span class="text-red-500 ml-0.5">*</span> {{-- hapus jika tidak wajib --}}
                    </label>
                    <input
                        type="text"
                        id="[field_name]"
                        name="[field_name]"
                        value="{{ old('[field_name]') }}"
                        placeholder="[Contoh isian...]"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition @error('[field_name]') border-red-400 @enderror">
                    <p class="mt-1 text-xs text-gray-400">[Hint text opsional]</p>
                    @error('[field_name]')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Field: Grid 2 Kolom ===== --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="[field_a]" class="block text-sm font-medium text-gray-700 mb-1">
                            [Label A] <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input
                            type="text"
                            id="[field_a]"
                            name="[field_a]"
                            value="{{ old('[field_a]') }}"
                            placeholder="[Placeholder A]"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                   focus:border-transparent transition @error('[field_a]') border-red-400 @enderror">
                        @error('[field_a]')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="[field_b]" class="block text-sm font-medium text-gray-700 mb-1">
                            [Label B] <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input
                            type="text"
                            id="[field_b]"
                            name="[field_b]"
                            value="{{ old('[field_b]') }}"
                            placeholder="[Placeholder B]"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                   focus:border-transparent transition @error('[field_b]') border-red-400 @enderror">
                        @error('[field_b]')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ===== Field: Dropdown/Select ===== --}}
                <div>
                    <label for="[select_field]" class="block text-sm font-medium text-gray-700 mb-1">
                        [Label Select] <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <select
                        id="[select_field]"
                        name="[select_field]"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 bg-white
                               focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)] focus:border-transparent
                               transition @error('[select_field]') border-red-400 @enderror">
                        <option value="" disabled selected>-- Pilih [NamaOpsi] --</option>
                        @foreach ($options as $option)
                            <option value="{{ $option->id }}" {{ old('[select_field]') == $option->id ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('[select_field]')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Field: Textarea ===== --}}
                <div>
                    <label for="[textarea_field]" class="block text-sm font-medium text-gray-700 mb-1">
                        [Label Textarea]
                    </label>
                    <textarea
                        id="[textarea_field]"
                        name="[textarea_field]"
                        rows="4"
                        placeholder="[Placeholder...]"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition resize-none @error('[textarea_field]') border-red-400 @enderror">{{ old('[textarea_field]') }}</textarea>
                    @error('[textarea_field]')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Field: Radio Button ===== --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        [Label Radio]
                    </label>
                    <div class="flex items-center gap-6 mt-2.5">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="[radio_field]"
                                value="1"
                                {{ old('[radio_field]', '1') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-[var(--flora-moss)] border-gray-300 focus:ring-[var(--flora-moss)]">
                            <span class="text-sm text-gray-700">[Opsi A]</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="[radio_field]"
                                value="0"
                                {{ old('[radio_field]', '1') == '0' ? 'checked' : '' }}
                                class="w-4 h-4 text-[var(--flora-moss)] border-gray-300 focus:ring-[var(--flora-moss)]">
                            <span class="text-sm text-gray-700">[Opsi B]</span>
                        </label>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400">[Hint untuk radio button]</p>
                    @error('[radio_field]')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Field: Input Angka ===== --}}
                <div>
                    <label for="[number_field]" class="block text-sm font-medium text-gray-700 mb-1">
                        [Label Angka]
                    </label>
                    <input
                        type="number"
                        id="[number_field]"
                        name="[number_field]"
                        value="{{ old('[number_field]', 0) }}"
                        min="0"
                        placeholder="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition @error('[number_field]') border-red-400 @enderror">
                    @error('[number_field]')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Field: Tanggal ===== --}}
                <div>
                    <label for="[date_field]" class="block text-sm font-medium text-gray-700 mb-1">
                        [Label Tanggal] <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input
                        type="date"
                        id="[date_field]"
                        name="[date_field]"
                        value="{{ old('[date_field]') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition @error('[date_field]') border-red-400 @enderror">
                    @error('[date_field]')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ================================================================ --}}
                {{-- FORM FOOTER — Tombol Batal + Simpan                             --}}
                {{-- WAJIB ada di semua form, selalu di bagian paling bawah card     --}}
                {{-- ================================================================ --}}
                <div class="border-t border-gray-100 pt-5 flex items-center justify-end gap-3">
                    <a href="{{ route('[route.prefix].index') }}"
                       class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300
                              rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-[var(--flora-moss)] rounded-lg
                               hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:ring-offset-2 transition shadow-sm">
                        Simpan [NamaModul]
                    </button>
                </div>

            </form>
        </div>
        {{-- [/POLA A] --}}


        {{-- ================================================================ --}}
        {{-- POLA B: FORM MULTI-CARD (Beberapa Seksi)                        --}}
        {{-- Gunakan untuk form dengan field yang bisa dikelompokkan          --}}
        {{-- Contoh: Timexplorasi (Informasi Tim + Anggota Tim)               --}}
        {{-- ================================================================ --}}

        <form action="{{ route('[route.prefix].store') }}" method="POST">
            @csrf

            {{-- Card Seksi 1 --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        [SectionName 1]
                    </h2>
                </div>
                <div class="px-6 py-6 space-y-6">
                    {{-- ... field-field seksi 1 ... --}}
                </div>
            </div>

            {{-- Card Seksi 2 --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                {{-- Header seksi 2 bisa juga punya tombol di kanan --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                        [SectionName 2]
                    </h2>
                    {{-- Contoh: tombol tambah baris dinamis --}}
                    <button type="button"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--flora-moss)] hover:opacity-75 transition">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Baris
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    {{-- ... field-field seksi 2 ... --}}
                </div>
            </div>

            {{-- Form Footer — di luar card, posisi paling bawah --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('[route.prefix].index') }}"
                   class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300
                          rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-[var(--flora-moss)] rounded-lg
                           hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                           focus:ring-offset-2 transition shadow-sm">
                    Simpan [NamaModul]
                </button>
            </div>
        </form>
        {{-- [/POLA B] --}}

    </div>{{-- end container --}}
@endsection
