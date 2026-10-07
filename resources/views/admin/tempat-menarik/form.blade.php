@if ($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700" role="alert">
        <p class="font-semibold">Periksa kembali data tempat.</p>
        <ul class="list-disc pl-5 mt-2">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-sm font-medium text-gray-700">Informasi tempat</h2>
    </div>
    <div class="px-4 sm:px-6 py-6 space-y-5">
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama tempat <span class="text-red-700">*</span></label>
            <input id="nama" name="nama" value="{{ old('nama', $place->nama) }}" maxlength="255" required class="place-admin-input" @error('nama') aria-invalid="true" aria-describedby="nama-error" @enderror>
            @error('nama')<p id="nama-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-700">*</span></label>
            <textarea id="deskripsi" name="deskripsi" rows="6" maxlength="20000" required class="place-admin-input" @error('deskripsi') aria-invalid="true" aria-describedby="deskripsi-error" @enderror>{{ old('deskripsi', $place->deskripsi) }}</textarea>
            <p class="mt-1.5 text-sm text-gray-600">Jelaskan tempat, hal yang dapat dilihat, dan informasi kunjungan yang tersedia.</p>
            @error('deskripsi')<p id="deskripsi-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-700">*</span></label>
                <select id="kategori" name="kategori" required class="place-admin-input" @error('kategori') aria-invalid="true" @enderror>
                    @foreach (\App\Models\TempatMenarik::CATEGORIES as $value => $label)
                        <option value="{{ $value }}" @selected(old('kategori', $place->kategori) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('kategori')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="urutan" class="block text-sm font-medium text-gray-700 mb-1">Urutan tampil <span class="text-red-700">*</span></label>
                <input id="urutan" name="urutan" type="number" min="0" max="4294967295" value="{{ old('urutan', $place->urutan) }}" required class="place-admin-input" @error('urutan') aria-invalid="true" @enderror>
                <p class="mt-1.5 text-sm text-gray-600">Angka lebih kecil ditampilkan lebih dahulu.</p>
                @error('urutan')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto tempat @if (!$place->exists)<span class="text-red-700">*</span>@endif</label>
            @if ($place->exists)
                <x-tempat-menarik-photo class="place-admin-photo mb-3" :url="$photoUrl" :name="$place->nama" :eager="true" />
            @endif
            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" @required(!$place->exists) class="place-admin-input" aria-describedby="foto-help" @error('foto') aria-invalid="true" @enderror>
            <p id="foto-help" class="mt-1.5 text-sm text-gray-600">JPG, PNG, atau WebP. Maksimal 2 MB. {{ $place->exists ? 'Kosongkan jika tetap memakai foto saat ini.' : '' }}</p>
            @error('foto')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>
        <fieldset class="space-y-2">
            <legend class="text-sm font-medium text-gray-700 mb-1">Publikasi</legend>
            <input type="hidden" name="is_active" value="0">
            <label class="flex items-center gap-3 min-h-11 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $place->is_active)) class="h-5 w-5 accent-(--flora-moss)">
                Aktif: dapat dilihat pengunjung
            </label>
            <input type="hidden" name="is_featured" value="0">
            <label class="flex items-center gap-3 min-h-11 text-sm text-gray-700">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $place->is_featured)) class="h-5 w-5 accent-(--flora-moss)">
                Tampilkan di landing page
            </label>
            <p class="text-sm text-gray-600">Landing menampilkan hingga tiga tempat aktif sesuai urutan, lalu yang terbaru.</p>
            @error('is_active')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
            @error('is_featured')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
        </fieldset>
    </div>
</div>
<div class="flex flex-wrap justify-end gap-3">
    <a href="{{ route('admin.tempat-menarik.index') }}" class="inline-flex min-h-11 items-center px-4 py-2 text-sm text-gray-700 border border-gray-500 rounded-lg hover:bg-gray-50 transition-colors">Batal</a>
    <button type="submit" class="inline-flex min-h-11 items-center px-6 py-2 text-sm font-semibold text-white bg-(--flora-moss) rounded-lg hover:bg-(--flora-bark) transition-colors">{{ $place->exists ? 'Simpan perubahan' : 'Simpan tempat' }}</button>
</div>
