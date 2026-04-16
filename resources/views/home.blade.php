@extends('layout.app')


@section('content')
<section class="hero-section bg-white">
    <div class="hero-container mx-auto px-4 py-10 lg:py-16 max-w-screen-xl">
        <div class="hero-inner grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
            <div class="hero-copy">
                <p class="hero-eyebrow">Kebun Raya Daerah</p>
                <h1 class="hero-title">Kebun Raya Bundayati</h1>
                <p class="hero-subtitle"></p>
                <div class="hero-actions">
                    @guest
                        <button class="button--primary">Explore</button>
                    @endguest
                    @auth
                        <a href="" class="button--primary" style="text-decoration: none; display: inline-block;">
                            Go to Dashboard
                        </a>
                    @endauth
                </div>
            </div>
            <img class="" src="{{ asset('storage/images/header.png') }}" alt=""/>
        </div>
    </div>
</section>
<section class="bg-white py-12">
    <div class="max-w-screen-xl mx-auto px-4 space-y-16">

        <!-- ===================== -->
        <!-- Tempat Menarik -->
        <!-- ===================== -->
        <div class="border-t pt-10">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700">
                    Tempat Menarik
                </h2>
                <p class="text-sm text-gray-400 mt-2 max-w-md">
                    Welcome to Burger Bliss, where we take your cravings to a whole new level!
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @for ($i = 0; $i < 3; $i++)
                    <div class="bg-gray-100 rounded-xl h-40 flex items-center justify-center">
                        <i class="fa-regular fa-image text-gray-400 text-2xl"></i>
                    </div>
                @endfor
            </div>
        </div>

        <!-- ===================== -->
        <!-- Berita -->
        <!-- ===================== -->
        <div class="border-t pt-10">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">
                Berita Seputar Kebun Raya
            </h2>

            <div class="grid md:grid-cols-2 gap-8">

                <!-- LEFT TEXT LIST -->
                <div class="space-y-6">
                    @for ($i = 0; $i < 3; $i++)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700">
                                Lorem ipsum is simply dummy
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">
                                Where can I get this? Where can I get this?
                            </p>
                            <button class="mt-2 text-xs border px-3 py-1 rounded-full text-gray-500 hover:bg-gray-100">
                                Read more
                            </button>
                        </div>
                    @endfor
                </div>

                <!-- RIGHT IMAGE LIST -->
                <div class="space-y-4">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="bg-gray-100 rounded-xl h-24 flex items-center justify-center">
                            <i class="fa-regular fa-image text-gray-400"></i>
                        </div>
                    @endfor
                </div>

            </div>
        </div>

        <!-- ===================== -->
        <!-- Koleksi Unggulan -->
        <!-- ===================== -->
        <div class="border-t pt-10">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700">
                    Koleksi Unggulan
                </h2>
                <p class="text-sm text-gray-400 mt-2 max-w-md">
                    Explore our curated plant collections from Kebun Raya.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @for ($i = 0; $i < 3; $i++)
                    <div class="bg-gray-100 rounded-xl h-40 flex items-center justify-center">
                        <i class="fa-regular fa-image text-gray-400 text-2xl"></i>
                    </div>
                @endfor
            </div>
        </div>

    </div>
</section>
@endsection


