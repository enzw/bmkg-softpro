@extends('layouts.main')

@section('content')
<div class="container px-4 mx-auto py-10">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Dashboard Pelayanan
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Selamat datang, {{ Auth::user()->name }}! Pilih layanan yang ingin Anda gunakan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($layanan as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 hover:-translate-y-2 flex flex-col">
            <!-- Image Section -->
            <div class="overflow-hidden h-48 bg-gray-200 dark:bg-gray-700">
                <img 
                    src="{{ asset($item['images']) }}" 
                    alt="{{ $item['nama'] }}" 
                    class="w-full h-full object-cover hover:scale-110 transition duration-300"
                >
            </div>

            <!-- Content Section -->
            <div class="p-6 flex flex-col flex-grow">
                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ $item['nama'] }}
                </h3>

                <!-- Description -->
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-6 flex-grow">
                    {{ $item['deskripsi'] }}
                </p>

                <!-- CTA Button -->
                <a href="{{ $item['url'] }}" 
                    class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 transform hover:scale-105 text-center">
                    {{ $item['cta'] }}
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Additional Info Section -->
    <div class="mt-12 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
        <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">
            <i class="fas fa-info-circle mr-2"></i> Informasi Penting
        </h4>
        <p class="text-blue-800 dark:text-blue-200 text-sm">
            Semua layanan yang kami sediakan telah disesuaikan dengan peraturan perundang-undangan yang berlaku. 
            Untuk informasi lebih lanjut atau konsultasi, silakan hubungi tim kami melalui fitur kontak yang tersedia.
        </p>
    </div>
</div>
@endsection
