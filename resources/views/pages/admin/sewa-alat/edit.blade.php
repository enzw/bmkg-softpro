@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    Edit Permohonan Sewa Alat
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Ubah data permohonan sewa alat di bawah.
                </p>
            </div>
            <a href="{{ route('admin.sewa-alat.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 text-white bg-red-600 rounded-lg hover:bg-red-700 transition font-semibold">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        @include('components.form-sewa-alat-admin', ['alats' => $alats, 'permohonan' => $permohonan, 'is_edit' => true])
    </div>
@endsection
