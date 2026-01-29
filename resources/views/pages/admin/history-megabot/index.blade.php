@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                History Megabot
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Kelola riwayat chat megabot di sini.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @include('components.table-histroy-chat-admin', ['permohonan' => $permohonan, 'layanan' => $layanan])
                </div>
@endsection