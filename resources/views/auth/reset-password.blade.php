@extends('layouts.main')

@section('content')
<x-guest-layout>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Reset Password</h2>
        <p class="text-gray-600 dark:text-gray-400 text-sm">Buat password baru yang kuat untuk akun Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                :value="old('email', $request->email)" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-lock text-gray-500 dark:text-gray-400 mr-2"></i>Password Baru
            </label>
            <x-text-input 
                id="password" 
                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimal 8 karakter</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-check-circle text-gray-500 dark:text-gray-400 mr-2"></i>Konfirmasi Password
            </label>
            <x-text-input 
                id="password_confirmation" 
                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full mt-6 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <i class="fas fa-lock-open mr-2"></i>Reset Password
        </button>

        <!-- Back Link -->
        <div class="text-center pt-4">
            <a href="{{ route('login') }}" class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium transition">
                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>
@endsection