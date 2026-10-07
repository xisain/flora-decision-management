@extends('layout.app')
@section('title', 'Tempat menarik | Kebun Raya Bundayati')
@section('content')
<div class="public-places public-container">
    <header class="public-places-heading">
        <p class="section-label">Di dalam kawasan</p>
        <h1>Tempat menarik</h1>
        <p>Kenali tempat dan area di Kebun Raya Bundayati sebelum berkunjung.</p>
    </header>
    <nav class="place-filters" aria-label="Filter kategori tempat">
        <a href="{{ route('public.tempat-menarik.index') }}" @if (!$category) aria-current="page" @endif>Semua tempat</a>
        @foreach (\App\Models\TempatMenarik::CATEGORIES as $value => $label)
            <a href="{{ route('public.tempat-menarik.index', ['kategori' => $value]) }}" @if ($category === $value) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>
    <div class="public-places-grid">
        @forelse ($places as $place)
            <x-tempat-menarik-card :place="$place" :photo-url="$photoUrls[$place->id] ?? null" />
        @empty
            <div class="places-empty">
                <h2>{{ $category ? 'Belum ada tempat dalam kategori ini.' : 'Tempat menarik sedang disiapkan.' }}</h2>
                <p>Informasi tempat akan tampil setelah diterbitkan.</p>
                @if ($category)
                    <a class="public-text-link" href="{{ route('public.tempat-menarik.index') }}">Lihat semua kategori</a>
                @endif
            </div>
        @endforelse
    </div>
    <div class="public-places-pagination">{{ $places->links() }}</div>
</div>
@endsection
