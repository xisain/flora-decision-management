@extends('layout.admin')
@section('content')

<div class="px-4 py-6">

    {{-- Page Header --}}
    <div class="mb-8 flex items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1a3a2a]">Detail Penerimaan</h1>
            <p class="text-sm text-[#5a7a6a]">Formulir Eksplorasi &middot; {{ $data->tanggal_penerimaan }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- LEFT COLUMN --}}
        <div class="flex flex-col gap-6 lg:col-span-1">

            {{-- General Information --}}
            <div class="rounded-2xl border border-[#d8e8d8] bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-2">
                    <h2 class="text-base font-bold uppercase tracking-widest text-[#1a3a2a]">General Information</h2>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Tanggal Penerimaan</span>
                        <span class="text-sm font-medium text-[#1a3a2a]">{{ $data->tanggal_penerimaan }}</span>
                    </div>
                    <div class="h-px bg-[#eef4ef]"></div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Tanggal Eksplorasi</span>
                        <span class="text-sm font-medium text-[#1a3a2a]">{{ $data->tanggal_explorasi }}</span>
                    </div>
                    <div class="h-px bg-[#eef4ef]"></div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Tempat Asal</span>
                        <span class="text-sm font-medium text-[#1a3a2a]">{{ $data->tempat_asal }}</span>
                    </div>
                    <div class="h-px bg-[#eef4ef]"></div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Source</span>
                        <span class="text-sm font-medium text-[#1a3a2a]">{{ $data->source }}</span>
                    </div>
                    <div class="h-px bg-[#eef4ef]"></div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Native</span>
                        <span class="text-sm font-medium text-[#1a3a2a]">{{ $data->native }}</span>
                    </div>
                    <div class="h-px bg-[#eef4ef]"></div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Dicatat Oleh</span>
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#2d6a4f] text-xs font-bold text-white">
                                {{ strtoupper(substr($data->user->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-[#1a3a2a]">{{ $data->user->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tim Eksplorasi --}}
            <div class="rounded-2xl border border-[#d8e8d8] bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-1 w-6 rounded-full bg-[#52a870]"></div>
                    <h2 class="text-base font-bold uppercase tracking-widest text-[#1a3a2a]">Tim Explorasi</h2>
                </div>

                @if($data->timExplorasi)
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#eef7f1] text-[#2d6a4f]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Nama Tim</p>
                            <p class="text-sm font-medium text-[#1a3a2a]">{{ $data->timExplorasi->nama_tim }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#eef7f1] text-[#2d6a4f]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Deskripsi</p>
                            <p class="text-sm font-medium text-[#1a3a2a]">{{ $data->timExplorasi->deskripsi_team }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#eef7f1] text-[#2d6a4f]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#8aaa98]">Lokasi Eksplorasi</p>
                            <p class="text-sm font-medium text-[#1a3a2a]">{{ $data->timExplorasi->lokasi_explorasi }}</p>
                        </div>
                    </div>
                </div>
                @else
                <p class="text-sm text-[#8aaa98]">Data tim eksplorasi tidak tersedia.</p>
                @endif
            </div>

            {{-- Legal Documents --}}
            <div class="rounded-2xl border border-[#d8e8d8] bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-1 w-6 rounded-full bg-[#e0a458]"></div>
                    <h2 class="text-base font-bold uppercase tracking-widest text-[#1a3a2a]">Legal Documents</h2>
                </div>

                @forelse($data->legalDocument as $doc)
                <div class="flex items-center justify-between gap-3 rounded-xl border border-[#eef4ef] bg-[#f8fbf8] p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#2d6a4f] text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1a3a2a]">{{ $doc->nama_surat }}</p>
                            <p class="text-xs text-[#8aaa98]">{{ $doc->nomor_surat }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $doc->path_file) }}"
                       target="_blank"
                       class="flex items-center gap-1.5 rounded-lg border border-[#2d6a4f] px-3 py-1.5 text-xs font-semibold text-[#2d6a4f] transition hover:bg-[#2d6a4f] hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </a>
                </div>
                @empty
                <p class="text-sm text-[#8aaa98]">Tidak ada dokumen legal.</p>
                @endforelse
            </div>

        </div>

        {{-- RIGHT COLUMN: Plant List --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-[#d8e8d8] bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-[#eef4ef] px-6 py-4">
                    <div class="flex items-center gap-2">
                        <div class="h-1 w-6 rounded-full bg-[#2d6a4f]"></div>
                        <h2 class="text-base font-bold uppercase tracking-widest text-[#1a3a2a]">Plant List</h2>
                    </div>
                    <span class="rounded-full bg-[#eef7f1] px-3 py-1 text-xs font-bold text-[#2d6a4f]">
                        {{$data->penerimaanTanaman->count() }} Spesies
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#f0f7f2]">
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">No.</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">Scientific Name</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">Nomor Akses</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">Nama Lokal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">Suku</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">Vak No.</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#5a7a6a]">Locality</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#eef4ef]">
                            @foreach($data->penerimaanTanaman as $index => $tanaman)
                            <tr class="transition hover:bg-[#f8fbf8] {{ $index % 2 === 0 ? 'bg-white' : 'bg-[#fafcfa]' }}">
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#eef7f1] text-xs font-bold text-[#2d6a4f]">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-semibold italic text-[#1a3a2a]">{{ $tanaman->scientific_name }}</p>
                                        <p class="text-xs text-[#8aaa98]">{{ $tanaman->author_name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-md bg-[#e8f4ec] px-2 py-1 text-xs font-mono font-semibold text-[#2d6a4f]">
                                        {{ $tanaman->nomor_akses }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium text-[#1a3a2a]">{{ $tanaman->nama_lokal }}</td>
                                <td class="px-4 py-3 text-[#5a7a6a]">{{ $tanaman->suku }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 font-semibold text-[#1a3a2a]">
                                        {{ $tanaman->jumlah_material }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs font-mono text-[#5a7a6a]">{{ $tanaman->vak_no }}</td>
                                <td class="px-4 py-3 text-[#5a7a6a]">{{ $tanaman->locality }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary Footer --}}
                <div class="flex items-center justify-between border-t border-[#eef4ef] bg-[#f8fbf8] px-6 py-3">
                    <p class="text-xs text-[#8aaa98]">Total material yang diterima</p>
                    <p class="text-sm font-bold text-[#2d6a4f]">
                        {{ $data->penerimaanTanaman->sum('jumlah_material') }} material
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
