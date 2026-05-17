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
                    Kriteria Penilaian
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data kriteria dan bobot penilaian tanaman
                </p>
            </div>
            <a href="{{ route('criteria.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                       bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Kriteria
            </a>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Nama Kriteria
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Bobot
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Tipe
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Skala
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($criteria as $index => $c)
                            <tr class="hover:bg-emerald-50/60 transition border-b border-gray-100 group">
                                <td class="px-5 py-4 text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $c->nama_criteria }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $c->bobot }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $c->tipe }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $c->skala }}</td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('criteria.edit', $c->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('criteria.destroy', $c->id) }}"
                                            class="form-delete">
                                            @csrf
                                            @method('DELETE')
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
                                            <i class="fa-solid fa-sliders text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data Kriteria</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                Mulai dengan menambahkan Kriteria baru
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="2" class="px-5 py-4 text-right text-gray-700">
                                Total Bobot
                            </td>
                            <td class="px-5 py-4 text-[var(--flora-moss)]">
                                {{ $criteria->sum('bobot') }}
                            </td>
                            <td colspan="3"></td>
                        </tr>
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
                        title: 'Hapus data ini?',
                        text: 'Data yang dihapus tidak dapat dikembalikan.',
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
