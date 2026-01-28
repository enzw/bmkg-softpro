@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Tambah Permohonan Pelayanan Jasa
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Isi formulir di bawah untuk membuat permohonan pelayanan jasa baru.
                            </p>
                        </div>
                        <a href="{{ route('admin.pelayanan-jasa.index') }}"
                            class="px-4 py-3 text-white bg-red-600 rounded hover:bg-red-500 transition">
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @include('components.form-permohonan-magang-admin', ['is_edit' => false])
                </div>
@endsection