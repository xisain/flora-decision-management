@extends('layout.admin')

@section('content')
    <div class="px-4 py-6">

        {{-- Page Header --}}
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-[#1a3a2a]">
                    Detail Penyemaian
                </h1>
                <p class="text-sm text-[#5a7a6a]">
                    Formulir Penyemaian
                    @if (!empty($data->tanggal_semai))
                        &middot; {{ \Carbon\Carbon::parse($data->tanggal_semai)->format('d M Y') }}
                        &middot; #{{ $data->id }}
                    @endif
                </p>
            </div>

            <a href="{{ route('peneliti.penyemaian.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-[#d8e8d8] bg-white px-4 py-2 text-sm font-semibold text-[#2d6a4f] shadow-sm transition hover:bg-[#eef7f1]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- LEFT COLUMN --}}
            <div class="flex flex-col gap-6 lg:col-span-1">

                {{-- General Information --}}
                <div class="rounded-2xl border border-[#d8e8d8] bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="h-1 w-6 rounded-full bg-[#2d6a4f]"></div>
                        <h2 class="text-base font-bold uppercase tracking-widest text-[#1a3a2a]">
                            General Information
                        </h2>
                    </div>

                    <div class="space-y-4">

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">
                                Tanggal Penyemaian
                            </span>
                            <span class="text-sm font-medium text-[#1a3a2a]">
                                {{ !empty($data->tanggal_semai) ? \Carbon\Carbon::parse($data->tanggal_semai)->format('d M Y') : '-' }}
                            </span>
                        </div>

                        <div class="h-px bg-[#eef4ef]"></div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">
                                Nomor Penyemaian
                            </span>
                            <span class="text-sm font-medium text-[#1a3a2a]">
                                #{{ $data->id ?? '-' }}
                            </span>
                        </div>

                        <div class="h-px bg-[#eef4ef]"></div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">
                                Lokasi Penyemaian
                            </span>
                            <span class="text-sm font-medium text-[#1a3a2a]">
                                {{ $data->lokasi_semai ?? '-' }}
                            </span>
                        </div>

                        <div class="h-px bg-[#eef4ef]"></div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">
                                Dicatat Oleh
                            </span>

                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-[#2d6a4f] text-xs font-bold text-white">
                                    {{ strtoupper(substr(optional($data->user)->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-[#1a3a2a]">
                                    {{ optional($data->user)->name ?? '-' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Catatan Penyemaian --}}
                <div class="rounded-2xl border border-[#d8e8d8] bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="h-1 w-6 rounded-full bg-[#e0a458]"></div>
                        <h2 class="text-base font-bold uppercase tracking-widest text-[#1a3a2a]">
                            Catatan
                        </h2>
                    </div>

                    <p class="rounded-xl border border-[#eef4ef] bg-[#f8fbf8] p-4 text-sm leading-relaxed text-[#5a7a6a]">
                        {{ $data->catatan ?? 'Tidak ada catatan penyemaian.' }}
                    </p>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="lg:col-span-2">

                <div class="rounded-2xl border border-[#d8e8d8] bg-white shadow-sm">

                    <div class="flex items-center justify-between border-b border-[#eef4ef] px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="h-1 w-6 rounded-full bg-[#2d6a4f]"></div>
                            <h2 class="text-base font-bold uppercase tracking-widest text-[#1a3a2a]">
                                Daftar Tanaman Penyemaian
                            </h2>
                        </div>

                        <span class="rounded-full bg-[#eef7f1] px-3 py-1 text-xs font-bold text-[#2d6a4f]">
                            {{ $groupedTanaman->count() }} Spesies / {{ $data->penyemaianTanaman->count() }} Tanaman
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-[#f0f7f2]">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                        No.
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                        Nomor Akses
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                        Scientific Name
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                        Jumlah
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                        Ringkasan Status
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#eef4ef]">
                                @forelse($groupedTanamanPaginated as $scientificName => $items)
                                    @php
                                        $first = $items->first();

                                        $tanamanInfo = $first->Tanaman?->tanamanPenerimaan?->TanamanInfo;

                                        $totalTanaman = $items->count();
                                        $totalHidup = $items->where('status', 'hidup')->count();
                                        $totalMati = $items->where('status', 'mati')->count();
                                        $totalProses = $items->where('status', 'proses')->count();

                                        $nomorAkses = $items
                                            ->map(fn($item) => $item->Tanaman?->tanamanPenerimaan?->nomor_akses)
                                            ->filter()
                                            ->unique()
                                            ->values();

                                        $nomorUrutTanaman = $items
                                            ->map(
                                                fn($item) => [
                                                    'nomor_akses' => $item->Tanaman?->nomor_akses,
                                                    'status' => $item->status,
                                                    'catatan' => $item->catatan,
                                                ],
                                            )
                                            ->filter(fn($item) => !empty($item['nomor_akses']))
                                            ->sortBy('nomor_akses')
                                            ->values();

                                        $rowId = 'group-' . $loop->iteration;
                                    @endphp

                                    <tr x-data="{ open: false }" x-id="['{{ $rowId }}']"
                                        class="transition hover:bg-[#f8fbf8] {{ $loop->iteration % 2 === 1 ? 'bg-white' : 'bg-[#fafcfa]' }}">
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#eef7f1] text-xs font-bold text-[#2d6a4f]">
                                                {{ $groupedTanamanPaginated->firstItem() + $loop->index }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($nomorAkses as $akses)
                                                    <span
                                                        class="rounded-md bg-[#e8f4ec] px-2 py-1 text-xs font-mono font-semibold text-[#2d6a4f]">
                                                        {{ $akses }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-[#8aaa98]">-</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div>
                                                <p class="font-semibold italic text-[#1a3a2a]">
                                                    {{ $tanamanInfo?->scientific_name ?? $scientificName }}
                                                </p>
                                                <p class="text-xs text-[#8aaa98]">
                                                    {{ $tanamanInfo?->author_name ?? '-' }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex rounded-full bg-[#eef7f1] px-2 py-1 text-xs font-bold text-[#2d6a4f]">
                                                {{ $totalTanaman }} tanaman
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    class="inline-flex rounded-full bg-[#eef7f1] px-2 py-1 text-xs font-bold text-[#2d6a4f]">
                                                    Hidup: {{ $totalHidup }}
                                                </span>

                                                <span
                                                    class="inline-flex rounded-full bg-red-50 px-2 py-1 text-xs font-bold text-red-700">
                                                    Mati: {{ $totalMati }}
                                                </span>

                                                @if ($totalProses > 0)
                                                    <span
                                                        class="inline-flex rounded-full bg-yellow-50 px-2 py-1 text-xs font-bold text-yellow-700">
                                                        Proses: {{ $totalProses }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <button type="button"
                                                @click="
                        const detail = document.getElementById('detail-{{ $rowId }}');
                        detail.classList.toggle('hidden');
                        open = !open;
                    "
                                                class="inline-flex items-center gap-1 rounded-lg border border-[#d8e8d8] bg-white px-3 py-1.5 text-xs font-semibold text-[#2d6a4f] transition hover:bg-[#eef7f1]">
                                                <span x-text="open ? 'Tutup' : 'Detail'"></span>

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform"
                                                    :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr id="detail-{{ $rowId }}" class="hidden bg-[#f8fbf8]">
                                        <td colspan="6" class="px-6 py-4">
                                            <div class="rounded-xl border border-[#d8e8d8] bg-white p-4">
                                                <div class="mb-3 flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-bold text-[#1a3a2a]">
                                                            Nomor Urut Tanaman yang Disemai
                                                        </p>
                                                        <p class="text-xs text-[#8aaa98]">
                                                            Total {{ $nomorUrutTanaman->count() }} tanaman pada spesies
                                                            ini.
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="overflow-x-auto rounded-xl border border-[#eef4ef]">
                                                    <table class="w-full text-sm">
                                                        <thead>
                                                            <tr class="bg-[#f0f7f2]">
                                                                <th
                                                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                                                    No.
                                                                </th>
                                                                <th
                                                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                                                    Nomor Akses
                                                                </th>
                                                                <th
                                                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">
                                                                    Status
                                                                </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody class="divide-y divide-[#eef4ef] bg-white">
                                                            @forelse($nomorUrutTanaman as $detailIndex => $item)
                                                                @php
                                                                    $status = strtolower($item['status'] ?? '-');

                                                                    $badgeClass = match ($status) {
                                                                        'hidup' => 'bg-[#eef7f1] text-[#2d6a4f]',
                                                                        'mati' => 'bg-red-50 text-red-700',
                                                                        'proses' => 'bg-yellow-50 text-yellow-700',
                                                                        default => 'bg-gray-100 text-gray-600',
                                                                    };
                                                                @endphp

                                                                <tr class="transition hover:bg-[#f8fbf8]">
                                                                    <td class="px-4 py-3 text-center">
                                                                        <span
                                                                            class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#eef7f1] text-xs font-bold text-[#2d6a4f]">
                                                                            {{ $detailIndex + 1 }}
                                                                        </span>
                                                                    </td>

                                                                    <td class="px-4 py-3">
                                                                        <span
                                                                            class="rounded-md bg-[#e8f4ec] px-2 py-1 text-xs font-mono font-semibold text-[#2d6a4f]">
                                                                            {{ $item['nomor_akses'] ?? '-' }}
                                                                        </span>
                                                                    </td>

                                                                    <td class="px-4 py-3">
                                                                        <span
                                                                            class="inline-flex rounded-full px-2 py-1 text-xs font-bold {{ $badgeClass }}">
                                                                            {{ ucfirst($item['status'] ?? '-') }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5"
                                                                        class="px-4 py-6 text-center text-sm text-[#8aaa98]">
                                                                        Tidak ada nomor urut tanaman.
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-10 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <p class="text-sm font-semibold text-[#1a3a2a]">
                                                    Belum ada data tanaman penyemaian.
                                                </p>
                                                <p class="text-xs text-[#8aaa98]">
                                                    Data tanaman akan muncul setelah penyemaian ditambahkan.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="border-t border-[#eef4ef] bg-white px-6 py-4">
                        {{ $groupedTanamanPaginated->links() }}
                    </div>

                    {{-- Summary Footer --}}
                    <div class="grid grid-cols-1 gap-3 border-t border-[#eef4ef] bg-[#f8fbf8] px-6 py-4 sm:grid-cols-3">

                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">
                                Total Spesies
                            </p>
                            <p class="mt-1 text-lg font-bold text-[#2d6a4f]">
                                {{ $groupedTanaman->count() }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">
                                Total Tanaman
                            </p>
                            <p class="mt-1 text-lg font-bold text-[#2d6a4f]">
                                {{ $data->penyemaianTanaman->count() }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">
                                Total Hidup
                            </p>
                            <p class="mt-1 text-lg font-bold text-[#2d6a4f]">
                                {{ $data->penyemaianTanaman->where('status', 'hidup')->count() }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
