@extends('layout.admin')
@section('content')
    @if ($errors->any())
        <div class="mb-4 flex gap-3 px-4 py-3.5 bg-red-50 border border-red-200 rounded-xl text-sm">
            <div class="flex-shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
            </div>
            <div>
                <p class="font-semibold text-red-700 mb-1">Terdapat {{ $errors->count() }} kesalahan pengisian:</p>
                <ul class="list-disc list-inside space-y-0.5 text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="mx-auto px-4 py-6" x-data="penerimaanForm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Formulir Penerimaan Tanaman
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    -
                </p>
            </div>
        </div>
        {{-- STEP INDICATOR --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-6 pt-6 pb-4 mb-6">
            <div class="flex items-start">

                <template x-for="(label, index) in steps" :key="index">
                    <div class="flex items-center flex-1">

                        {{-- STEP --}}
                        <div class="flex flex-col items-center flex-1 cursor-pointer"
                            @click="step >= index+1 && (step = index+1)">

                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-200"
                                :class="step > index + 1 ?
                                    'bg-[var(--flora-teal)] text-white ring-4 ring-[var(--flora-teal)]/20' :
                                    step === index + 1 ?
                                    'bg-[var(--flora-teal)] text-white ring-4 ring-[var(--flora-teal)]/20' :
                                    'bg-gray-100 text-gray-400 border border-gray-200'">

                                <span x-show="step <= index+1" x-text="index+1"></span>
                                <span x-show="step > index+1">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </span>
                            </div>

                            <span class="text-xs mt-1.5 font-medium text-center"
                                :class="step === index + 1 ? 'text-[var(--flora-moss)]' :
                                    step > index + 1 ? 'text-[var(--flora-teal)]' :
                                    'text-gray-400'"
                                x-text="label">
                            </span>
                        </div>

                        {{-- CONNECTOR --}}
                        <div class="flex-1 flex items-start pt-4" x-show="index < steps.length - 1">
                            <div class="h-0.5 flex-1 rounded-full transition-all duration-500"
                                :class="step > index + 1 ? 'bg-[var(--flora-teal)]' : 'bg-gray-200'">
                            </div>
                        </div>

                    </div>
                </template>

            </div>
        </div>

        {{-- FORM — membungkus step content DAN navigation --}}
        <form action="{{ route('peneliti.penerimaan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- STEP CONTENT --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

                {{-- STEP 1 --}}
                <div x-show="step === 1">
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Dokumen Legalitas</p>
                        <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"
                            x-text="dokumenList.length + ' dokumen'"></span>
                    </div>

                    <div class="space-y-3 mb-3">
                        <template x-for="(doc, index) in dokumenList" :key="doc._id">
                            <div class="rounded-xl border transition-all duration-200"
                                :class="doc.fileSurat ? 'border-[var(--flora-teal)]/30 bg-[var(--flora-teal)]/5' :
                                    'border-gray-200 bg-gray-50/30'">

                                {{-- Row atas: icon + fields + hapus --}}
                                <div class="flex items-start gap-3 p-3.5">

                                    {{-- Status icon --}}
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 transition-colors"
                                        :class="doc.fileSurat ? 'bg-[var(--flora-teal)]/15' : 'bg-white border border-gray-200'">
                                        <i x-show="!doc.fileSurat"
                                            class="fa-regular fa-file-lines text-gray-400 text-sm"></i>
                                        <i x-show="doc.fileSurat"
                                            class="fa-solid fa-check text-[var(--flora-teal)] text-sm"></i>
                                    </div>

                                    {{-- Inputs --}}
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                        <div>
                                            <label
                                                class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">
                                                Nama Surat <span class="text-red-400">*</span>
                                            </label>
                                            <input type="text" :name="`dokumen[${index}][namaSurat]`"
                                                x-model="doc.namaSurat" placeholder="Contoh: Surat Jalan"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors bg-white">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">
                                                Nomor Surat
                                            </label>
                                            <input type="text" :name="`dokumen[${index}][nomorSurat]`"
                                                x-model="doc.nomorSurat" placeholder="Contoh: 001/SK/IV/2026"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors bg-white">
                                        </div>
                                    </div>

                                    {{-- Hapus --}}
                                    <button type="button" x-show="dokumenList.length > 1" @click="hapusDokumen(index)"
                                        class="mt-0.5 w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>

                                {{-- Row bawah: upload file --}}
                                <div class="px-3.5 pb-3.5 pl-[3.25rem]">
                                    <div x-show="!doc.fileSurat" class="flex items-center gap-2">
                                        <button type="button" @click="triggerDokumenUpload(doc._id)"
                                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border border-[var(--flora-teal)] text-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-teal)]/5 transition-colors">
                                            <i class="fa-solid fa-paperclip text-xs"></i>
                                            Lampirkan File
                                        </button>
                                        <span class="text-xs text-gray-400">PDF / JPG / PNG, maks. 5MB</span>
                                    </div>
                                    <div x-show="doc.fileSurat" class="flex items-center gap-2">
                                        <div
                                            class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg">
                                            <i class="fa-regular fa-file-pdf text-[var(--flora-teal)] text-xs"></i>
                                            <span class="text-xs text-gray-600 max-w-[180px] truncate"
                                                x-text="doc.fileSurat?.name"></span>
                                            <span class="text-xs text-gray-400"
                                                x-text="doc.fileSurat ? formatSize(doc.fileSurat.size) : ''"></span>
                                        </div>
                                        <button type="button" @click="triggerDokumenUpload(doc._id)"
                                            class="text-xs text-gray-400 hover:text-gray-600 transition-colors">Ganti</button>
                                        <button type="button" @click="hapusFileDokumen(index)"
                                            class="text-xs text-red-400 hover:text-red-600 transition-colors">Hapus
                                            file</button>
                                    </div>
                                    <input type="file" :id="'dokumen-file-' + doc._id"
                                        :name="`dokumen[${index}][fileSurat]`" accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                        @change="handleDokumenFile($event, index)">
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Progress --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[var(--flora-teal)] rounded-full transition-all duration-500"
                                :style="'width: ' + progressDokumen + '%'"></div>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0"
                            x-text="dokumenTerlampir + ' / ' + dokumenList.length + ' terlampir'"></span>
                    </div>
                    <p x-show="!semuaDokumenValid" class="text-xs text-amber-600 mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info"></i>
                        Nama surat dan file wajib diisi untuk setiap dokumen
                    </p>

                    <button type="button" @click="addDokumen()"
                        class="w-full py-2.5 border border-dashed border-gray-300 rounded-xl text-[var(--flora-teal)] text-sm font-medium hover:border-[var(--flora-teal)]/50 hover:bg-[var(--flora-teal)]/5 transition-colors flex items-center justify-center gap-2 mb-4">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Dokumen
                    </button>
                </div>

                {{-- STEP 2 --}}
                <div x-show="step === 2">
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-[15px] font-bold text-gray-400 uppercase tracking-widest">Informasi Penerimaan</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Tanggal Penerimaan -->
                         <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Tanggal Penerimaan <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="tanggal_penerimaan"
                                x-model="tanggal_penerimaan"
                                :max="today"
                                @change="validateTanggal()"
                                class="w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] bg-white transition-colors"
                                :class="tanggalErrors.penerimaan ? 'border-red-400' : 'border-gray-200'">
                            <p x-show="tanggalErrors.penerimaan"
                                class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span x-text="tanggalErrors.penerimaan"></span>
                            </p>
                        </div>

                        <!-- Tanggal Explorasi -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Tanggal Explorasi <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="tanggal_explorasi"
                                x-model="tanggal_explorasi"
                                :max="tanggal_penerimaan || yesterday"
                                @change="validateTanggal()"
                                class="w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] bg-white transition-colors"
                                :class="tanggalErrors.explorasi ? 'border-red-400' : 'border-gray-200'">
                            <p x-show="tanggalErrors.explorasi"
                                class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span x-text="tanggalErrors.explorasi"></span>
                            </p>
                        </div>

                        <!-- Jenis Form -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Jenis Form <span class="text-red-400">*</span>
                            </label>
                            <select name="jenis_form"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[var(--flora-teal)]/30
                       focus:border-[var(--flora-teal)] bg-white">
                                <option value="">Pilih jenis form...</option>
                                <option value="explorasi">Explorasi</option>
                                <option value="penerimaan">Penerimaan</option>
                            </select>
                        </div>

                        <!-- Tempat -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Tempat Asal
                            </label>
                            <input type="text" name="tempat_asal"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[var(--flora-teal)]/30
                       focus:border-[var(--flora-teal)] bg-white">
                        </div>

                        <!-- Negara -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Negara
                            </label>
                            <input type="text" name="country"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[var(--flora-teal)]/30
                       focus:border-[var(--flora-teal)] bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Source
                            </label>
                            <input type="text" name="source"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[var(--flora-teal)]/30
                       focus:border-[var(--flora-teal)] bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Native
                            </label>
                            <input type="text" name="native"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[var(--flora-teal)]/30
                       focus:border-[var(--flora-teal)] bg-white">
                        </div>
                    </div>

                </div>

                {{-- STEP 3 --}}
                <div x-show="step === 3">
                    {{-- STEP 3 --}}
                    <div x-show="step === 3">
                        <div class="flex items-center justify-between mb-5">
                            <p class="text-[15px] font-bold text-gray-400 uppercase tracking-widest">Tim Penerimaan</p>
                        </div>

                        {{-- Toggle: Tim Lama vs Tim Baru --}}
                        <div class="flex items-center gap-3 mb-5 p-1 bg-gray-100 rounded-xl w-fit">
                            <button type="button" @click="gunakanTimLama = true"
                                class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all"
                                :class="gunakanTimLama ? 'bg-white text-[var(--flora-moss)] shadow-sm' : 'text-gray-400'">
                                Gunakan Tim yang Ada
                            </button>
                            <button type="button" @click="gunakanTimLama = false"
                                class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all"
                                :class="!gunakanTimLama ? 'bg-white text-[var(--flora-moss)] shadow-sm' : 'text-gray-400'">
                                Buat Tim Baru
                            </button>
                        </div>

                        {{-- Pilih Tim yang Ada --}}
                        <div x-show="gunakanTimLama">
                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                Pilih Tim <span class="text-red-400">*</span>
                            </label>
                            <select name="tim_id" x-model="selectedTimId"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                   focus:ring-2 focus:ring-[var(--flora-teal)]/30
                   focus:border-[var(--flora-teal)] bg-white mb-4"
                                :disabled="!gunakanTimLama">
                                <option value="">Pilih tim...</option>
                                @foreach ($team as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama_tim }}</option>
                                @endforeach
                            </select>

                            {{-- Detail tim yang dipilih --}}
                            <template x-if="selectedTimId">
                                <div class="rounded-xl border border-[var(--flora-teal)]/30 bg-[var(--flora-teal)]/5 p-4">
                                    <p class="text-xs font-semibold text-gray-400 uppercase mb-3">Anggota Tim</p>
                                    <div class="space-y-2">
                                        @foreach ($team as $t)
                                            <template x-if="selectedTimId == '{{ $t->id }}'">
                                                <div>
                                                    @foreach ($t->AnggotaTimExplorasi as $anggota)
                                                        <div class="flex items-center gap-2 py-1.5">
                                                            <div
                                                                class="w-7 h-7 rounded-full bg-[var(--flora-teal)]/20 flex items-center justify-center text-xs font-semibold text-[var(--flora-teal)]">
                                                                {{ strtoupper(substr($anggota->collector->full_name, 0, 1)) }}
                                                            </div>
                                                            <span
                                                                class="text-sm text-gray-700">{{ $anggota->collector->full_name }}
                                                                ({{ $anggota->collector->initial_collector_name }})
                                                            </span>
                                                            <span
                                                                class="text-xs text-gray-400 ml-auto">{{ $anggota->role ?? 'Kolektor' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </template>
                                        @endforeach
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Buat Tim Baru --}}
                        <div x-show="!gunakanTimLama" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                        Nama Tim <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="tim_baru[nama_tim]" x-model="timBaru.namaTim"
                                        placeholder="Contoh: Tim Flora Jawa Barat"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                           focus:ring-2 focus:ring-[var(--flora-teal)]/30
                           focus:border-[var(--flora-teal)] bg-white"
                                        :disabled="gunakanTimLama">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                        Lokasi Eksplorasi <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="tim_baru[lokasi]" x-model="timBaru.lokasi"
                                        placeholder="Contoh: Gunung Gede, Jawa Barat"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                           focus:ring-2 focus:ring-[var(--flora-teal)]/30
                           focus:border-[var(--flora-teal)] bg-white"
                                        :disabled="gunakanTimLama">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                    Deskripsi Tim
                                </label>
                                <textarea name="tim_baru[deskripsi]" x-model="timBaru.deskripsi" rows="2"
                                    placeholder="Deskripsi singkat tentang tim..."
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[var(--flora-teal)]/30
                       focus:border-[var(--flora-teal)] bg-white resize-none"
                                    :disabled="gunakanTimLama"></textarea>
                            </div>

                            {{-- Anggota Tim --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-semibold text-gray-400 uppercase">
                                        Anggota Tim (Kolektor)
                                    </label>
                                    <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"
                                        x-text="timBaru.anggota.length + ' dipilih'"></span>
                                </div>

                                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                    @foreach ($collector as $k)
                                        <label
                                            class="flex items-center gap-3 p-2.5 rounded-xl border border-gray-100 hover:border-[var(--flora-teal)]/30 hover:bg-[var(--flora-teal)]/5 cursor-pointer transition-colors"
                                            :class="isAnggotaSelected('{{ $k->id }}') ?
                                                'border-[var(--flora-teal)]/30 bg-[var(--flora-teal)]/5' : ''">

                                            <input type="checkbox" value="{{ $k->id }}"
                                                @change="toggleAnggota('{{ $k->id }}')"
                                                class="accent-[var(--flora-teal)]">

                                            <div
                                                class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-xs font-semibold text-gray-500 flex-shrink-0">
                                                {{ strtoupper(substr($k->full_name, 0, 1)) }}
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-700 truncate">{{ $k->full_name }}</p>
                                                <p class="text-xs text-gray-400">{{ $k->user?->email ?? '-' }}</p>
                                            </div>

                                            {{-- Input role, muncul hanya kalau dipilih --}}
                                            <template x-if="isAnggotaSelected('{{ $k->id }}')">
                                                <select @click.stop
                                                    @change="setRole('{{ $k->id }}', $event.target.value)"
                                                    class="text-xs border border-gray-200 rounded-lg px-2 py-1 bg-white focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)]">
                                                    <option value="anggota">Anggota</option>
                                                    <option value="ketua">Ketua Tim</option>
                                                </select>
                                            </template>

                                        </label>

                                        {{-- Hidden inputs untuk submit form --}}
                                        <template x-if="isAnggotaSelected('{{ $k->id }}')">
                                            <div>
                                                <input type="hidden"
                                                    :name="`tim_baru[anggota][${getAnggotaIndex('{{ $k->id }}')}][id]`"
                                                    value="{{ $k->id }}" :disabled="gunakanTimLama">
                                                <input type="hidden"
                                                    :name="`tim_baru[anggota][${getAnggotaIndex('{{ $k->id }}')}][role]`"
                                                    :value="getRole('{{ $k->id }}')":disabled="gunakanTimLama">
                                            </div>
                                        </template>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 4 --}}
                <div x-show="step === 4">
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-[15px] font-bold text-gray-400 uppercase tracking-widest">Informasi Tanaman</p>
                    </div>
                    <div class="flex items-center gap-3 mb-5 p-1 bg-gray-100 rounded-xl w-fit">
                        <button type="button" @click="modeInput = 'manual'"
                            class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all"
                            :class="modeInput === 'manual' ? 'bg-white text-[var(--flora-moss)] shadow-sm' : 'text-gray-400'">
                            Manual
                        </button>
                        <button type="button" @click="modeInput = 'excel'"
                            class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all"
                            :class="modeInput === 'excel' ? 'bg-white text-[var(--flora-moss)] shadow-sm' : 'text-gray-400'">
                            Excel
                        </button>
                    </div>
                    <div x-show="modeInput === 'manual'">
                        <div class="space-y-4 mb-4">
                            <template x-for="(tanaman, index) in tanamanList" :key="index">
                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    <div
                                        class="flex justify-between items-center px-4 py-3 bg-gray-50 border-b border-gray-100">
                                        <span class="text-sm font-semibold text-gray-600 flex items-center gap-2">
                                            <span
                                                class="w-5 h-5 rounded-full bg-[var(--flora-teal)]/15 text-[var(--flora-teal)] text-xs flex items-center justify-center font-bold"
                                                x-text="index + 1"></span>
                                            Tanaman
                                        </span>
                                        <button type="button" @click="removeTanaman(index)"
                                            x-show="tanamanList.length > 1"
                                            class="text-xs px-3 py-1 bg-red-50 text-red-400 border border-red-100 rounded-lg hover:bg-red-100 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash-can mr-1"></i>Hapus
                                        </button>
                                    </div>
                                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Scientific
                                                Name</label>
                                            <input type="text" :name="`tanaman[${index}][scientific_name]`"
                                                x-model="tanaman.scientific_name" placeholder="Contoh: Mangifera indica"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm italic focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Nomor
                                                Akses</label>
                                            <div class="relative">
                                                <input type="text" :name="`tanaman[${index}][nomor_akses]`"
                                                    x-model="tanaman.nomor_akses" placeholder="Otomatis di-generate..."
                                                    readonly
                                                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none">
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2">
                                                    <template x-if="tanaman.nomor_akses">
                                                        <i class="fa-solid fa-check text-[var(--flora-teal)] text-xs"></i>
                                                    </template>
                                                    <template x-if="!tanaman.nomor_akses">
                                                        <span class="text-gray-300 text-xs animate-pulse">•••</span>
                                                    </template>
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-0.5">Dibuat otomatis oleh sistem</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama
                                                Lokal</label>
                                            <input type="text" :name="`tanaman[${index}][nama_lokal]`"
                                                x-model="tanaman.nama_lokal" placeholder="Nama lokal tanaman"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Marga
                                                (Genus)</label>
                                            <input type="text" :name="`tanaman[${index}][marga]`"
                                                x-model="tanaman.marga" placeholder="Contoh: Mangifera"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Marga
                                                Jenis</label>
                                            <input type="text" :name="`tanaman[${index}][marga_jenis]`"
                                                x-model="tanaman.marga_jenis" placeholder="Marga jenis"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Suku
                                                (Famili)</label>
                                            <input type="text" :name="`tanaman[${index}][suku]`"
                                                x-model="tanaman.suku" placeholder="Contoh: Anacardiaceae"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Spesies</label>
                                            <input type="text" :name="`tanaman[${index}][spesies]`"
                                                x-model="tanaman.spesies" placeholder="Nama spesies"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Author
                                                Name</label>
                                            <input type="text" :name="`tanaman[${index}][author_name]`"
                                                x-model="tanaman.author_name" placeholder="Contoh: L."
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Locality</label>
                                            <input type="text" :name="`tanaman[${index}][locality]`"
                                                x-model="tanaman.locality" placeholder="Lokasi pengambilan"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Jumlah
                                                Material</label>
                                            <input type="number" :name="`tanaman[${index}][jumlah_material]`"
                                                x-model="tanaman.jumlah_material" placeholder="0" min="0"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Tipe Tanaman</label>
                                            <select  x-model="tanaman.tipe_tanaman" :name="`tanaman[${index}][tipe_tanaman]`" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors bg-white">
                                                <option value="">Pilih Tipe Tanaman</option>
                                                <option value="tree">Pohon</option>
                                                <option value="shrub">Semak</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">VAK No</label>
                                            <input type="text" :name="`tanaman[${index}][vak_no]`"
                                                x-model="tanaman.vak_no" placeholder="Nomor VAK"
                                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors">
                                        </div>

                                        {{-- Collector --}}
                                        <div class="md:col-span-2 pt-1 border-t border-gray-100 mt-1">
                                            <p
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2.5">
                                                Collector</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama
                                                        Collector</label>
                                                    <select :name="`tanaman[${index}][collector_id]`"
                                                        x-model="tanaman.collector_id" @change="onCollectorChange(index)"
                                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors bg-white">
                                                        <option value="">Pilih collector</option>
                                                        @foreach ($collector as $c)
                                                            <option value="{{ $c->id }}"
                                                                data-initial="{{ $c->initial_collector_name }}"
                                                                data-name="{{ $c->full_name }}">
                                                                {{ $c->initial_collector_name }}({{ $c->full_name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Initial
                                                        Collector</label>
                                                    <input type="text" :name="`tanaman[${index}][collector_initial]`"
                                                        x-model="tanaman.collector_initial"
                                                        placeholder="Otomatis dari pilihan" readonly
                                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>
                            <button type="button" @click="addTanaman()"
                                class="w-full py-3 border border-dashed border-gray-300 rounded-xl text-[var(--flora-teal)] text-sm font-medium hover:border-[var(--flora-teal)]/50 hover:bg-[var(--flora-teal)]/5 transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-plus text-xs"></i>
                                Tambah Tanaman
                            </button>
                        </div>
                    </div>
                    <div x-show="modeInput === 'excel'">

                        {{-- Upload area --}}
                        <div x-show="tanamanList.length === 0"
                            class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
                            <i class="fa-solid fa-file-excel text-3xl text-green-500 mb-3 block"></i>
                            <p class="text-sm font-medium text-gray-600 mb-1">Upload file Excel</p>
                            <p class="text-xs text-gray-400 mb-4">Format kolom: scientific_name, nama_lokal, marga,
                                marga_jenis, suku, spesies, author_name, locality, jumlah_material, vak_no</p>
                            <label
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--flora-teal)] text-white text-sm rounded-xl cursor-pointer hover:opacity-90 transition">
                                <i class="fa-solid fa-upload text-xs"></i>
                                Pilih File
                                <input type="file" @change="handleExcel($event)" accept=".xlsx,.xls,.csv"
                                    class="hidden">
                            </label>
                        </div>

                        {{-- Tabel editable setelah upload --}}
                        <div x-show="tanamanList.length > 0">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-semibold text-gray-600">
                                    <i class="fa-solid fa-table text-[var(--flora-teal)] mr-1.5"></i>
                                    <span x-text="tanamanList.length + ' baris data'"></span>
                                </p>
                                <div class="flex items-center gap-2">
                                    <label
                                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border border-gray-200 text-gray-500 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <i class="fa-solid fa-arrow-rotate-left text-xs"></i>
                                        Ganti File
                                        <input type="file" @change="handleExcel($event)" accept=".xlsx,.xls,.csv"
                                            class="hidden">
                                    </label>
                                    <button type="button" @click="addTanaman()"
                                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-[var(--flora-teal)]/10 text-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-teal)]/20 transition-colors">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                        Tambah Baris
                                    </button>
                                </div>
                            </div>

                            {{-- Tabel --}}
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-200">
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide w-8">
                                                #</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[140px]">
                                                Scientific Name</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[110px]">
                                                Nama Lokal</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[100px]">
                                                Marga</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[100px]">
                                                Marga Jenis</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[110px]">
                                                Suku</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[100px]">
                                                Spesies</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[80px]">
                                                Author</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[110px]">
                                                Locality</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[60px]">
                                                Jml</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[80px]">
                                                VAK No</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[80px]">
                                                Tipe Tanaman</th>
                                            <th
                                                class="px-2 py-2.5 text-left font-semibold text-gray-400 uppercase tracking-wide min-w-[120px]">
                                                Collector</th>
                                            <th class="px-2 py-2.5 w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(tanaman, index) in tanamanList" :key="index">
                                            <tr
                                                class="border-b border-gray-100 hover:bg-[var(--flora-teal)]/3 group transition-colors">
                                                <td class="px-2 py-1.5 text-gray-400 text-center" x-text="index + 1"></td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][scientific_name]`"
                                                        x-model="tanaman.scientific_name"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none italic text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][nama_lokal]`"
                                                        x-model="tanaman.nama_lokal"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][marga]`"
                                                        x-model="tanaman.marga"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][marga_jenis]`"
                                                        x-model="tanaman.marga_jenis"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][suku]`"
                                                        x-model="tanaman.suku"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][spesies]`"
                                                        x-model="tanaman.spesies"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][author_name]`"
                                                        x-model="tanaman.author_name"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][locality]`"
                                                        x-model="tanaman.locality"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="number" :name="`tanaman[${index}][jumlah_material]`"
                                                        x-model="tanaman.jumlah_material" min="0"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][vak_no]`"
                                                        x-model="tanaman.vak_no"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>
                                                <td class="px-1 py-1">
                                                    <input type="text" :name="`tanaman[${index}][tipe_tanaman]`"
                                                        x-model="tanaman.tipe_tanaman"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                </td>

                                                {{-- Collector dropdown --}}
                                                <td class="px-1 py-1">
                                                    <select :name="`tanaman[${index}][collector_id]`"
                                                        x-model="tanaman.collector_id" @change="onCollectorChange(index)"
                                                        class="w-full px-2 py-1.5 rounded-lg border border-transparent hover:border-gray-200 focus:border-[var(--flora-teal)] focus:ring-1 focus:ring-[var(--flora-teal)]/30 focus:outline-none text-gray-700 bg-transparent focus:bg-white transition-all text-xs">
                                                        <option value="">- Pilih -</option>
                                                        @foreach ($collector as $c)
                                                            <option value="{{ $c->id }}"
                                                                data-initial="{{ $c->initial_collector_name }}">
                                                                {{ $c->initial_collector_name }} ({{ $c->full_name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    {{-- hidden input collector_initial --}}
                                                    <input type="hidden" :name="`tanaman[${index}][collector_initial]`"
                                                        x-model="tanaman.collector_initial">
                                                </td>

                                                {{-- Hapus baris --}}
                                                <td class="px-1 py-1 text-center">
                                                    <button type="button" @click="removeTanaman(index)"
                                                        x-show="tanamanList.length > 1"
                                                        class="opacity-0 group-hover:opacity-100 w-6 h-6 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-all">
                                                        <i class="fa-solid fa-xmark text-xs"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-info"></i>
                                Klik langsung pada sel untuk mengedit. Data hidden inputs (nomor_akses) tetap dikirim
                                otomatis.
                            </p>

                            {{-- Hidden inputs untuk nomor_akses (readonly, tidak ada di tabel) --}}
                            <template x-for="(tanaman, index) in tanamanList" :key="'akses-' + index">
                                <input type="hidden" :name="`tanaman[${index}][nomor_akses]`"
                                    x-model="tanaman.nomor_akses">
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            {{-- NAVIGATION — di dalam form agar submit berfungsi --}}
            <div class="flex justify-between mt-4">

                <button type="button" @click="prevStep" x-show="step > 1" class="px-4 py-2 bg-gray-200 rounded-lg">
                    Back
                </button>
                {{-- Placeholder agar Next/Submit tetap di kanan saat Back tidak tampil --}}
                <div x-show="step === 1"></div>

                {{-- Next — tampil di step 1, 2, 3 --}}
                <button type="button" x-show="step < steps.length" @click="nextStep"
                    class="px-4 py-2 bg-[var(--flora-teal)] text-white rounded-lg"
                    :class="step === 1 && !semuaDokumenValid ? 'opacity-50 cursor-not-allowed' : ''">
                    Next
                </button>

                {{-- Submit — hanya tampil di step terakhir --}}
                <button type="submit" x-show="step === steps.length"
                    class="px-4 py-2 bg-[var(--flora-teal)] text-white rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    Simpan
                </button>

            </div>
            {{-- Debug Button --}}
            {{-- <button type="submit" class="px-4 py-2 bg-[var(--flora-teal)] text-white rounded-lg flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                Simpan
            </button> --}}
        </form>
        {{-- akhir form --}}

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('penerimaanForm', () => ({
                    step: 1,
                    steps: [
                        'Data Legalitas',
                        'Tanggal Penerimaan',
                        'Tim Penerimaan',
                        'Data Tanaman'
                    ],
                    _dokumenIdCounter: 0,
                    dokumenList: [],
                    gunakanTimLama: true,
                    selectedTimId: '',
                    modeInput: 'manual',
                    tanggal_penerimaan: '',
                    tanggal_explorasi: '',
                    tanggalErrors: {
                        penerimaan: '',
                        explorasi: ''
                    },
                    // Step 1 Function :
                    // getter untuk dokumen di Step 1
                    get dokumenTerlampir() {
                        return this.dokumenList.filter(d => d.fileSurat).length;
                    },
                    get progressDokumen() {
                        return this.dokumenList.length === 0 ?
                            0 :
                            Math.round((this.dokumenTerlampir / this.dokumenList.length) * 100);
                    },
                    get semuaDokumenValid() {
                        return this.dokumenList.length > 0 &&
                            this.dokumenList.every(d => d.namaSurat.trim() !== '' && d.fileSurat !==
                                null);
                    },

                    // Handling Dokumen baru
                    _newDokumen() {
                        return {
                            _id: ++this._dokumenIdCounter,
                            namaSurat: '',
                            nomorSurat: '',
                            fileSurat: null
                        };
                    },
                    // Function create dokumen baru
                    addDokumen() {
                        this.dokumenList.push(this._newDokumen());
                    },
                    hapusDokumen(index) {
                        if (this.dokumenList.length <= 1) return;
                        this.dokumenList.splice(index, 1);
                    },
                    triggerDokumenUpload(id) {
                        document.getElementById('dokumen-file-' + id).click();
                    },
                    handleDokumenFile(event, index) {
                        const file = event.target.files[0];
                        if (!file) return;
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Ukuran file maksimal 5MB.');
                            event.target.value = '';
                            return;
                        }
                        this.dokumenList[index].fileSurat = file;
                    },
                    hapusFileDokumen(index) {
                        const id = this.dokumenList[index]._id;
                        this.dokumenList[index].fileSurat = null;
                        const el = document.getElementById('dokumen-file-' + id);
                        if (el) el.value = '';
                    },
                    formatSize(bytes) {
                        if (bytes < 1024) return bytes + ' B';
                        if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
                        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
                    },
                    // End Of Function Step 1
                    // Function step 2
                    get today() {
                        return new Date().toISOString().split('T')[0]; // YYYY-MM-DD
                    },
                    get yesterday() {
                        const d = new Date();
                        d.setDate(d.getDate() - 1);
                        return d.toISOString().split('T')[0];
                    },
                    validateTanggal() {
                        this.tanggalErrors.penerimaan = '';
                        this.tanggalErrors.explorasi = '';

                        if (this.tanggal_penerimaan && this.tanggal_penerimaan > this.today) {
                            this.tanggalErrors.penerimaan = 'Tanggal penerimaan tidak boleh lebih dari hari ini.';
                        }

                        if (this.tanggal_explorasi && this.tanggal_penerimaan) {
                            if (this.tanggal_explorasi >= this.tanggal_penerimaan) {
                                this.tanggalErrors.explorasi = 'Tanggal eksplorasi tidak boleh lebih dari tanggal penerimaan.';
                            }
                        }

                        return !this.tanggalErrors.penerimaan && !this.tanggalErrors.explorasi;
                    },
                    // Function Of Step 3
                    timBaru: {
                        namaTim: '',
                        lokasi: '',
                        deskripsi: '',
                        anggota: [], // array of id
                    },

                    toggleAnggota(id) {
                        const idx = this.timBaru.anggota.findIndex(a => a[0] === id);
                        if (idx === -1) {
                            this.timBaru.anggota.push([id, 'Kolektor']); // default role
                        } else {
                            this.timBaru.anggota.splice(idx, 1);
                        }
                    },

                    isAnggotaSelected(id) {
                        return this.timBaru.anggota.some(a => a[0] === id);
                    },

                    setRole(id, role) {
                        const anggota = this.timBaru.anggota.find(a => a[0] === id);
                        if (anggota) anggota[1] = role;
                    },

                    getRole(id) {
                        return this.timBaru.anggota.find(a => a[0] === id)?.[1] ?? 'Kolektor';
                    },

                    getAnggotaIndex(id) {
                        return this.timBaru.anggota.findIndex(a => a[0] === id);
                    },
                    // End Of Function Step 3
                    //  Function Of Step 4
                    tanamanList: [],
                    // tanamanList: [{
                    //     scientific_name: '',
                    //     nomor_akses: '',
                    //     nama_lokal: '',
                    //     marga: '',
                    //     marga_jenis: '',
                    //     suku: '',
                    //     spesies: '',
                    //     author_name: '',
                    //     locality: '',
                    //     jumlah_material: '',
                    //     vak_no: '',
                    //     collector_id: '',
                    //     collector_initial: ''
                    // }],
                    addTanaman() {
                        this.tanamanList.push({
                            scientific_name: '',
                            nomor_akses: '',
                            nama_lokal: '',
                            marga: '',
                            marga_jenis: '',
                            suku: '',
                            spesies: '',
                            author_name: '',
                            locality: '',
                            jumlah_material: '',
                            vak_no: '',
                            tipe_tanaman:'',
                            collector_id: '',
                            collector_initial: ''
                        });
                    },
                    removeTanaman(index) {
                        if (this.tanamanList.length > 1) {
                            this.tanamanList.splice(index, 1);
                        }
                    },
                    handleExcel(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        const reader = new FileReader();

                        reader.onload = (e) => {
                            const data = new Uint8Array(e.target.result);
                            const workbook = XLSX.read(data, {
                                type: 'array'
                            });

                            const sheet = workbook.Sheets[workbook.SheetNames[0]];

                            // ⬇ Paksa decode range aktual dari sel yang ada, jangan percaya !ref bawaan
                            const range = XLSX.utils.decode_range(sheet['!ref']);

                            const json = XLSX.utils.sheet_to_json(sheet, {
                                defval: '', // cell kosong → string kosong, bukan undefined
                                blankrows: false, // skip baris yang semua kolom-nya kosong
                                range: range, // gunakan range yang sudah di-decode
                            });


                            this.tanamanList = json.map(row => ({
                                scientific_name: row['scientific_name'] || '',
                                nomor_akses: '',
                                nama_lokal: row['nama_lokal'] || '',
                                marga: row['marga'] || '',
                                marga_jenis: row['marga_jenis'] || '',
                                suku: row['suku'] || '',
                                spesies: row['spesies'] || '',
                                author_name: row['author_name'] || '',
                                locality: row['locality'] || '',
                                jumlah_material: row['jumlah_material'] || '',
                                vak_no: row['vak_no'] || '',
                                tipe_tanaman: row['tipe_tanaman'] || '',
                                collector_id: '',
                                collector_initial: row['collector_initial'] || '',
                            }));

                            console.log(`Loaded ${this.tanamanList.length} rows`); // untuk verifikasi
                        };

                        reader.readAsArrayBuffer(file);
                    },
                    onCollectorChange(index) {
                        const select = document.querySelector(
                            `select[name="tanaman[${index}][collector_id]"]`);
                        if (!select) return;
                        const selected = select.options[select.selectedIndex];
                        console.log(selected)
                        this.tanamanList[index].collector_initial = selected?.dataset?.initial ?? '';
                    },
                    // End Of Function Step 4
                    // Nav Function
                    nextStep() {
                        if (this.step === 1 && !this.semuaDokumenValid) return;
                        if (this.step === 2 && !this.validateTanggal()) return;
                        if (this.step < this.steps.length) this.step++;
                        if (this.step === 4 && this.modeInput === 'manual' && this.tanamanList.length ===
                            0) {
                            this.addTanaman();
                        }
                    },
                    prevStep() {
                        if (this.step > 1) this.step--;
                    },
                    // Init Alpine
                    init() {
                        this.dokumenList.push(this._newDokumen());
                        this.$watch('modeInput', (mode) => {
                            this.tanamanList = [];
                            if (mode === 'manual') this.addTanaman();
                        });
                    },
                }))
            })
        </script>
    @endpush
@endsection
