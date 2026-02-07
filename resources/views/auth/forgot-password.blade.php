@extends('layouts.main')

@section('content')
<x-guest-layout>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Lupa Password?</h2>
        <p class="text-gray-600 dark:text-gray-400 text-sm">Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan link untuk reset password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200 flex items-start gap-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-envelope text-gray-500 dark:text-gray-400 mr-2"></i>Email Address
            </label>
            <x-text-input 
                id="email" 
                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus
                placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                <i class="fas fa-info-circle mr-1"></i>Link reset password akan dikirim ke email ini
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between gap-4 pt-4">
            <a href="{{ route('login') }}" class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium transition">
                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
            </a>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset
            </button>
        </div>
    </form>
</x-guest-layout>
@endsection
