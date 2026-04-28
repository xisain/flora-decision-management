
@extends('layout.admin')
@section('content')
    <div class="p-2 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-2">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Halo,
                    {{ Auth()->user()->name }}!
                </p>
                <h1 class="text-3xl font-semibold text-slate-900">Dashboard FDM</h1>
                <p class="max-w-2xl text-sm text-slate-500">Selamat Datang di Dashboard Pengelolaan Koleksi</p>
            </div>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4"> {{-- Grid Buat Informasi Card --}}
            <div
                class="rounded-3xl border border-slate-200 bg-linear-to-br from-white via-slate-50 to-emerald-50 p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Tanaman Penerimaan</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900">1,234</p>
                {{-- <p class="mt-2 text-sm text-emerald-600">+12% since last week</p> --}}
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-linear-to-br from-white via-slate-50 to-sky-50 p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Tanaman Semai</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900">56</p>
                {{-- <p class="mt-2 text-sm text-sky-600">5 new species added</p> --}}
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-linear-to-br from-white via-slate-50 to-yellow-50 p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Tanaman Inspeksi</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900">842</p>
                {{-- <p class="mt-2 text-sm text-yellow-600">+8% ready for delivery</p> --}}
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-linear-to-br from-white via-slate-50 to-rose-50 p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Tanaman Siap Tanam</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900">23</p>
                {{-- <p class="mt-2 text-sm text-rose-600">2 alerts unresolved</p> --}}
            </div>
        </div>
        <div class="grid grid-cols-6 grid-rows-4 gap-3"> {{-- Grid Buat Chart --}}
            <div class="col-span-3 row-span-5">
                <div
                    class="rounded-3xl border border-slate-200 bg-linear-to-br from-white via-slate-50 to-emerald-50 p-6 shadow-sm">
                    <div id="chart" class="w-full h-[300px]"></div>
                </div>
            </div>
            <div class="col-span-3 row-span-5 col-start-4">
                <div
                    class="rounded-3xl border border-slate-200 bg-linear-to-br from-white via-slate-50 to-emerald-50 p-6 shadow-sm">
                     <div id="chart2" class="w-full h-[300px]"></div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                if (typeof ApexCharts === 'undefined') {
                    console.error('ApexCharts belum load');
                    return;
                }

                var chart = new ApexCharts(document.querySelector("#chart"), {
                    chart: {
                        type: 'line',
                        height: 300
                    },
                    series: [{
                        name: 'sales',
                        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
                    }],
                    xaxis: {
                        categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
                    }
                });

                chart.render();
                    var chart2 = new ApexCharts(document.querySelector("#chart2"), {
                    chart: {
                        type: 'line',
                        height: 300
                    },
                    series: [{
                        name: 'sales',
                        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
                    }],
                    xaxis: {
                        categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
                    }
                });

                chart2.render();
            });
        </script>
    @endpush
@endsection
