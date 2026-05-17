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
    @if (session('success'))
        <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    <div class="px-4 py-6 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    Data Kolektor
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data kolektor tanaman
                </p>
            </div>
            <a href="{{ route('collector.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                       bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Kolektor
            </a>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Pengguna
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Nama Panjang
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Inisial Kolektor
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Jumlah Koleksi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($collector as $index => $c)
                            <tr class="hover:bg-emerald-50/60 transition border-b border-gray-200 group">
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $collector->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($c->user)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-[var(--flora-teal)] flex items-center justify-center text-white text-xs font-semibold shrink-0 uppercase">
                                                {{ mb_substr($c->user?->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 leading-tight">{{ $c->user?->name }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $c->user?->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-semibold shrink-0">
                                                ?
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-500 leading-tight">Tidak Terikat Pengguna</p>
                                                <p class="text-xs text-gray-400 mt-0.5">-</p>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $c->full_name }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $c->initial_collector_name }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ count($c->penerimaanTanaman) }} Tanaman
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('collector.show', $c->id) }}" title="Detail"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('collector.edit', $c->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('collector.destroy', $c) }}"
                                            class="form-delete" data-user="{{ $c->full_name }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="user_id" value="{{ $c->id }}">
                                            <button type="submit"
                                                class="btn-delete p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-users text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Tidak ada data Kolektor</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                Mulai dengan menambahkan kolektor baru
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($collector->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    <div class="flora-pagination">
                        {{ $collector->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Kolektor?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
