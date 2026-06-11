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
                    Penerimaan Koleksi Kebun Raya
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">Kelola data Penerimaan Koleksi</p>
            </div>
            <a href="{{ route('peneliti.penerimaan.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                       bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Penerimaan
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <form action="{{ route('peneliti.penerimaan.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-3 w-full">

                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Tgl. Penerimaan
                        </label>
                        <div class="flex items-center gap-1">
                            <input type="date" name="penerimaan_dari" value="{{ request('penerimaan_dari') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white" />
                            <span class="text-gray-400 text-xs">–</span>
                            <input type="date" name="penerimaan_sampai" value="{{ request('penerimaan_sampai') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-0.5">
                        <label class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">
                            Tgl. Eksplorasi
                        </label>
                        <div class="flex items-center gap-1">
                            <input type="date" name="eksplorasi_dari" value="{{ request('eksplorasi_dari') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white" />
                            <span class="text-gray-400 text-xs">–</span>
                            <input type="date" name="eksplorasi_sampai" value="{{ request('eksplorasi_sampai') }}"
                                class="py-2 px-3 text-sm border border-gray-200 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                       focus:border-transparent bg-white" />
                        </div>
                    </div>

                    <div class="flex items-end gap-2 self-end">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[var(--flora-teal)]
                                   rounded-lg hover:bg-[var(--flora-moss)] transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
                        </button>
                        @if (request()->hasAny(['penerimaan_dari', 'penerimaan_sampai', 'eksplorasi_dari', 'eksplorasi_sampai']))
                            <a href="{{ route('peneliti.penerimaan.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100
                                       rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                                <i class="fa-solid fa-xmark text-xs mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Tanggal Penerimaan
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Tanggal Eksplorasi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Jumlah Koleksi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Jumlah Material
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Nama Penanggung Jawab
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($penerimaan as $index => $p)
                            <tr class="hover:bg-emerald-50/60 transition border-b border-gray-200 group">
                                <td class="px-5 py-4 text-gray-700">{{ $penerimaan->firstItem() + $index }}</td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ \Carbon\Carbon::parse($p->tanggal_penerimaan)->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ \Carbon\Carbon::parse($p->tanggal_explorasi)->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $p->penerimaanTanaman?->count() }} Koleksi
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $p->penerimaanTanaman?->sum('jumlah_material') }} Material
                                </td>
                                <td class="px-5 py-4 text-gray-700">{{ $p->user?->name }}</td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('peneliti.penerimaan.show', $p->id) }}" title="Detail"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('peneliti.penerimaan.edit', $p->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('peneliti.penerimaan.destroy', $p->id) }}"
                                            class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="delete_tanaman_penerimaan" value="0"
                                                class="delete-peneriaam-tanaman">
                                            <input type="hidden" name="penerimaan_id" value="{{ $p->id }}">
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
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-inbox text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data Penerimaan</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                @if (request()->hasAny(['penerimaan_dari', 'penerimaan_sampai', 'eksplorasi_dari', 'eksplorasi_sampai']))
                                                    Coba ubah filter pencarian
                                                @else
                                                    Mulai dengan menambahkan Penerimaan baru
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $penerimaan->links() }}
                </div>
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
