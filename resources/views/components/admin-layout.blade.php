@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900">
    <div class="container px-4 mx-auto py-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="pt-12 lg:col-span-1">
                <x-admin-sidebar />
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="pt-12 mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $title ?? 'Title' }}
                    </h2>
                    @isset($button)
                    <div class="mb-4">
                        {{ $button }}
                    </div>
                    @endisset
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
