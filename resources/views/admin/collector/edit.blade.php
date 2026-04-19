@extends('layout.admin')
@section('content')
    <div class="px-4 py-6 mx-auto">
        {{-- Header Group --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Tambah Kolektor Baru
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data kolektor
                </p>
            </div>
            <a href="{{ route('collector.index') }}"
                class="inline-flex items-center gap-2 text-sm text-[var(--flora-stone)] hover:text-[var(--flora-moss)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Card Utama --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-medium text-[var(--flora-stone)] uppercase tracking-widest">
                    Informasi Kolektor
                </h2>
            </div>

            {{-- Form --}}
            <form action="{{ route('collector.update', $collector->id) }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf
                @method('patch')

                {{-- User (Dropdown) --}}
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Akun Pengguna
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <select id="user_id" name="user_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800 bg-white
                               focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)] focus:border-transparent
                               transition @error('user_id') border-red-400 @enderror">
                        <option value="">-- Pilih User (Opsional) --</option>
                        @if ($collector->user)
                            <option value="{{ $collector->user->id }}" selected>
                                {{ $collector->user->name }} ({{ $collector->user->email }})
                            </option>
                        @endif
                        @foreach ($userTanpaCollector as $utc)
                            <option value="{{ $utc->id }}"
                                {{ old('user_id', $collector->user_id) == $utc->id ? 'selected' : '' }}>
                                {{ $utc->name }} ({{ $utc->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Lengkap & Nama Inisial - Grid 2 col --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Full Name --}}
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name"
                            value="{{ old('full_name', $collector->full_name) }}" placeholder="contoh: Budi Santoso"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                   focus:border-transparent transition @error('full_name') border-red-400 @enderror" />
                        @error('full_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Initial Collector Name --}}
                    <div>
                        <label for="initial_collector_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Inisial Kolektor
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input type="text" id="initial_collector_name" name="initial_collector_name"
                            value="{{ old('initial_collector_name', $collector->initial_collector_name) }}"
                            placeholder="contoh: BST" maxlength="10"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                   focus:border-transparent transition @error('initial_collector_name') border-red-400 @enderror" />
                        <p class="mt-1 text-xs text-gray-400">Singkatan unik untuk identifikasi kolektor</p>
                        @error('initial_collector_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Last Sequence & Is Manual - Grid 2 col --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Last Sequence --}}
                    <div>
                        <label for="last_sequence" class="block text-sm font-medium text-gray-700 mb-1">
                            Urutan Terakhir
                        </label>
                        <input type="number" id="last_sequence" name="last_sequence"
                            value="{{ old('last_sequence', $collector->last_sequence) }}" min="0" placeholder="0"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-800
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                                   focus:border-transparent transition @error('last_sequence') border-red-400 @enderror" />
                        <p class="mt-1 text-xs text-gray-400">Nomor urut terakhir yang digunakan kolektor ini</p>
                        @error('last_sequence')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Is Manual --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mode Input
                        </label>
                        <div class="flex items-center gap-6 mt-2.5">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_manual" value="1"
                                    {{ old('is_manual', $collector->is_manual) == '1' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[var(--flora-moss)] border-gray-300 focus:ring-[var(--flora-moss)]" />
                                <span class="text-sm text-gray-700">Manual</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_manual" value="0"
                                    {{ old('is_manual', $collector->is_manual) == '0' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[var(--flora-moss)] border-gray-300 focus:ring-[var(--flora-moss)]" />
                                <span class="text-sm text-gray-700">Otomatis</span>
                            </label>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Tentukan apakah nomor urut diisi manual atau otomatis</p>
                        @error('is_manual')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-100 pt-5 flex items-center justify-end gap-3">
                    <a href="{{ route('collector.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300
                              rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-[var(--flora-moss)] rounded-lg
                               hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--flora-moss)]
                               focus:ring-offset-2 transition shadow-sm">
                        Simpan Kolektor
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
