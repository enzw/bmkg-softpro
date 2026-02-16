@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-2">Download Area
                </h1>
                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 dark:bg-emerald-900/20 text-green-600 dark:text-emerald-400 text-[10px] font-semibold uppercase tracking-widest shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Data Management
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest text-shadow-sm">Export
                        Data
                        to Excel</span>
                </div>
            </div>
        </div>

        <!-- Download Form Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div
                class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 dark:text-emerald-400">
                        <i class="fas fa-file-excel text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Export Filter
                        </h2>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest text-shadow-sm">Pilih
                            layanan dan rentang tanggal</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('admin.download-area.preview') }}" method="GET" class="space-y-8">
                    <!-- Service Selection -->
                    <div class="relative pl-6 border-l-2 border-green-500/30">
                        <h4
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Pilih Layanan <span class="text-red-500">*</span>
                        </h4>
                        <select id="service" name="service" required
                            class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-bold text-xs uppercase tracking-widest">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="relative pl-6 border-l-2 border-blue-500/30">
                        <h4
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Rentang Tanggal <span
                                class="text-gray-400 font-bold lowercase italic text-[8px]">(Opsional)</span>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div class="space-y-2">
                                <label for="start_date"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Tanggal
                                    Mulai</label>
                                <input id="start_date" type="date" name="start_date"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-black text-xs uppercase tracking-widest" />
                            </div>

                            <!-- End Date -->
                            <div class="space-y-2">
                                <label for="end_date"
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Tanggal
                                    Akhir</label>
                                <input id="end_date" type="date" name="end_date"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-black text-xs uppercase tracking-widest" />
                            </div>
                        </div>
                    </div>

                    <!-- Info Message -->
                    <div
                        class="flex items-center gap-4 p-6 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20 rounded-[1.5rem] mt-6">
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 shrink-0">
                            <i class="fas fa-info text-sm"></i>
                        </div>
                        <p
                            class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-widest leading-relaxed">
                            Biarkan tanggal kosong untuk mendownload semua data tanpa filter tanggal. Pratinjau akan
                            ditampilkan sebelum download file.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-green-500 hover:bg-green-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-2xl transition-all duration-300 shadow-lg shadow-green-200 dark:shadow-none font-bold text-xs uppercase tracking-widest group">
                            <i class="fas fa-eye group-hover:scale-110 transition-transform"></i>
                            Lihat Pratinjau
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div
            class="bg-gradient-to-br from-green-50 to-blue-50 dark:from-green-900/10 dark:to-blue-900/10 border border-green-100 dark:border-gray-700/50 rounded-[2.5rem] p-10 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-10 opacity-10 pointer-events-none">
                <i class="fas fa-lightbulb text-9xl text-yellow-500"></i>
            </div>
            <div class="relative z-10">
                <h2
                    class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-8 flex items-center gap-3">
                    <span
                        class="w-8 h-8 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600">
                        <i class="fas fa-lightbulb text-xs"></i>
                    </span>
                    Panduan Download
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl bg-white/50 dark:bg-gray-800/30 backdrop-blur-sm border border-white dark:border-gray-700">
                        <div
                            class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center text-green-600 shrink-0">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-widest">
                            Excel
                            Format yang mudah diproses (XLSX)</p>
                    </div>
                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl bg-white/50 dark:bg-gray-800/30 backdrop-blur-sm border border-white dark:border-gray-700">
                        <div
                            class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center text-green-600 shrink-0">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">Filter
                            per layanan dan rentang waktu spesifik</p>
                    </div>
                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl bg-white/50 dark:bg-gray-800/30 backdrop-blur-sm border border-white dark:border-gray-700">
                        <div
                            class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center text-green-600 shrink-0">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">Sistem
                            pratinjau data sebelum export</p>
                    </div>
                    <div
                        class="flex items-start gap-4 p-4 rounded-2xl bg-white/50 dark:bg-gray-800/30 backdrop-blur-sm border border-white dark:border-gray-700">
                        <div
                            class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center text-green-600 shrink-0">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-widest">
                            Naming
                            convention file otomatis berbasis waktu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0) brightness(1);
            cursor: pointer;
        }

        @media (prefers-color-scheme: dark) {
            input[type="date"]::-webkit-calendar-picker-indicator {
                filter: invert(1) brightness(2);
            }
        }
    </style>
@endsection