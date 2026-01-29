@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900">
    <div class="container px-4 mx-auto py-10">
        <div class="mt-12 mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="/layanan" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">
                    Layanan
                </a>
                <i class="text-sm text-gray-400 fa-solid fa-angle-right"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Peta Sebaran
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Layanan peta sebaran sedang dalam tahap pengembangan.
            </p>
        </div>

        <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i> Sedang Dikembangkan
            </h4>
            <p class="text-blue-800 dark:text-blue-200 text-sm">
                Fitur peta sebaran akan segera tersedia. Silakan kembali lagi nanti untuk informasi lebih lanjut.
            </p>
        </div>
    </div>
</div>
@endsection
