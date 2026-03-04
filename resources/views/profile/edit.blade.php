@extends('layouts.main')

@section('title', 'Profile')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12">
        <div class="mt-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation & Back Button -->
            <div class="flex items-center justify-between mb-8">
                <nav class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superuser')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white transition">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('dashboard-pelayanan') }}"
                            class="hover:text-gray-900 dark:hover:text-white transition">
                            <i class="fas fa-briefcase mr-2"></i>Dashboard Pelayanan
                        </a>
                    @endif
                    <span class="mx-3">/</span>
                    <span class="text-gray-900 dark:text-white font-semibold">
                        <i class="fas fa-user mr-2"></i>Profile Saya
                    </span>
                </nav>

                <a href="{{ url()->previous() }}"
                    class="px-5 py-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-100 dark:hover:border-blue-800/50 transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>

            <!-- Header Section -->
            <div class="mb-12">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">
                            Profile Saya
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 text-lg">
                            Kelola informasi profil dan keamanan akun Anda
                        </p>
                    </div>
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('status') === 'profile-updated')
                <div
                    class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-1"></i>
                    <div>
                        <p class="font-semibold text-green-700 dark:text-green-300">Berhasil!</p>
                        <p class="text-sm text-green-600 dark:text-green-400">Profil Anda telah diperbarui.</p>
                    </div>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div
                    class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-1"></i>
                    <div>
                        <p class="font-semibold text-green-700 dark:text-green-300">Berhasil!</p>
                        <p class="text-sm text-green-600 dark:text-green-400">Kata sandi Anda telah diperbarui.</p>
                    </div>
                </div>
            @endif

            <!-- Content Grid -->
            <div class="space-y-6">
                <!-- Update Profile Information -->
                <div
                    class="bg-white dark:bg-gray-800/50 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition duration-200">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <i class="fas fa-user-circle text-blue-600 dark:text-blue-400 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Informasi Profil</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Perbarui nama dan email Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div
                    class="bg-white dark:bg-gray-800/50 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition duration-200">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                <i class="fas fa-lock text-amber-600 dark:text-amber-400 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Keamanan Akun</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Perbarui kata sandi Anda secara
                                    berkala</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div
                    class="bg-white dark:bg-gray-800/50 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition duration-200">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <i class="fas fa-trash-alt text-red-600 dark:text-red-400 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Hapus Akun</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Hapus akun dan semua data Anda
                                    secara permanen</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection