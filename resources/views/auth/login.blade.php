@extends('layouts.main')

@section('content')
    <x-guest-layout>
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Masuk ke Akun</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Akses layanan publik BMKG Yogyakarta</p>
        </div>

        <!-- Status Messages -->
        <x-auth-session-status class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200" :status="session('status')" />

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200 flex items-start gap-3">
                <i class="fas fa-check-circle flex-shrink-0 mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-800 dark:text-red-200 flex items-start gap-3">
                <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                    autocomplete="username"
                    placeholder="name@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
            </div>

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

            <!-- Remember Me -->
            <div class="block pt-2">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <input 
                        id="remember_me" 
                        type="checkbox"
                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 cursor-pointer"
                        name="remember">
                    <span class="text-sm text-gray-600 dark:text-gray-400 ms-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">
                        Ingat saya
                    </span>
                </label>
            </div>

            <!-- Submit and Forgot Password -->
            <div class="flex items-center justify-between gap-4 pt-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium transition"
                        href="{{ route('password.request') }}">
                        <i class="fas fa-question-circle mr-1"></i>Lupa Password?
                    </a>
                @endif

                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </div>

            <!-- Register Link -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-semibold transition">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </form>
    </x-guest-layout>
@endsection
