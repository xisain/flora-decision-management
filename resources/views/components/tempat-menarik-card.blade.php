@props(['place', 'photoUrl' => null])
<article class="place-card">
    <x-tempat-menarik-photo :url="$photoUrl" :name="$place->nama" />
    <div class="place-card-copy">
        <p class="section-label">{{ \App\Models\TempatMenarik::CATEGORIES[$place->kategori] }}</p>
        <h3><a href="{{ route('public.tempat-menarik.show', $place) }}">{{ $place->nama }}</a></h3>
        <p>{{ \Illuminate\Support\Str::limit($place->deskripsi, 150) }}</p>
        <a class="public-text-link" href="{{ route('public.tempat-menarik.show', $place) }}" aria-label="Lihat tempat: {{ $place->nama }}">Lihat tempat</a>
    </div>
</article>
