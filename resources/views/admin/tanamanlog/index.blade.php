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
                    Log Tanaman
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Rekam jejak status dan perjalanan tanaman koleksi
                </p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Nomor Akses &amp; Koleksi
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Nama Scientific
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Status Tanaman
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Stage Tanaman
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Penanggung Jawab
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Time Process / Time Of Death
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($data as $index => $item)
                            <tr class="hover:bg-emerald-50/60 transition border-b border-gray-100 group">
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $data->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->tanaman->nomor_akses }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    <p class="text-gray-800 italic font-bold">
                                        {{ $item->tanaman->tanamanPenerimaan->tanamanInfo->scientific_name }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $item->tanaman->tanamanPenerimaan->tanamanInfo->author_name }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ str()->upper($item->status) }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ str()->upper($item->stage) }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->user->name }}
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $item->tanggal_proses }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-leaf text-gray-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-500">Belum ada data Log Tanaman</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                Log akan muncul setelah ada aktivitas tanaman
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (isset($data) && method_exists($data, 'hasPages') && $data->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    <div class="flora-pagination">
                        {{ $data->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
