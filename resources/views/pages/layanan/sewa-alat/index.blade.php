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
                Jasa Sewa Alat MKG
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Silakan isi formulir untuk melakukan permohonan sewa alat.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                @include('components.form-sewa-alat-permohonan', ['alats' => $alats])
            </div>
            <div class="lg:col-span-2">
                @include('components.table-sewa-alat-permohonan', ['permohonan' => $permohonan])
            </div>
        </div>
    </div>
</div>
@endsection
