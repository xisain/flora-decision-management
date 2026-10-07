@extends('layout.app')

@section('content')

<div class="px-4 md:px-8 lg:px-16 py-10">

    {{-- Search --}}
    <div class="flex justify-center mb-12">
        <div
            class="flex items-center border border-gray-500/30 pl-4 gap-2
                   h-[46px] rounded-full overflow-hidden max-w-md w-full"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                viewBox="0 0 30 30"
                fill="#6B7280"
                aria-hidden="true"
            >
                <path d="M13 3C7.489 3 3 7.489 3 13s4.489 10 10 10a9.95 9.95 0 0 0 6.322-2.264l5.971 5.971a1 1 0 1 0 1.414-1.414l-5.97-5.97A9.95 9.95 0 0 0 23 13c0-5.511-4.489-10-10-10m0 2c4.43 0 8 3.57 8 8s-3.57 8-8 8-8-3.57-8-8 3.57-8 8-8"/>
            </svg>

            <input
                type="text"
                placeholder="Cari berita..."
                class="w-full h-full outline-none text-gray-500
                       bg-transparent placeholder-gray-500 text-sm"
            >
        </div>
    </div>


    {{-- Header --}}
    {{-- Header --}}
    <div class="max-w-6xl mx-auto mb-10 flex flex-col items-center text-center">
        <span
            class="inline-block border border-slate-200 rounded-full
                   px-5 py-1 text-sm text-slate-800"
        >
            Berita
        </span>
    
        <h1 class="mt-4 text-4xl md:text-5xl font-medium text-slate-950">
            Berita & Informasi
        </h1>
    
        <p class="mt-3 text-sm text-slate-600 max-w-xl leading-relaxed">
            Temukan informasi terbaru mengenai kegiatan, konservasi,
            penelitian, dan perkembangan Kebun Raya Bundayati.
        </p>
    </div>


    {{-- News Grid --}}
    <div
        class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7"
    >

        @forelse ($beritas as $item)

            <article
                class="bg-slate-50 border border-slate-200 rounded-2xl
                       overflow-hidden group
                       transition duration-300
                       hover:-translate-y-1
                       hover:border-slate-300
                       hover:shadow-sm"
            >

                {{-- Image --}}
                <div class="w-full h-60 overflow-hidden bg-slate-100">

                    @if ($item->image_url)

                        <img
                            src="{{ Str::startsWith($item->image_url, ['bulungan/berita/']) ? Storage::disk('s3')->temporaryUrl($item->image_url, now()->addMinutes(10)) : Storage::url($item->image_url) }}"
                            alt="{{ $item->judul }}"
                            class="w-full h-full object-cover
                                   transition-transform duration-500
                                   group-hover:scale-105"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';"
                        >

                    @else

                        <div class="w-full h-full flex items-center justify-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="42"
                                height="42"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                class="text-slate-300"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.409 2.409M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5V19.5A1.5 1.5 0 003.75 21z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 8.25h.008v.008H8.25V8.25z"
                                />
                            </svg>
                        </div>

                    @endif

                </div>


                {{-- Content --}}
                <div class="p-6">

                    {{-- Meta --}}
                    <div class="flex items-center gap-2 mb-4">

                        @if ($item->kategori_berita_id)
                            <span class="text-xs text-slate-500">
                                {{ $item->kategori?->nama_kategori ?? 'Berita' }}
                            </span>

                            <span class="size-1.5 rounded-full bg-slate-400"></span>
                        @endif

                        <span class="text-xs text-slate-500">
                            {{ $item->created_at?->format('d M Y') }}
                        </span>

                    </div>


                    {{-- Title --}}
                    <h2
                        class="text-lg font-medium text-slate-800
                               leading-6 line-clamp-2"
                    >
                        {{ $item->judul }}
                    </h2>


                    {{-- Description --}}
                    <p
                        class="mt-3 text-sm text-slate-500
                               leading-relaxed line-clamp-3"
                    >
                        {{ Str::limit(strip_tags($item->content), 120) }}
                    </p>


                    {{-- Button --}}
                    <div class="mt-6">

                        <a
                            href="{{ route('berita.detail', $item->slugs) }}"
                            class="inline-flex items-center
                                   border border-slate-200
                                   rounded-full px-5 py-2
                                   text-xs text-slate-800
                                   hover:bg-slate-100
                                   transition-colors"
                        >
                            Baca selengkapnya

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                class="ml-2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 12h14m-6-6l6 6-6 6"
                                />
                            </svg>
                        </a>

                    </div>

                </div>

            </article>

        @empty

            <div class="col-span-full text-center py-20">

                <p class="text-sm text-slate-500">
                    Belum ada berita yang tersedia.
                </p>

            </div>

        @endforelse

    </div>


    {{-- Pagination --}}
    @if ($beritas instanceof \Illuminate\Pagination\AbstractPaginator)

        <div class="max-w-6xl mx-auto mt-12">
            {{ $beritas->links() }}
        </div>

    @endif

</div>

@endsection
