@extends('layout.admin')
@section('header', 'Tempat menarik | Flora')
@section('content')
<div class="places-admin px-4 py-6 mx-auto">
    @foreach (['success' => 'bg-green-50 text-green-800 border-green-200', 'warning' => 'bg-amber-50 text-amber-900 border-amber-200'] as $key => $style)
        @if (session($key))<p class="mb-4 px-4 py-3 border rounded-xl text-sm {{ $style }}" role="status">{{ session($key) }}</p>@endif
    @endforeach
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    <header class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-(--flora-moss) tracking-tight">Tempat menarik</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola tempat yang dapat dilihat pengunjung kebun raya.</p>
        </div>
        <a href="{{ route('admin.tempat-menarik.create') }}" class="inline-flex min-h-11 items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-(--flora-moss) rounded-lg hover:bg-(--flora-bark) transition-colors"><i class="fa-solid fa-plus" aria-hidden="true"></i>Tambah tempat</a>
    </header>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <form method="GET" action="{{ route('admin.tempat-menarik.index') }}" class="flex flex-wrap items-end gap-3 px-5 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex-1 min-w-0">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari nama tempat</label>
                <input id="search" name="search" value="{{ $search }}" maxlength="255" class="place-admin-input">
            </div>
            <button type="submit" class="min-h-11 px-4 py-2 text-sm font-medium text-white bg-(--flora-moss) rounded-lg hover:bg-(--flora-bark) transition-colors">Cari</button>
            @if ($search)<a href="{{ route('admin.tempat-menarik.index') }}" class="inline-flex min-h-11 items-center px-3 text-sm text-gray-700 underline">Reset</a>@endif
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-600 border-b border-gray-100">
                    <tr>
                        @foreach (['Nama tempat', 'Kategori', 'Status', 'Landing', 'Urutan', 'Aksi'] as $heading)<th scope="col" class="px-5 py-3.5 font-semibold">{{ $heading }}</th>@endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($places as $place)
                        <tr class="hover:bg-emerald-50/60 transition-colors">
                            <td class="px-5 py-4 text-gray-800 font-medium break-words">{{ $place->nama }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ \App\Models\TempatMenarik::CATEGORIES[$place->kategori] }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $place->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $place->is_featured ? 'Ditampilkan' : 'Tidak' }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $place->urutan }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.tempat-menarik.edit', $place) }}" class="inline-flex min-h-11 items-center px-3 rounded-lg text-(--flora-moss) hover:bg-(--flora-sage-pale)" aria-label="Edit {{ $place->nama }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.tempat-menarik.destroy', $place) }}" data-place-delete data-place-name="{{ $place->nama }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="min-h-11 px-3 rounded-lg text-red-700 hover:bg-red-50" aria-label="Hapus {{ $place->nama }}">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-16 text-center text-gray-600">{{ $search ? 'Tidak ada tempat yang sesuai pencarian.' : 'Belum ada tempat menarik. Klik Tambah tempat untuk memulai.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $places->links() }}</div>
    </div>
</div>
@endsection
