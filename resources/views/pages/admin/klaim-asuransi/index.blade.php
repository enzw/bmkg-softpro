@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Klaim Asuransi
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Kelola semua klaim asuransi di sini.
                            </p>
                        </div>
                        <a href="{{ route('admin.klaim-asuransi.create') }}"
                            class="px-4 py-3 text-white bg-green-600 rounded hover:bg-green-500 transition">
                            + Klaim Asuransi
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @include('components.table-klaim-asuransi-admin', ['permohonan' => $permohonan])
                </div>
@endsection