@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Pelayanan Informasi Geofisika
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Kelola semua permohonan pelayanan informasi geofisika di sini.
                    </p>
                </div>
                <a href="{{ route('admin.pelayanan-jasa.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-plus"></i>
                    Permohonan Baru
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Permohonan</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 ml-13">{{ $permohonan->count() }} permohonan pelayanan</p>
            </div>

            <!-- Content -->
            <div class="p-8">
                @include('components.table-permohonan-magang-admin', ['permohonan' => $permohonan, 'showAllServices' => true])
            </div>
        </div>
    </div>
@endsection
