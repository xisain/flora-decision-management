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

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="mb-4 flex gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm">
            <div class="shrink-0 pt-0.5">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    {{ $collector }}
    <div class="mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    User & Collector
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data user dan collector
                </p>
            </div>
            <a href="{{ route('collector.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah User
            </a>
        </div>
        <div class="bg-white border border-[var(--flora-sage-mid)] rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-[var(--flora-sage-mid)]">
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                #</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Pengguna</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama Panjang </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Inisial Kolektor</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Jumlah Koleksi</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($collector as $index => $c)
                            <tr class="hover:bg-gray-50/70 transition-colors duration-150 group">
                                <td class="px-5 py-3.5 text-gray-400 text-xs">
                                    {{ $collector->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">
                                    @if ($c->user)
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-[var(--flora-teal)] flex items-center justify-center text-white text-xs font-semibold shrink-0 uppercase">
                                                {{ mb_substr($c->user?->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 leading-tight">{{ $c->user?->name }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $c->user?->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-[var(--flora-teal)] flex items-center justify-center text-white text-xs font-semibold shrink-0 uppercase">
                                                {{ mb_substr('?', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 leading-tight">Tidak Terikat Pengguna
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5">-</p>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    {{ $c->full_name }}
                                </td>
                                <td class="px-5 py-3.5">
                                    {{ $c->initial_collector_name }}
                                </td>
                                <td class="px-5 py-3.5">
                                    {{ count($c->penerimaanTanaman)  }} Tanaman
                                </td>
                                <td class="px-5 py-3.5">
                                    <div
                                        class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                        {{-- Detail --}}
                                        <a href="{{ route('collector.show', $c->id) }}" title="Detail"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('collector.edit', $c->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>


                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('collector.destroy', $c) }}"
                                            class="form-delete" data-user="{{ $c->name }}">
                                            @csrf
                                            @method('DELETE')

                                            {{-- hidden input untuk collector --}}
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
                                        <i class="fa-solid fa-users text-4xl opacity-30"></i>
                                        <div>
                                            <p class="font-medium text-gray-500">Tidak ada data Collector</p>
                                            <p class="text-xs mt-1">
                                                @if (request()->hasAny(['search', 'role', 'status']))
                                                    Coba ubah filter pencarian
                                                @else
                                                    Mulai dengan menambahkan collector baru
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Collector?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#ef4444',
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
