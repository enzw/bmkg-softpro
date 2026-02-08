@extends('layouts.main')

@section('title', 'Edit Permohonan Pelayanan')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="container px-4 mx-auto py-12">
            <!-- Header Section -->
            <div class="mt-12 mb-12">
                <div class="flex items-center gap-2 mb-4">
                    <a href="{{ route('layanan') }}"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                        Layanan
                    </a>
                    <span class="text-gray-300 dark:text-gray-600">/</span>
                    <a href="{{ route('pelayanan-jasa.index') }}"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                        Pelayanan Informasi Geofisika
                    </a>
                    <span class="text-gray-300 dark:text-gray-600">/</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Edit</span>
                </div>
                <div class="mb-2">
                    <h1 class="text-5xl font-bold text-gray-900 dark:text-white">Edit {{ $jenis_layanan }}</h1>
                </div>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl">
                    Ubah data permohonan {{ $jenis_layanan }}. Dokumen bersifat opsional saat edit.
                </p>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-4xl">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                        @include('components.form-pelayanan-jasa-tabs', ['is_edit' => true, 'permohonan' => $permohonan, 'jenis_layanan' => $jenis_layanan])
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('pelayanan-jasa.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 text-white bg-gray-600 dark:bg-gray-700 rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition font-semibold">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
