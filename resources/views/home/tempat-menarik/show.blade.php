@extends('layout.app')
@section('title', $place->nama.' | Kebun Raya Bundayati')
@section('content')
<article class="public-places public-container">
    <nav class="place-breadcrumb" aria-label="Breadcrumb">
        <a class="public-text-link" href="{{ route('landing') }}">Beranda</a>
        <span aria-hidden="true">/</span>
        <a class="public-text-link" href="{{ route('public.tempat-menarik.index') }}">Tempat menarik</a>
    </nav>
    <header class="public-places-heading">
        <p class="section-label">{{ \App\Models\TempatMenarik::CATEGORIES[$place->kategori] }}</p>
        <h1>{{ $place->nama }}</h1>
    </header>
    <x-tempat-menarik-photo class="place-detail-photo" :url="$photoUrl" :name="$place->nama" :eager="true" />
    <div class="place-description">{{ $place->deskripsi }}</div>
    <a class="public-text-link" href="{{ route('public.tempat-menarik.index') }}">Kembali ke tempat menarik</a>
</article>
@endsection
