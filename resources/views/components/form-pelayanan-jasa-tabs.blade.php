@php
    $is_edit = $is_edit ?? false;
    $permohonan = $permohonan ?? null;
@endphp

<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0) brightness(1);
    cursor: pointer;
}

@media (prefers-color-scheme: dark) {
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1) brightness(2);
    }
}
</style>

<div class="relative h-max p-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800 lg:sticky lg:top-20">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                <i class="fas fa-list text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Permohonan Pelayanan Jasa</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 ml-13">Pilih jenis layanan yang Anda butuhkan dan isi formulir dengan lengkap</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 overflow-x-auto">
        <div class="flex gap-2 border-b border-gray-100 dark:border-gray-700" role="tablist">
            <button 
                role="tab"
                onclick="switchTab('magang', this)"
                class="tab-button whitespace-nowrap px-5 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition active-tab"
                aria-selected="true">
                <i class="fas fa-graduation-cap mr-2"></i>Magang
            </button>
            <button 
                role="tab"
                onclick="switchTab('asuransi', this)"
                class="tab-button whitespace-nowrap px-5 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-file-invoice-dollar mr-2"></i>Klaim
            </button>
            <button 
                role="tab"
                onclick="switchTab('data', this)"
                class="tab-button whitespace-nowrap px-5 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-database mr-2"></i>Data
            </button>
            <button 
                role="tab"
                onclick="switchTab('survey', this)"
                class="tab-button whitespace-nowrap px-5 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-compass mr-2"></i>Survey
            </button>
            <button 
                role="tab"
                onclick="switchTab('konsultasi', this)"
                class="tab-button whitespace-nowrap px-5 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-comments mr-2"></i>Konsultasi
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="px-5 py-3 mb-5 rounded-lg bg-green-50 text-green-800 border border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-700">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="px-5 py-3 mb-5 rounded-lg bg-red-50 text-red-800 border border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-700">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="px-5 py-3 mb-5 rounded-lg bg-red-50 text-red-800 border border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tab Content -->
    <!-- Magang Tab -->
    <div id="magang-content" class="tab-content">
        <div class="mb-6 p-5 rounded-xl bg-blue-50/50 border border-blue-200/60 dark:bg-blue-900/10 dark:border-blue-700/40">
            <h3 class="flex items-center font-semibold text-blue-900 dark:text-blue-100 mb-2">
                <i class="fas fa-info-circle mr-2 text-lg"></i>Program Magang di BMKG
            </h3>
            <p class="text-sm text-blue-800 dark:text-blue-200">
                Bergabunglah dengan program magang kami untuk mendapatkan pengalaman praktis di bidang meteorologi, klimatologi, dan geofisika.
            </p>
        </div>
        
        <form action="{{ isset($permohonan) && $is_edit ? route('pelayanan-jasa.update', $permohonan->id) : route('pelayanan-jasa.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @if(isset($permohonan) && $is_edit)
                @method('PUT')
            @endif
            <input type="hidden" name="jenis_layanan" value="{{ $jenis_layanan ?? 'Magang' }}">

            <div>
                <label for="nama_lengkap_magang" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input id="nama_lengkap_magang" type="text" name="nama_lengkap" :value="old('nama_lengkap', Auth::user()->name ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Masukkan nama lengkap Anda" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <label for="no_whatsapp_magang" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    No WhatsApp <span class="text-red-500">*</span>
                </label>
                <input id="no_whatsapp_magang" type="text" name="no_whatsapp" :value="old('no_whatsapp', Auth::user()->telp ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="62..." />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <label for="email_magang" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input id="email_magang" type="email" name="email" :value="old('email', Auth::user()->email ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="universitas" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Universitas <span class="text-red-500">*</span>
                </label>
                <input id="universitas" type="text" name="universitas" :value="old('universitas')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Nama universitas" />
                <x-input-error :messages="$errors->get('universitas')" class="mt-2" />
            </div>

            <div>
                <label for="fakultas" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Fakultas
                </label>
                <input id="fakultas" type="text" name="fakultas" :value="old('fakultas')"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Nama fakultas" />
                <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
            </div>

            <div>
                <label for="prodi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Program Studi
                </label>
                <input id="prodi" type="text" name="prodi" :value="old('prodi')"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Nama program studi" />
                <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_mulai" type="date" name="tanggal_mulai" :value="old('tanggal_mulai')" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_selesai" type="date" name="tanggal_selesai" :value="old('tanggal_selesai')" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>
            </div>

            <div>
                <label for="surat_permohonan_magang" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Surat Permohonan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="surat_permohonan_magang" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <div>
                <label for="kartu_mahasiswa_magang" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Kartu Mahasiswa <span class="text-xs text-gray-500 dark:text-gray-400">(Opsional)</span>
                </label>
                <div class="relative">
                    <input id="kartu_mahasiswa_magang" type="file" name="kartu_mahasiswa" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('kartu_mahasiswa')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan
            </button>
        </form>
    </div>

    <!-- Klaim Asuransi Tab -->
    <div id="asuransi-content" class="tab-content hidden">
        <div class="mb-6 p-5 rounded-xl bg-orange-50/50 border border-orange-200/60 dark:bg-orange-900/10 dark:border-orange-700/40">
            <h3 class="flex items-center font-semibold text-orange-900 dark:text-orange-100 mb-2">
                <i class="fas fa-info-circle mr-2 text-lg"></i>Klaim Asuransi untuk Bencana Alam
            </h3>
            <p class="text-sm text-orange-800 dark:text-orange-200">
                Ajukan permohonan Informasi geofisika untuk keperluan klaim asuransi (kejadian petir dan gempabumi)
            </p>
        </div>
        
        <form action="{{ isset($permohonan) && $is_edit ? route('pelayanan-jasa.update', $permohonan->id) : route('pelayanan-jasa.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @if(isset($permohonan) && $is_edit)
                @method('PUT')
            @endif
            <input type="hidden" name="jenis_layanan" value="Layanan Klaim Asuransi">

            <div>
                <label for="nama_user_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input id="nama_user_asuransi" type="text" name="nama_user" :value="old('nama_user', Auth::user()->name ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Masukkan nama Anda" />
                <x-input-error :messages="$errors->get('nama_user')" class="mt-2" />
            </div>

            <div>
                <label for="no_whatsapp_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    No WhatsApp <span class="text-red-500">*</span>
                </label>
                <input id="no_whatsapp_asuransi" type="text" name="no_whatsapp" :value="old('no_whatsapp', Auth::user()->telp ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="62..." />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <label for="perusahaan_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nama Perusahaan<span class="text-red-500">*</span>
                </label>
                <input id="perusahaan_asuransi" type="text" name="perusahaan" :value="old('perusahaan')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Nama perusahaan" />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>

            <div>
                <label for="lokasi_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Alamat Lokasi Kejadian <span class="text-red-500">*</span>
                </label>
                <input id="lokasi_asuransi" type="text" name="lokasi" :value="old('lokasi')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Alamat lengkap" />
                <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Lintang (Latitude) <span class="text-xs text-gray-500 dark:text-gray-400">(Opsional)</span>
                    </label>
                    <input id="latitude_asuransi" type="number" step="0.00000001" min="-90" max="90" name="latitude" :value="old('latitude')"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="-90 hingga 90" />
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Range: -90 hingga 90</p>
                </div>
                <div>
                    <label for="longitude_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Bujur (Longitude) <span class="text-xs text-gray-500 dark:text-gray-400">(Opsional)</span>
                    </label>
                    <input id="longitude_asuransi" type="number" step="0.00000001" min="-180" max="180" name="longitude" :value="old('longitude')"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="-180 hingga 180" />
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Range: -180 hingga 180</p>
                </div>
            </div>

            <div>
                <label for="tanggal_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Tanggal Kejadian <span class="text-red-500">*</span>
                </label>
                <input id="tanggal_asuransi" type="date" name="tanggal" :value="old('tanggal')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>

            <div>
                <label for="surat_permohonan_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Surat Permohonan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="surat_permohonan_asuransi" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <div>
                <label for="ktp_asuransi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    KTP <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="ktp_asuransi" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan
            </button>
        </form>
    </div>

    <!-- Data Geofisika Tab -->
    <div id="data-content" class="tab-content hidden">
        <div class="mb-6 p-5 rounded-xl bg-purple-50/50 border border-purple-200/60 dark:bg-purple-900/10 dark:border-purple-700/40">
            <h3 class="flex items-center font-semibold text-purple-900 dark:text-purple-100 mb-2">
                <i class="fas fa-info-circle mr-2 text-lg"></i>Permintaan Data Geofisika
            </h3>
            <p class="text-sm text-purple-800 dark:text-purple-200">
                Dapatkan data geofisika dari BMKG untuk keperluan penelitian, analisis, atau keperluan lainnya.
            </p>
        </div>
        
        <form action="{{ isset($permohonan) && $is_edit ? route('pelayanan-jasa.update', $permohonan->id) : route('pelayanan-jasa.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @if(isset($permohonan) && $is_edit)
                @method('PUT')
            @endif
            <input type="hidden" name="jenis_layanan" value="Layanan Data">

            <!-- Data Geofisika Fields -->
            <div id="geofisika-fields" class="space-y-6">
                <div>
                    <label for="nama_lengkap_data" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input id="nama_lengkap_data" type="text" name="nama_lengkap" :value="old('nama_lengkap', Auth::user()->name ?? '')" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Masukkan nama lengkap" />
                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                </div>

                <div>
                    <label for="no_whatsapp_data" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        No WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input id="no_whatsapp_data" type="text" name="no_whatsapp" :value="old('no_whatsapp', Auth::user()->telp ?? '')" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="62..." />
                    <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
                </div>

                <div>
                    <label for="email_data" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input id="email_data" type="email" name="email" :value="old('email', Auth::user()->email ?? '')" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="email@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="keterangan_data" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Deskripsi Data yang Dibutuhkan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="keterangan_data" name="keterangan" rows="4" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none" placeholder="Jelaskan jenis data, periode, lokasi (lintang, bujur), dan tujuan penggunaan">{{ old('keterangan') }}</textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Contoh: Data gempa bumi bulan Januari 2024 untuk area Jakarta lintang 12 bujur 12</p>
                    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                </div>
            </div>

            <!-- Common Fields -->
            <div>
                <label for="surat_permohonan_data" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Surat Permohonan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="surat_permohonan_data" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <div>
                <label for="ktp_data" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    KTP <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="ktp_data" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i><span id="submit-btn-text">Kirim Permohonan</span>
            </button>
        </form>
    </div>

    <!-- Survey Tab -->
    <div id="survey-content" class="tab-content hidden">
        <div class="mb-6 p-5 rounded-xl bg-red-50/50 border border-red-200/60 dark:bg-red-900/10 dark:border-red-700/40">
            <h3 class="flex items-center font-semibold text-red-900 dark:text-red-100 mb-2">
                <i class="fas fa-info-circle mr-2 text-lg"></i>Layanan Survey Geofisika
            </h3>
            <p class="text-sm text-red-800 dark:text-red-200">
                Dapatkan jasa survey geofisika lapangan dari tim ahli BMKG.
            </p>
        </div>
        
        <form action="{{ isset($permohonan) && $is_edit ? route('pelayanan-jasa.update', $permohonan->id) : route('pelayanan-jasa.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @if(isset($permohonan) && $is_edit)
                @method('PUT')
            @endif
            <input type="hidden" name="jenis_layanan" value="Layanan Survey">

            <div>
                <label for="nama_lengkap_survey" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input id="nama_lengkap_survey" type="text" name="nama_lengkap" :value="old('nama_lengkap', Auth::user()->name ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Masukkan nama lengkap Anda" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <label for="no_whatsapp_survey" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    No WhatsApp <span class="text-red-500">*</span>
                </label>
                <input id="no_whatsapp_survey" type="text" name="no_whatsapp" :value="old('no_whatsapp', Auth::user()->telp ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="62..." />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <label for="email_survey" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input id="email_survey" type="email" name="email" :value="old('email', Auth::user()->email ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="keterangan_survey" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Deskripsi Survey yang Dibutuhkan <span class="text-red-500">*</span>
                </label>
                <textarea id="keterangan_survey" name="keterangan" rows="4" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none" placeholder="Jelaskan lokasi survey, durasi, dan tujuan survey">{{ old('keterangan') }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Contoh: Survey gempa mikro selama 2 minggu di daerah Yogyakarta</p>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div>
                <label for="surat_permohonan_survey" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Surat Permohonan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="surat_permohonan_survey" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <div>
                <label for="ktp_survey" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    KTP <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="ktp_survey" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan
            </button>
        </form>
    </div>

    <!-- Konsultasi Tab -->
    <div id="konsultasi-content" class="tab-content hidden">
        <div class="mb-6 p-5 rounded-xl bg-yellow-50/50 border border-yellow-200/60 dark:bg-yellow-900/10 dark:border-yellow-700/40">
            <h3 class="flex items-center font-semibold text-yellow-900 dark:text-yellow-100 mb-2">
                <i class="fas fa-info-circle mr-2 text-lg"></i>Konsultasi Teknis Geofisika
            </h3>
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                Konsultasikan kebutuhan geofisika Anda dengan ahli dari BMKG.
            </p>
        </div>
        
        <form action="{{ isset($permohonan) && $is_edit ? route('pelayanan-jasa.update', $permohonan->id) : route('pelayanan-jasa.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @if(isset($permohonan) && $is_edit)
                @method('PUT')
            @endif
            <input type="hidden" name="jenis_layanan" value="Layanan Konsultasi">

            <div>
                <label for="nama_lengkap_konsultasi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input id="nama_lengkap_konsultasi" type="text" name="nama_lengkap" :value="old('nama_lengkap', Auth::user()->name ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="Masukkan nama lengkap Anda" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <label for="no_whatsapp_konsultasi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    No WhatsApp <span class="text-red-500">*</span>
                </label>
                <input id="no_whatsapp_konsultasi" type="text" name="no_whatsapp" :value="old('no_whatsapp', Auth::user()->telp ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="62..." />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <label for="email_konsultasi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input id="email_konsultasi" type="email" name="email" :value="old('email', Auth::user()->email ?? '')" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="keterangan_konsultasi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Topik dan Detail Konsultasi <span class="text-red-500">*</span>
                </label>
                <textarea id="keterangan_konsultasi" name="keterangan" rows="4" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none" placeholder="Jelaskan topik konsultasi dan detail yang ingin Anda diskusikan">{{ old('keterangan') }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Contoh: Konsultasi tentang interpretasi data seismik untuk survei hidrokarbon</p>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div>
                <label for="surat_permohonan_konsultasi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Surat Permohonan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="surat_permohonan_konsultasi" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <div>
                <label for="ktp_konsultasi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    KTP <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="ktp_konsultasi" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($permohonan) || !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan
            </button>
        </form>
    </div>

</div>

<script>
function switchTab(tabName, buttonElement) {
    // Hide all tab contents
    const allContents = document.querySelectorAll('.tab-content');
    allContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all buttons
    const allButtons = document.querySelectorAll('.tab-button');
    allButtons.forEach(btn => {
        btn.classList.remove('text-green-600', 'dark:text-green-400', 'border-green-600', 'dark:border-green-400');
        btn.classList.add('text-gray-600', 'dark:text-gray-400', 'border-transparent');
    });
    
    // Show selected tab content
    const selectedContent = document.getElementById(tabName + '-content');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    // Set active state on button
    buttonElement.classList.remove('text-gray-600', 'dark:text-gray-400', 'border-transparent');
    buttonElement.classList.add('text-green-600', 'dark:text-green-400', 'border-green-600', 'dark:border-green-400');
    buttonElement.setAttribute('aria-selected', 'true');
}

function toggleServiceType(serviceType) {
    // Peta Sebaran removed - no longer needed
}
</script>
