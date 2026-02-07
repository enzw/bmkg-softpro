@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Tambah Permohonan Jasa Konsultasi
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Isi formulir di bawah untuk membuat permohonan jasa konsultasi baru.
                            </p>
                        </div>
                        <a href="{{ route('admin.jasa-konsultasi.index') }}"
                            class="px-4 py-3 text-white bg-red-600 rounded hover:bg-red-500 transition">
                            Kembali
                        </a>
                    </div>
                </div>
                    @include('components.form-jasa-konsultasi-admin', ['is_edit' => false])
@endsection
