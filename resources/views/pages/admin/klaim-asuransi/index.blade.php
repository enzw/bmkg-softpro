@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Permohonan Kunjungan
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Kelola semua permohonan kunjungan di sini.
                            </p>
                        </div>
                        <a href="{{ route('admin.permohonan-kunjungan.create') }}"
                            class="px-4 py-3 text-white bg-green-600 rounded hover:bg-green-500 transition">
                            + Permohonan
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @include('components.table-klaim-asuransi-admin', ['permohonan' => $permohonan])
                </div>
@endsection