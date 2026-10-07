@extends('layout.admin')

@section('content')
<div class="px-4 py-6 mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                Tambah Berita Baru
            </h1>
            <p class="text-sm text-[var(--flora-stone)] mt-1">
                Tambahkan berita baru untuk ditampilkan pada website.
            </p>
        </div>

        <a href="{{ route('admin.berita.index') }}"
           class="inline-flex items-center gap-2 text-sm text-[var(--flora-stone)]
                  hover:text-[var(--flora-moss)] transition-colors">

            <svg class="w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>

            Kembali
        </a>
    </div>


    {{-- Main Card --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-medium text-[var(--flora-stone)]
                       uppercase tracking-widest">
                Informasi Berita
            </h2>
        </div>


        {{-- Form --}}
        <form action="{{ route('admin.berita.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="px-6 py-6 space-y-6">

            @csrf


            {{-- ========================================================= --}}
            {{-- Judul --}}
            {{-- ========================================================= --}}
            <div>
                <label for="judul"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Judul Berita
                    <span class="text-red-500 ml-0.5">*</span>
                </label>

                <input
                    type="text"
                    id="judul"
                    name="judul"
                    value="{{ old('judul') }}"
                    placeholder="Masukkan judul berita"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                           text-sm text-gray-800 placeholder-gray-400
                           focus:outline-none focus:ring-2
                           focus:ring-[var(--flora-moss)]
                           focus:border-transparent transition
                           @error('judul') border-red-400 @enderror">

                <p class="mt-1 text-xs text-gray-400">
                    Gunakan judul yang singkat, jelas, dan menggambarkan isi berita.
                </p>

                @error('judul')
                    <p class="mt-1 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- ========================================================= --}}
            {{-- Slug + Kategori --}}
            {{-- ========================================================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Slug --}}
                <div>
                    <label for="slugs"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Slug
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>

                    <input
                        type="text"
                        id="slugs"
                        name="slugs"
                        value="{{ old('slugs') }}"
                        placeholder="contoh-judul-berita"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                               text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2
                               focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition
                               @error('slugs') border-red-400 @enderror">

                    <p class="mt-1 text-xs text-gray-400">
                        Digunakan sebagai alamat URL berita.
                    </p>

                    @error('slugs')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Kategori --}}
                <div>
                    <label for="kategori_berita_id"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Kategori Berita
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                
                    <select
                        id="kategori_berita_id"
                        name="kategori_berita_id"
                        class="w-full"
                        required>
                
                        <option value="">Pilih atau ketik kategori...</option>
                
                        @foreach ($kategoriBerita as $kategori)
                            <option
                                value="{{ $kategori->id }}"
                                {{ old('kategori_berita_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                
                    </select>
                
                    <p class="mt-1 text-xs text-gray-400">
                        Pilih kategori yang tersedia atau ketik kategori baru lalu tekan Enter.
                    </p>
                
                    @error('kategori_berita_id')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Image --}}
            {{-- ========================================================= --}}
            <div>
                <label for="image_url"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Gambar Berita
                </label>

                <input
                    type="file"
                    id="image_url"
                    name="image_url"
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-gray-600
                           border border-gray-300 rounded-lg
                           file:mr-4 file:py-2.5 file:px-4
                           file:border-0
                           file:text-sm file:font-medium
                           file:bg-gray-50
                           file:text-[var(--flora-moss)]
                           hover:file:bg-gray-100
                           focus:outline-none">

                <p class="mt-1 text-xs text-gray-400">
                    Format JPG, PNG, atau WEBP. Gunakan gambar yang relevan dengan berita.
                </p>

                @error('image_url')
                    <p class="mt-1 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- ========================================================= --}}
            {{-- Content --}}
            {{-- ========================================================= --}}
            <div>
                <label for="content"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Isi Berita
                    <span class="text-red-500 ml-0.5">*</span>
                </label>

                {{-- Trix --}}
                <input id="content"
                       type="hidden"
                       name="content"
                       value="{{ old('content') }}">

                <trix-editor
                    input="content"
                    class="trix-content border border-gray-300 rounded-lg
                           min-h-[300px]
                           focus-within:ring-2
                           focus-within:ring-[var(--flora-moss)]
                           focus-within:border-transparent">
                </trix-editor>

                <p class="mt-1 text-xs text-gray-400">
                    Tulis isi berita menggunakan editor teks di atas.
                </p>

                @error('content')
                    <p class="mt-1 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- ========================================================= --}}
            {{-- Status + Visitor --}}
            {{-- ========================================================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Status --}}
                <div>
                    <label for="status"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                               text-sm text-gray-800 bg-white
                               focus:outline-none focus:ring-2
                               focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition
                               @error('status') border-red-400 @enderror">

                        <option value="draft"
                            {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="public"
                            {{ old('status') === 'public' ? 'selected' : '' }}>
                            Dipublikasikan
                        </option>
                        <option value="private"
                            {{ old('status') === 'private' ? 'selected' : '' }}>
                            Private
                        </option>

                    </select>

                    <p class="mt-1 text-xs text-gray-400">
                        Berita draft tidak ditampilkan kepada pengunjung.
                    </p>

                    @error('status')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Visitor --}}
                <div>
                    <label for="visitor"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Jumlah Pengunjung
                    </label>

                    <input
                        type="number"
                        id="visitor"
                        name="visitor"
                        value="{{ old('visitor', 0) }}"
                        min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                               text-sm text-gray-800
                               focus:outline-none focus:ring-2
                               focus:ring-[var(--flora-moss)]
                               focus:border-transparent transition
                               @error('visitor') border-red-400 @enderror disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed"" disabled>

                    <p class="mt-1 text-xs text-gray-400">
                        Biasanya dimulai dari 0 dan bertambah ketika berita dibaca.
                    </p>

                    @error('visitor')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Footer --}}
            {{-- ========================================================= --}}
            <div class="border-t border-gray-100 pt-5
                        flex items-center justify-end gap-3">

                <a href="{{ route('admin.berita.index') }}"
                   class="px-4 py-2.5 text-sm font-medium
                          text-gray-600 bg-white border border-gray-300
                          rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white
                           bg-[var(--flora-moss)] rounded-lg
                           hover:opacity-90
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[var(--flora-moss)]
                           focus:ring-offset-2
                           transition shadow-sm">
                    Simpan Berita
                </button>

            </div>

        </form>

    </div>
