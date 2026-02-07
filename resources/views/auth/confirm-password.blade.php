@extends('layouts.main')

@section('content')
<x-guest-layout>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Konfirmasi Password</h2>
        <p class="text-gray-600 dark:text-gray-400 text-sm flex items-center gap-2">
            <i class="fas fa-shield-alt text-yellow-500"></i>
            Ini adalah area aman. Silakan konfirmasi password Anda untuk melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-lock text-gray-500 dark:text-gray-400 mr-2"></i>Password
            </label>
            <x-text-input 
                id="password" 
                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <i class="fas fa-check-circle mr-2"></i>Konfirmasi
        </button>
    </form>
</x-guest-layout>
@endsection