@extends('layout.admin')
@section('content')
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
                    Formulir Penyemaian Tanaman
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">-</p>
            </div>
        </div>

        <form action="{{ route('peneliti.penyemaian.store') }}" method="post" x-data="penyemaianForm">
            @csrf

            {{-- Form fields --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-6 pt-6 pb-4 mb-6">
                <div class="mb-4">
                    <label for="tanggal_penyemaian"
                        class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">
                        Tanggal Penyemaian
                    </label>
                    <input type="date" name="tanggal_penyemaian" id="tanggal_penyemaian"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors bg-white">
                </div>
                <div class="mb-4">
                    <label for="lokasi_semai"
                        class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">
                        Lokasi Semai
                    </label>
                    <input type="text" name="lokasi_semai" id="lokasi_semai"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors bg-white">
                </div>
                <div>
                    <label for="catatan"
                        class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">
                        Catatan
                    </label>
                    <textarea name="catatan" id="catatan"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]/30 focus:border-[var(--flora-teal)] transition-colors bg-white"></textarea>
                </div>
            </div>

            {{-- Tabel tanaman --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-6 pt-6 pb-4 mb-6">

                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs text-gray-500">
                        Total dipilih:
                        <span x-text="selected.length" class="font-semibold text-teal-600"></span> tanaman
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-4 py-3 w-10">
                                    <input type="checkbox"
                                        @change="toggleAll()"
                                        :checked="isAllSelected"
                                        :indeterminate="isAllIndeterminate"
                                        class="rounded accent-teal-600">
                                </th>
                                <th class="w-8"></th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Nomor Akses
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Nama Scientific
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Author
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($create as $nomorAkses => $tanaman)
                                @php
                                    $groupIds = $tanaman->pluck('id')->values()->toArray();
                                    $groupIdsJson = json_encode($groupIds);
                                    $firstItem = $tanaman->first();
                                @endphp

                                {{-- Group header row --}}
                                <tr class="border-b border-gray-100 transition-colors cursor-pointer"
                                    :class="isGroupSelected({{ $groupIdsJson }})
                                        ? 'bg-teal-50'
                                        : isGroupIndeterminate({{ $groupIdsJson }}) ? 'bg-teal-50/40' : 'hover:bg-gray-50/70'">

                                    <td class="px-4 py-3" @click.stop>
                                        <input type="checkbox"
                                            @change="toggleGroup({{ $groupIdsJson }})"
                                            :checked="isGroupSelected({{ $groupIdsJson }})"
                                            :indeterminate="isGroupIndeterminate({{ $groupIdsJson }})"
                                            class="rounded accent-teal-600">
                                    </td>

                                    <td class="px-4 py-3 w-8" @click="toggleExpand('{{ $nomorAkses }}')">
                                        <span class="text-gray-400 transition-transform duration-200 inline-block"
                                            :class="isExpanded('{{ $nomorAkses }}') ? 'rotate-90' : ''">
                                            ▶
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-xs font-semibold text-gray-700"
                                        @click="toggleExpand('{{ $nomorAkses }}')">
                                        {{ $nomorAkses }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500 italic"
                                        @click="toggleExpand('{{ $nomorAkses }}')">
                                        {{ $firstItem->tanamanPenerimaan->tanamanInfo->scientific_name }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-400"
                                        @click="toggleExpand('{{ $nomorAkses }}')">
                                        {{ $firstItem->tanamanPenerimaan->tanamanInfo->author_name }}
                                    </td>
                                    <td class="px-4 py-3" @click="toggleExpand('{{ $nomorAkses }}')">
                                        <span class="inline-flex items-center gap-1 text-xs bg-teal-100 text-teal-700 font-medium px-2 py-0.5 rounded-full">
                                            {{ $tanaman->count() }} tanaman
                                        </span>
                                    </td>
                                </tr>

                                {{-- Individual item rows --}}
                                @foreach ($tanaman as $item)
                                    <tr class="border-b border-gray-50 transition-colors"
                                        x-show="isExpanded('{{ $nomorAkses }}')"
                                        :class="selected.includes({{ $item->id }}) ? 'bg-teal-50/70' : 'bg-gray-50/30 hover:bg-gray-50'">

                                        <td class="px-4 py-2.5 pl-8">
                                            <input type="checkbox"
                                                :value="{{ $item->id }}"
                                                x-model="selected"
                                                class="rounded accent-teal-600">
                                        </td>

                                        <td class="px-4 py-2.5">
                                            <span class="text-gray-300 text-xs">└</span>
                                        </td>

                                        <td class="px-4 py-2.5 text-xs text-gray-500 font-mono" colspan="4">
                                            {{ $nomorAkses }} - {{ str_pad($item->nomor_urut, 3, '0', STR_PAD_LEFT) }}
                                        </td>
                                    </tr>
                                @endforeach

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Hidden inputs untuk submit --}}
            <template x-for="id in selected" :key="'hidden-' + id">
                <input type="hidden" name="tanaman[]" :value="id">
            </template>

            <button type="submit"
                class="px-4 py-2 bg-[var(--flora-teal)] text-white rounded-lg flex items-center gap-2">
                Submit Penyemaian
            </button>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('penyemaianForm', () => {
                    return {
                        selected: [],
                        expanded: [],
                        allIds: @json($create->flatten()->pluck('id')->values()),

                        // --- Select All ---
                        get isAllSelected() {
                            return this.allIds.length > 0 &&
                                this.allIds.every(id => this.selected.includes(id))
                        },
                        get isAllIndeterminate() {
                            return this.allIds.some(id => this.selected.includes(id)) &&
                                !this.isAllSelected
                        },
                        toggleAll() {
                            this.selected = this.isAllSelected ? [] : [...this.allIds]
                        },

                        // --- Select Per Group ---
                        isGroupSelected(ids) {
                            return ids.length > 0 && ids.every(id => this.selected.includes(id))
                        },
                        isGroupIndeterminate(ids) {
                            return ids.some(id => this.selected.includes(id)) && !this.isGroupSelected(ids)
                        },
                        toggleGroup(ids) {
                            if (this.isGroupSelected(ids)) {
                                this.selected = this.selected.filter(id => !ids.includes(id))
                            } else {
                                ids.forEach(id => {
                                    if (!this.selected.includes(id)) this.selected.push(id)
                                })
                            }
                        },

                        // --- Expand / Collapse ---
                        toggleExpand(nomorAkses) {
                            if (this.expanded.includes(nomorAkses)) {
                                this.expanded = this.expanded.filter(n => n !== nomorAkses)
                            } else {
                                this.expanded.push(nomorAkses)
                            }
                        },
                        isExpanded(nomorAkses) {
                            return this.expanded.includes(nomorAkses)
                        },
                    }
                })
            })
        </script>
    @endpush
@endsection
