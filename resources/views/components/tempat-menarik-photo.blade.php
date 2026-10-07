@props(['url' => null, 'name', 'eager' => false])
<div {{ $attributes->class(['place-photo']) }}
     x-data="{ failed: false, loaded: false }"
     x-init="$nextTick(() => { const image = $el.querySelector('img'); if (image?.complete) { loaded = image.naturalWidth > 0; failed = !loaded; } })">
    @if ($url)
        <p class="image-state" x-show="!loaded && !failed" role="status">Memuat foto tempat…</p>
        <p class="image-state" x-show="failed" x-cloak>Foto tempat tidak dapat dimuat.</p>
        <img src="{{ $url }}" alt="{{ $name }}" width="1200" height="800"
             loading="{{ $eager ? 'eager' : 'lazy' }}" x-show="loaded && !failed"
             x-on:load="loaded = true" x-on:error="failed = true">
    @else
        <p class="image-state">Foto tempat belum tersedia.</p>
    @endif
</div>
