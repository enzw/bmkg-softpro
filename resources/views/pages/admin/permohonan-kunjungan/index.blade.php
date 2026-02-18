@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-2">Kunjungan Teknis
                </h1>
                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 dark:bg-emerald-900/20 text-green-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-widest shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Update Terakhir
                    </span>
                    <span
                        class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">{{ $permohonan->count() }}
                        Data Ditemukan</span>
                </div>
            </div>
            <a href="{{ route('admin.permohonan-kunjungan.create') }}"
                class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-green-500 hover:bg-green-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-[1.5rem] transition-all duration-300 shadow-lg shadow-green-200 dark:shadow-none font-bold text-xs uppercase tracking-widest group">
                <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                Permohonan Baru
            </a>
        </div>

        <!-- Content Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div
                class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 dark:text-emerald-400">
                        <i class="fas fa-bus text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Daftar
                            Permohonan</h2>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">Managemen permohonan
                            kunjungan teknis</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                @include('components.table-permohonan-kunjungan-admin', ['permohonan' => $permohonan])
            </div>
        </div>
    </div>
@endsection