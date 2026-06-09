@extends('layout.admin')
@section('content')
    {{-- Error Alert --}}
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

    <div class="px-4 py-6 mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--flora-moss)] tracking-tight">
                    User &amp; Collector
                </h1>
                <p class="text-sm text-[var(--flora-stone)] mt-1">
                    Kelola data pengguna dan kolektor tanaman
                </p>
            </div>
            <a href="{{ route('user.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white
                       bg-[var(--flora-teal)] rounded-lg hover:bg-[var(--flora-moss)] transition-colors duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah User
            </a>
        </div>

        {{-- Table Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            {{-- Toolbar --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <form method="GET" action="{{ route('user.index') }}"
                    class="flex flex-wrap items-center gap-3 w-full">

                    {{-- Search --}}
                    <div class="relative flex-1 sm:w-64 max-w-xs">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)]
                                   focus:border-transparent bg-white">
                    </div>

                    {{-- Role Filter --}}
                    <select name="role"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white
                               focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] text-gray-600">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Status Filter --}}
                    <select name="status"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white
                               focus:outline-none focus:ring-2 focus:ring-[var(--flora-teal)] text-gray-600">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[var(--flora-teal)]
                                   rounded-lg hover:bg-[var(--flora-moss)] transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
                        </button>
                        @if (request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('user.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100
                                       rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                                <i class="fa-solid fa-xmark text-xs mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-emerald-50/70 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest w-8">#</th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Pengguna
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                No. Telepon
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Role
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-widest">
                                Status
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold text-gray-700 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-emerald-50/60 transition border-b border-gray-200 group">
                                {{-- No --}}
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $users->firstItem() + $index }}
                                </td>

                                {{-- User Info --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[var(--flora-teal)] flex items-center justify-center text-white text-xs font-semibold shrink-0 uppercase">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 leading-tight">
                                                {{ $user->name }}
                                                <span class="text-blue-300">({{ $user->collectorInfo?->initial_collector_name }})</span>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Phone --}}
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $user->phone_number ?? '-' }}
                                </td>

                                {{-- Role --}}
                                <td class="px-5 py-4">
                                    @if ($user->roles)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[var(--flora-teal)]/10 text-[var(--flora-moss)]">
                                            {{ $user->roles->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4">
                                    @if ($user->account_status === 1)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                        {{-- <a href="{{ route('user.show', $user->id) }}" title="Detail"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-[var(--flora-teal)] hover:bg-[var(--flora-teal)]/10 transition-colors">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a> --}}
                                        <a href="{{ route('user.edit', $user->id) }}" title="Edit"
                                            class="p-1.5 rounded-md text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form method="POST" action="{{ route('user.destroy', $user) }}"
                                            class="form-delete" data-user="{{ $user->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="delete_collector" value="0"
                                                class="delete-collector-input">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
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
                                            <p class="font-medium text-gray-500">Tidak ada data user</p>
                                            <p class="text-xs mt-1 text-gray-400">
                                                @if (request()->hasAny(['search', 'role', 'status']))
                                                    Coba ubah filter pencarian
                                                @else
                                                    Mulai dengan menambahkan user baru
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

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    <div class="flora-pagination">
                        {{ $users->withQueryString()->links() }}
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

                    const userName = form.dataset.user;

                    Swal.fire({
                        title: 'Hapus User?',
                        html: `
                            <p class="text-sm text-gray-600 mb-3">
                                Yakin ingin menghapus <b>${userName}</b>?
                            </p>
                            <label style="display:flex;align-items:center;gap:8px;font-size:14px;">
                                <input type="checkbox" id="deleteCollector">
                                Hapus juga data collector
                            </label>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#ef4444',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const checkbox = document.getElementById('deleteCollector');
                            const input = form.querySelector('.delete-collector-input');
                            input.value = checkbox.checked ? 1 : 0;
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