</div>


{{-- ========================================================= --}}
{{-- Choices.js + Trix --}}
{{-- ========================================================= --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        /*
         * Choices.js
         */
        const kategoriSelect = document.getElementById('kategori_berita_id');
        
        if (kategoriSelect) {
            const kategoriChoices = new Choices(kategoriSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Cari atau ketik kategori baru...',
                placeholder: true,
                placeholderValue: 'Pilih atau ketik kategori...',
                shouldSort: false,
                itemSelectText: '',
                noResultsText: 'Tekan Enter untuk menambahkan kategori baru',
                noChoicesText: 'Tidak ada kategori',
                allowHTML: false,
        
                addChoices: true,
                addItems: true,
                duplicateItemsAllowed: false,
        
                addItemText: (value) => {
                    return `Tekan Enter untuk menambahkan "<b>${value}</b>"`;
                }
            });
        
            kategoriSelect.addEventListener('search', function (event) {
                const value = event.detail.value?.trim();
        
                if (!value) {
                    return;
                }
            });
        }


        /*
         * Auto generate slug dari judul
         */
        const judul = document.getElementById('judul');
        const slug = document.getElementById('slugs');

        if (judul && slug) {

            judul.addEventListener('input', () => {

                // Jangan menimpa slug jika user sudah mengubahnya manual
                if (slug.dataset.manual === 'true') {
                    return;
                }

                slug.value = judul.value
                    .toLowerCase()
                    .trim()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            });

            slug.addEventListener('input', () => {
                slug.dataset.manual = 'true';
            });
        }


        /*
         * Trix
         *
         * Hindari upload attachment jika belum disediakan
         * endpoint upload.
         */
        document.addEventListener('trix-attachment-add', (event) => {

            const attachment = event.attachment;

            if (attachment.file) {
                attachment.remove();
            }

        });

    });
</script>
@endpush

@endsection