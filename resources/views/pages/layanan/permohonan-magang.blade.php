@extends('layouts.main')

@section('title', 'Pelayanan Informasi Geofisika')

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
                <span class="text-sm text-gray-600 dark:text-gray-400">Pelayanan Informasi Geofisika</span>
            </div>
            <div class="mb-2">
                <h1 class="text-5xl font-bold text-gray-900 dark:text-white">Pelayanan Informasi Geofisika</h1>
            </div>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl">
                Ajukan permohonan berbagai layanan informasi geofisika termasuk magang, klaim asuransi, data, survey, dan konsultasi.
            </p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-1">
                @include('components.form-pelayanan-jasa-tabs')
            </div>

            <!-- Table Section -->
            <div class="lg:col-span-2">
                @include('components.table-permohonan-magang', ['permohonan' => $permohonan])
            </div>
        </div>
    </div>
</div>
@endsection
