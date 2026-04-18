@extends('layout.admin')

@section('content')
<div class="mx-auto px-4 py-2">

    {{-- Page Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('user.index') }}"
           aria-label="Kembali ke daftar user"
           class="p-2 rounded-lg text-gray-400 hover:text-[var(--flora-moss)] hover:bg-gray-100 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--flora-teal)] focus-visible:outline-offset-2">
            <i class="fa-solid fa-arrow-left text-sm" aria-hidden="true"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                Tambahkan User
            </h1>
            <p class="text-sm text-[var(--flora-stone)] mt-0.5">
                Masukkan data user baru ke dalam sistem
            </p>
        </div>
    </div>

    {{-- Global Error Summary — WCAG 3.3.1 --}}
    @if($errors->any())
        <div role="alert"
             aria-labelledby="error-summary-title"
             class="mb-6 flex gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-base" aria-hidden="true"></i>
            </div>
            <div>
                <p id="error-summary-title" class="font-semibold text-red-700 mb-1">
                    Terdapat {{ $errors->count() }} kesalahan yang perlu diperbaiki:
                </p>
                <ul class="list-disc list-inside space-y-0.5 text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('user.store') }}"
              method="POST"
              enctype="multipart/form-data"
              novalidate
              aria-label="Form tambah user baru">
            @csrf

            {{-- ── Section: Informasi Akun ── --}}
            <fieldset class="p-6 border-b border-gray-100">
                <legend class="w-full text-sm font-semibold text-[var(--flora-moss)] py-5 pb-5 flex items-center gap-2.5">
                    <i class="fa-solid fa-user-circle text-[var(--flora-teal)] w-4 text-center" aria-hidden="true"></i>
                    Informasi Akun
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Name --}}
                    <div class="sm:col-span-2">
                        <label for="name"
                               class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Lengkap
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            <span class="sr-only">(wajib diisi)</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               autocomplete="name"
                               required
                               aria-required="true"
                               aria-describedby="name-hint{{ $errors->has('name') ? ' name-error' : '' }}"
                               aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                               placeholder="Masukkan nama lengkap"
                               class="w-full px-3.5 py-2.5 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent
                                      {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                        <p id="name-hint" class="mt-1.5 text-xs text-gray-400">Gunakan nama sesuai identitas resmi.</p>
                        @error('name')
                            <p id="name-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email"
                               class="block text-sm font-medium text-gray-700 mb-1.5">
                            Alamat Email
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            <span class="sr-only">(wajib diisi)</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               autocomplete="email"
                               required
                               aria-required="true"
                               aria-describedby="email-hint{{ $errors->has('email') ? ' email-error' : '' }}"
                               aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                               placeholder="contoh@email.com"
                               class="w-full px-3.5 py-2.5 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent
                                      {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                        <p id="email-hint" class="mt-1.5 text-xs text-gray-400">Digunakan untuk login ke sistem.</p>
                        @error('email')
                            <p id="email-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone_number"
                               class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nomor Telepon
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 text-sm pointer-events-none select-none" aria-hidden="true">+62</span>
                            <input type="tel"
                                   id="phone_number"
                                   name="phone_number"
                                   value="{{ old('phone_number') }}"
                                   autocomplete="tel"
                                   aria-describedby="phone-hint{{ $errors->has('phone_number') ? ' phone-error' : '' }}"
                                   aria-invalid="{{ $errors->has('phone_number') ? 'true' : 'false' }}"
                                   placeholder="812 3456 7890"
                                   class="w-full pl-12 pr-3.5 py-2.5 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent
                                          {{ $errors->has('phone_number') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                        </div>
                        <p id="phone-hint" class="mt-1.5 text-xs text-gray-400">Opsional. Format: 08xx atau tanpa angka 0.</p>
                        @error('phone_number')
                            <p id="phone-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </fieldset>

            {{-- ── Section: Keamanan ── --}}
            <fieldset class="p-6 border-b border-gray-100">
                <legend class="w-full text-sm font-semibold text-[var(--flora-moss)] py-5 pb-5 flex items-center gap-2.5">
                    <i class="fa-solid fa-lock text-[var(--flora-teal)] w-4 text-center" aria-hidden="true"></i>
                    Keamanan
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Password --}}
                    <div>
                        <label for="password"
                               class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kata Sandi
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            <span class="sr-only">(wajib diisi)</span>
                        </label>
                        <div class="relative">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   autocomplete="new-password"
                                   required
                                   aria-required="true"
                                   aria-describedby="password-hint{{ $errors->has('password') ? ' password-error' : '' }}"
                                   aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                   placeholder="Min. 8 karakter"
                                   class="w-full px-3.5 py-2.5 pr-11 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent
                                          {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            <button type="button"
                                    onclick="togglePassword('password', this)"
                                    aria-label="Tampilkan kata sandi"
                                    aria-pressed="false"
                                    class="absolute inset-y-0 right-0 w-10 flex items-center justify-center text-gray-400 hover:text-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--flora-teal)] rounded-r-lg">
                                <i class="fa-solid fa-eye text-sm" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p id="password-hint" class="mt-1.5 text-xs text-gray-400">Minimal 8 karakter, kombinasi huruf dan angka.</p>
                        @error('password')
                            <p id="password-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation"
                               class="block text-sm font-medium text-gray-700 mb-1.5">
                            Konfirmasi Kata Sandi
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            <span class="sr-only">(wajib diisi)</span>
                        </label>
                        <div class="relative">
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   autocomplete="new-password"
                                   required
                                   aria-required="true"
                                   aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                                   aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                   placeholder="Ulangi kata sandi"
                                   class="w-full px-3.5 py-2.5 pr-11 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent
                                          {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                            <button type="button"
                                    onclick="togglePassword('password_confirmation', this)"
                                    aria-label="Tampilkan konfirmasi kata sandi"
                                    aria-pressed="false"
                                    class="absolute inset-y-0 right-0 w-10 flex items-center justify-center text-gray-400 hover:text-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--flora-teal)] rounded-r-lg">
                                <i class="fa-solid fa-eye text-sm" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </fieldset>

            {{-- ── Section: Hak Akses & Status ── --}}
            <fieldset class="p-6 border-b border-gray-100">
                <legend class="w-full text-sm font-semibold text-[var(--flora-moss)] py-5 pb-5 flex items-center gap-2.5">
                    <i class="fa-solid fa-shield-halved text-[var(--flora-teal)] w-4 text-center" aria-hidden="true"></i>
                    Hak Akses &amp; Status
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Role --}}
                    <div>
                        <label for="roles_id"
                               class="block text-sm font-medium text-gray-700 mb-1.5">
                            Role
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            <span class="sr-only">(wajib diisi)</span>
                        </label>
                        <div class="relative">
                            <select id="roles_id"
                                    name="roles_id"
                                    required
                                    aria-required="true"
                                    aria-describedby="role-hint{{ $errors->has('roles_id') ? ' role-error' : '' }}"
                                    aria-invalid="{{ $errors->has('roles_id') ? 'true' : 'false' }}"
                                    class="w-full appearance-none px-3.5 py-2.5 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent
                                           {{ $errors->has('roles_id') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                <option value="" disabled {{ old('roles_id') ? '' : 'selected' }}>— Pilih role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('roles_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400" aria-hidden="true">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                        <p id="role-hint" class="mt-1.5 text-xs text-gray-400">Menentukan hak akses user dalam sistem.</p>
                        @error('roles_id')
                            <p id="role-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <p class="block text-sm font-medium text-gray-700 mb-2" id="status-label">
                            Status Akun
                            <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                            <span class="sr-only">(wajib diisi)</span>
                        </p>
                        <div role="radiogroup"
                             aria-labelledby="status-label"
                             aria-describedby="{{ $errors->has('account_status') ? 'status-error' : '' }}"
                             class="flex gap-3">

                            {{-- Active --}}
                            <label class="flex-1 flex items-center gap-3 px-4 py-3 border rounded-lg cursor-pointer transition-colors has-[:checked]:border-[var(--flora-teal)] has-[:checked]:bg-[var(--flora-teal)]/5 {{ old('account_status', 'active') === 'active' ? 'border-[var(--flora-teal)] bg-[var(--flora-teal)]/5' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio"
                                       name="account_status"
                                       id="status_active"
                                       value="active"
                                       {{ old('account_status', 'active') === 'active' ? 'checked' : '' }}
                                       class="sr-only">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0
                                    {{ old('account_status', 'active') === 'active' ? 'border-[var(--flora-teal)]' : 'border-gray-300' }}"
                                    aria-hidden="true">
                                    <span class="w-2 h-2 rounded-full {{ old('account_status', 'active') === 'active' ? 'bg-[var(--flora-teal)]' : '' }}"></span>
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Aktif</p>
                                    <p class="text-xs text-gray-400">User dapat login</p>
                                </div>
                            </label>

                            {{-- Inactive --}}
                            <label class="flex-1 flex items-center gap-3 px-4 py-3 border rounded-lg cursor-pointer transition-colors has-[:checked]:border-[var(--flora-teal)] has-[:checked]:bg-[var(--flora-teal)]/5 {{ old('account_status') === 'inactive' ? 'border-[var(--flora-teal)] bg-[var(--flora-teal)]/5' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio"
                                       name="account_status"
                                       id="status_inactive"
                                       value="inactive"
                                       {{ old('account_status') === 'inactive' ? 'checked' : '' }}
                                       class="sr-only">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0
                                    {{ old('account_status') === 'inactive' ? 'border-[var(--flora-teal)]' : 'border-gray-300' }}"
                                    aria-hidden="true">
                                    <span class="w-2 h-2 rounded-full {{ old('account_status') === 'inactive' ? 'bg-[var(--flora-teal)]' : '' }}"></span>
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Nonaktif</p>
                                    <p class="text-xs text-gray-400">User tidak dapat login</p>
                                </div>
                            </label>
                        </div>
                        @error('account_status')
                            <p id="status-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </fieldset>

            {{-- ── Section: Data Collector ── --}}
            <fieldset class="p-6 border-b border-gray-100">
                <legend class="w-full text-sm font-semibold text-[var(--flora-moss)] py-5 pb-5 flex items-center gap-2.5">
                    <i class="fa-solid fa-id-badge text-[var(--flora-teal)] w-4 text-center" aria-hidden="true"></i>
                    Data Collector
                </legend>

                {{-- Toggle checkbox --}}
                <label for="is_collector"
                       class="inline-flex items-start gap-3 mb-5 cursor-pointer group">
                    <div class="relative mt-0.5 shrink-0">
                        <input type="checkbox"
                               id="is_collector"
                               name="is_collector"
                               value="1"
                               {{ old('is_collector') ? 'checked' : '' }}
                               aria-describedby="collector-toggle-hint"
                               aria-controls="collector-fields"
                               class="peer sr-only">
                        {{-- Custom checkbox --}}
                        <div class="w-5 h-5 rounded-md border-2 border-gray-300 bg-white
                                    peer-checked:bg-[var(--flora-teal)] peer-checked:border-[var(--flora-teal)]
                                    peer-focus-visible:ring-2 peer-focus-visible:ring-[var(--flora-teal)] peer-focus-visible:ring-offset-2
                                    group-hover:border-[var(--flora-teal)]/60
                                    transition-all flex items-center justify-center"
                             aria-hidden="true">
                            <i class="fa-solid fa-check text-white text-[10px]" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 leading-tight">
                            Daftarkan sebagai Collector
                        </p>
                        <p id="collector-toggle-hint" class="text-xs text-gray-400 mt-0.5">
                            Data collector akan disimpan ke database terpisah.
                        </p>
                    </div>
                </label>

                {{-- Collector Fields --}}
                <div id="collector-fields"
                     aria-live="polite"
                     class="{{ old('is_collector') ? '' : 'hidden' }}">

                    <div class="rounded-xl border border-[var(--flora-sage-mid)] bg-gray-50/60 p-5">

                        <p class="text-xs font-semibold text-[var(--flora-stone)] uppercase tracking-wider mb-4">
                            Identitas Collector
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">

                            {{-- Collector Initial Name --}}
                            <div>
                                <label for="collector_initial_name"
                                       class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Inisial Collector
                                    <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                                    <span class="sr-only">(wajib diisi jika collector aktif)</span>
                                </label>
                                <input type="text"
                                       id="collector_initial_name"
                                       name="collector_initial_name"
                                       value="{{ old('collector_initial_name') }}"
                                       autocomplete="off"
                                       aria-describedby="initial-hint{{ $errors->has('collector_initial_name') ? ' initial-error' : '' }}"
                                       aria-invalid="{{ $errors->has('collector_initial_name') ? 'true' : 'false' }}"
                                       placeholder="Cth: JD"
                                       maxlength="10"
                                       class="w-full px-3.5 py-2.5 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent uppercase tracking-widest
                                              {{ $errors->has('collector_initial_name') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                <p id="initial-hint" class="mt-1.5 text-xs text-gray-400">
                                    Singkatan unik, maks. 3 karakter. Disimpan ke database collector.
                                </p>
                                @error('collector_initial_name')
                                    <p id="initial-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Collector Display Name --}}
                            <div>
                                <label for="collector_display_name"
                                       class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Tampil Collector
                                </label>
                                <input type="text"
                                       id="collector_display_name"
                                       name="collector_display_name"
                                       value="{{ old('collector_display_name') }}"
                                       autocomplete="off"
                                       aria-describedby="display-hint{{ $errors->has('collector_display_name') ? ' display-error' : '' }}"
                                       aria-invalid="{{ $errors->has('collector_display_name') ? 'true' : 'false' }}"
                                       placeholder="Nama yang ditampilkan ke publik"
                                       class="w-full px-3.5 py-2.5 text-sm border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] focus:border-transparent
                                              {{ $errors->has('collector_display_name') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                <p id="display-hint" class="mt-1.5 text-xs text-gray-400">
                                    Opsional. Jika kosong akan menggunakan nama user.
                                </p>
                                @error('collector_display_name')
                                    <p id="display-error" role="alert" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>

                        {{-- Info banner --}}
                        <div class="mt-5 flex items-start gap-2.5 px-4 py-3 bg-blue-50 border border-blue-100 rounded-lg"
                             role="note"
                             aria-label="Informasi penyimpanan data collector">
                            <i class="fa-solid fa-circle-info text-blue-400 text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Data pada bagian ini akan disimpan ke <strong>database collector</strong> secara terpisah dari data user utama.
                                Pastikan inisial collector bersifat unik di seluruh sistem.
                            </p>
                        </div>

                    </div>
                </div>

            </fieldset>

            {{-- ── Footer: Actions ── --}}
            <div class="flex items-center justify-between gap-3 px-6 py-4 bg-gray-50/60">
                <p class="text-xs text-gray-400">
                    Kolom bertanda <span class="text-red-500 font-semibold" aria-hidden="true">*</span>
                    <span class="sr-only">bintang merah</span> wajib diisi.
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--flora-teal)] focus-visible:outline-offset-2">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--flora-teal)] focus-visible:outline-offset-2">
                        <i class="fa-solid fa-user-plus text-xs" aria-hidden="true"></i>
                        Simpan User
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
/* ── Password toggle ── */
function togglePassword(fieldId, btn) {
    const input    = document.getElementById(fieldId);
    const icon     = btn.querySelector('i');
    const isHidden = input.type === 'password';

    input.type      = isHidden ? 'text' : 'password';
    btn.ariaLabel   = isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi';
    btn.ariaPressed = isHidden ? 'true' : 'false';
    icon.className  = isHidden ? 'fa-solid fa-eye-slash text-sm' : 'fa-solid fa-eye text-sm';
}

/* ── Radio status visual sync ── */
document.querySelectorAll('input[name="account_status"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('input[name="account_status"]').forEach(r => {
            const ring   = r.closest('label').querySelector('span');
            const dot    = ring.querySelector('span');
            const active = r.checked;
            dot.className  = `w-2 h-2 rounded-full ${active ? 'bg-[var(--flora-teal)]' : ''}`;
            ring.className = ring.className
                .replace(/border-\[var\(--flora-teal\)\]|border-gray-300/g, '').trim()
                + ` ${active ? 'border-[var(--flora-teal)]' : 'border-gray-300'}`;
        });
    });
});

/* ── Collector toggle ── */
(function () {
    const checkbox       = document.getElementById('is_collector');
    const fields         = document.getElementById('collector-fields');
    const initialInput   = document.getElementById('collector_initial_name');

    function sync() {
        const on = checkbox.checked;
        fields.classList.toggle('hidden', !on);
        if (initialInput) {
            initialInput.required     = on;
            initialInput.ariaRequired = on ? 'true' : 'false';
        }
    }

    checkbox.addEventListener('change', sync);
    sync(); // run once on load to honour old() state
})();
</script>
@endsection
