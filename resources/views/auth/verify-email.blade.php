@extends('layouts.main')

@section('content')
<x-guest-layout>
    <!-- Header -->
    <div class="mb-8">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full mb-4">
                <i class="fas fa-envelope-circle-check text-3xl text-green-600 dark:text-green-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Verifikasi Email</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Terima kasih telah mendaftar! Untuk melanjutkan, silakan verifikasi email Anda dengan mengklik link yang kami kirimkan.
            </p>
        </div>
    </div>

    <!-- Status Messages -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5 flex-shrink-0"></i>
                <div class="text-sm text-green-800 dark:text-green-300">
                    <p class="font-semibold mb-1">Link verifikasi telah dikirim!</p>
                    <p>Silakan periksa email Anda dan klik link untuk mengaktifkan akun.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Resend Verification -->
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <p class="text-sm text-green-800 dark:text-green-300 mb-4">
            <i class="fas fa-info-circle mr-2"></i>Belum menerima email? Klik tombol di bawah untuk mengirim ulang.
        </p>
        <form method="POST" action="{{ route('verification.send') }}" class="inline">
            @csrf
            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-redo mr-2"></i>Kirim Ulang Email Verifikasi
            </button>
        </form>
    </div>

    <!-- Logout -->
    <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
@endsection