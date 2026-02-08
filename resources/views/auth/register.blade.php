@extends('layouts.main')

@section('content')
<x-guest-layout wide>
    <!-- Header -->
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Daftar Akun Baru</h2>
        <p class="text-gray-600 dark:text-gray-400 text-sm">Isi formulir di bawah untuk membuat akun layanan BMKG Yogyakarta</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0"></i>
                <div>
                    <h3 class="font-semibold text-red-800 dark:text-red-300 mb-2">Ada kesalahan pada formulir:</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-200">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-8">
        @csrf

        <!-- Account Information -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-user-circle text-green-600"></i>
                Informasi Akun
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <x-text-input 
                        id="name" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required
                        placeholder="Nama lengkap Anda" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm" />
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <x-text-input 
                        id="email" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required
                        placeholder="name@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="telp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-phone text-gray-500 mr-1"></i>No. HP / Telepon <span class="text-red-500">*</span>
                    </label>
                    <x-text-input 
                        id="telp" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="text" 
                        name="telp" 
                        required
                        placeholder="62..." />
                    <x-input-error :messages="$errors->get('telp')" class="mt-2 text-sm" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-lock text-gray-500 mr-1"></i>Password <span class="text-red-500">*</span>
                    </label>
                    <x-text-input 
                        id="password" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="password" 
                        name="password" 
                        required
                        placeholder="••••••••" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimal 8 karakter</p>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-check-circle text-gray-500 mr-1"></i>Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <x-text-input 
                        id="password_confirmation" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="password" 
                        name="password_confirmation" 
                        required
                        placeholder="••••••••" />
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700"></div>

        <!-- Identity Information -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-id-card text-green-600"></i>
                Informasi Identitas
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- No. Identitas -->
                <div>
                    <label for="no_identitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        No. Identitas (KTP) <span class="text-red-500">*</span>
                    </label>
                    <x-text-input 
                        id="no_identitas" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="text" 
                        name="no_identitas" 
                        :value="old('no_identitas')" 
                        required
                        placeholder="Nomor KTP atau SIM" />
                    <x-input-error :messages="$errors->get('no_identitas')" class="mt-2 text-sm" />
                </div>

                <!-- NPWP (Optional) -->
                <div>
                    <label for="npwp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        No. NPWP <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <x-text-input 
                        id="npwp" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="text" 
                        name="npwp" 
                        :value="old('npwp')"
                        placeholder="Nomor NPWP" />
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label for="pekerjaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <x-text-input 
                        id="pekerjaan" 
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        type="text" 
                        name="pekerjaan" 
                        :value="old('pekerjaan')" 
                        required
                        placeholder="Bidang pekerjaan Anda" />
                    <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2 text-sm" />
                </div>

                <!-- Pendidikan -->
                <div>
                    <label for="pendidikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pendidikan Terakhir <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="pendidikan" 
                        id="pendidikan"
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                        required>
                        <option value="">-- Pilih Pendidikan --</option>
                        <option value="sd" @selected(old('pendidikan') == 'sd')>SD / Setara</option>
                        <option value="smp" @selected(old('pendidikan') == 'smp')>SMP / Setara</option>
                        <option value="sma" @selected(old('pendidikan') == 'sma')>SMA / Setara</option>
                        <option value="d3" @selected(old('pendidikan') == 'd3')>D3</option>
                        <option value="s1" @selected(old('pendidikan') == 's1')>S1</option>
                        <option value="s2" @selected(old('pendidikan') == 's2')>S2</option>
                        <option value="s3" @selected(old('pendidikan') == 's3')>S3</option>
                    </select>
                    <x-input-error :messages="$errors->get('pendidikan')" class="mt-2 text-sm" />
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt text-gray-500 mr-1"></i>Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="alamat" 
                        id="alamat" 
                        rows="3"
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none"
                        placeholder="Jalan, No., Kelurahan, Kecamatan, Kota, Provinsi"
                        required></textarea>
                    <x-input-error :messages="$errors->get('alamat')" class="mt-2 text-sm" />
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('login') }}" class="text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition">
                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
            </a>

            <button 
                type="submit" 
                class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
            </button>
        </div>
    </form>

</x-guest-layout>
@endsection
