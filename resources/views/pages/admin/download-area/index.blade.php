@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    Download Area
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Download data permohonan dalam format Excel. Pilih layanan dan rentang tanggal yang diinginkan.
                </p>
            </div>
        </div>

        <!-- Download Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-lg p-8 border border-gray-100 dark:border-gray-700/50">
            <form action="{{ route('admin.download-area.preview') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Service Selection -->
                    <div>
                        <label for="service" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Pilih Layanan <span class="text-red-500">*</span>
                        </label>
                        <select id="service" name="service" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Tanggal Mulai <span class="text-gray-500 text-xs">(Opsional)</span>
                        </label>
                        <input id="start_date" type="date" name="start_date"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Tanggal Akhir <span class="text-gray-500 text-xs">(Opsional)</span>
                        </label>
                        <input id="end_date" type="date" name="end_date"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    </div>
                </div>

                <!-- Info Message -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/30 rounded-lg p-4">
                    <p class="text-sm text-blue-900 dark:text-blue-100 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        <span>Biarkan tanggal kosong untuk mendownload semua data tanpa filter tanggal.</span>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-eye"></i>
                        Lihat Pratinjau
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border border-green-200 dark:border-green-700/30 rounded-2xl p-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-lightbulb text-yellow-500"></i>
                Informasi
            </h2>
            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                <li class="flex items-start gap-3">
                    <span class="text-green-600 dark:text-green-400 mt-1">✓</span>
                    <span>Download data dalam format Excel yang mudah diproses</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-green-600 dark:text-green-400 mt-1">✓</span>
                    <span>Pilih rentang tanggal spesifik atau download semua data</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-green-600 dark:text-green-400 mt-1">✓</span>
                    <span>Lihat pratinjau data sebelum download</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-green-600 dark:text-green-400 mt-1">✓</span>
                    <span>File diunduh dengan nama yang terstruktur dan tanggal</span>
                </li>
            </ul>
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
