@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Tambah Permohonan Kunjungan
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Isi formulir di bawah untuk membuat permohonan kunjungan teknis baru.
                    </p>
                </div>
                <a href="{{ route('admin.permohonan-kunjungan.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 text-white bg-red-600 rounded-lg hover:bg-red-700 transition font-semibold">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        @include('components.form-permohonan-kunjungan-admin', ['is_edit' => false])
    </div>
@endsection
