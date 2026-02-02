<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}
</style>

<div class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

    <h2 class="flex items-center mb-6 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        <i class="fas fa-clipboard-list mr-3 text-green-600"></i>
        Permohonan Pelayanan Jasa
    </h2>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6 overflow-x-auto">
        <div class="flex gap-0" role="tablist">
            <button 
                role="tab"
                onclick="switchTab('magang', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition active-tab"
                aria-selected="true">
                <i class="fas fa-graduation-cap mr-2"></i>Magang
            </button>
            <button 
                role="tab"
                onclick="switchTab('asuransi', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-file-invoice-dollar mr-2"></i>Klaim Asuransi
            </button>
            <button 
                role="tab"
                onclick="switchTab('data', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-database mr-2"></i>Data Geofisika
            </button>
            <button 
                role="tab"
                onclick="switchTab('pemetaan', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-map mr-2"></i>Peta Sebaran
            </button>
            <button 
                role="tab"
                onclick="switchTab('survey', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-compass mr-2"></i>Survey
            </button>
            <button 
                role="tab"
                onclick="switchTab('konsultasi', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                <i class="fas fa-comments mr-2"></i>Konsultasi
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="px-4 py-2 mb-4 text-green-900 bg-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="px-4 py-2 mb-4 text-red-900 bg-red-200 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="px-4 py-2 mb-4 text-red-900 bg-red-200 rounded shadow">
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
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Program Magang di BMKG
            </h3>
            <p class="text-sm text-blue-800 dark:text-blue-200">
                Bergabunglah dengan program magang kami untuk mendapatkan pengalaman praktis di bidang meteorologi, klimatologi, dan geofisika.
            </p>
        </div>
        
        <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_layanan" value="Magang">

            <div>
                <x-input-label for="nama_lengkap_magang">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap_magang" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp_magang">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp_magang" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email_magang">Email</x-input-label>
                <x-text-input id="email_magang" class="block w-full mt-1" type="email" name="email" 
                    :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="universitas">Universitas</x-input-label>
                <x-text-input id="universitas" class="block w-full mt-1" type="text" name="universitas" 
                    :value="old('universitas')" required />
                <x-input-error :messages="$errors->get('universitas')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="fakultas">Fakultas</x-input-label>
                <x-text-input id="fakultas" class="block w-full mt-1" type="text" name="fakultas" 
                    :value="old('fakultas')" />
                <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="prodi">Program Studi</x-input-label>
                <x-text-input id="prodi" class="block w-full mt-1" type="text" name="prodi" 
                    :value="old('prodi')" />
                <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="tanggal_mulai">Tanggal Mulai</x-input-label>
                    <x-text-input id="tanggal_mulai" class="block w-full mt-1" type="date" name="tanggal_mulai"
                        :value="old('tanggal_mulai')" />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tanggal_selesai">Tanggal Selesai</x-input-label>
                    <x-text-input id="tanggal_selesai" class="block w-full mt-1" type="date" name="tanggal_selesai"
                        :value="old('tanggal_selesai')" />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="surat_permohonan_magang">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
                <input id="surat_permohonan_magang" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan Magang
            </button>
        </form>
    </div>

    <!-- Klaim Asuransi Tab -->
    <div id="asuransi-content" class="tab-content hidden">
        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-orange-900 dark:text-orange-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Klaim Asuransi untuk Bencana Alam
            </h3>
            <p class="text-sm text-orange-800 dark:text-orange-200">
                Ajukan permohonan informasi geofisika untuk keperluan klaim asuransi bencana alam.
            </p>
        </div>
        
        <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_layanan" value="Layanan Klaim Asuransi">

            <div>
                <x-input-label for="perusahaan">Nama Perusahaan/Instansi</x-input-label>
                <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" 
                    :value="old('perusahaan')" />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="tanggal_asuransi">Tanggal Kejadian</x-input-label>
                    <x-text-input id="tanggal_asuransi" class="block w-full mt-1" type="date" name="tanggal" 
                        :value="old('tanggal')" />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="lokasi_asuransi">Lokasi Kejadian</x-input-label>
                    <x-text-input id="lokasi_asuransi" class="block w-full mt-1" type="text" name="lokasi" 
                        :value="old('lokasi')" />
                    <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="latitude_asuransi">Latitude</x-input-label>
                    <x-text-input id="latitude_asuransi" class="block w-full mt-1" type="number" step="0.000001" name="latitude" 
                        :value="old('latitude')" />
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="longitude_asuransi">Longitude</x-input-label>
                    <x-text-input id="longitude_asuransi" class="block w-full mt-1" type="number" step="0.000001" name="longitude" 
                        :value="old('longitude')" />
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="kejadian">Deskripsi Kejadian</x-input-label>
                <textarea id="kejadian" name="kejadian" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('kejadian') }}</textarea>
                <x-input-error :messages="$errors->get('kejadian')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="surat_permohonan_asuransi">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
                <input id="surat_permohonan_asuransi" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan Klaim
            </button>
        </form>
    </div>

    <!-- Data Geofisika Tab -->
    <div id="data-content" class="tab-content hidden">
        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-purple-900 dark:text-purple-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Permintaan Data Geofisika
            </h3>
            <p class="text-sm text-purple-800 dark:text-purple-200">
                Dapatkan data geofisika dari BMKG untuk keperluan penelitian, analisis, atau keperluan lainnya.
            </p>
        </div>
        
        <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_layanan" value="Layanan Data">

            <div>
                <x-input-label for="nama_lengkap_data">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap_data" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp_data">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp_data" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email_data">Email</x-input-label>
                <x-text-input id="email_data" class="block w-full mt-1" type="email" name="email" 
                    :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan_data">Deskripsi Data yang Dibutuhkan</x-input-label>
                <textarea id="keterangan_data" name="keterangan" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('keterangan') }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jelaskan jenis data yang Anda butuhkan, periode, lokasi, dan tujuan penggunaan</p>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="surat_permohonan_data">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
                <input id="surat_permohonan_data" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan Data
            </button>
        </form>
    </div>

    <!-- Peta Sebaran Tab -->
    <div id="peta-sebaran-content" class="tab-content hidden">
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-green-900 dark:text-green-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Layanan Peta Sebaran Geofisika
            </h3>
            <p class="text-sm text-green-800 dark:text-green-200">
                Dapatkan jasa peta sebaran geofisika dari tim profesional BMKG.
            </p>
        </div>
        
        <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_layanan" value="Layanan Peta Sebaran">

            <div>
                <x-input-label for="nama_lengkap_pemetaan">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap_pemetaan" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp_pemetaan">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp_pemetaan" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email_pemetaan">Email</x-input-label>
                <x-text-input id="email_pemetaan" class="block w-full mt-1" type="email" name="email" 
                    :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan_pemetaan">Deskripsi Kebutuhan Pemetaan</x-input-label>
                <textarea id="keterangan_pemetaan" name="keterangan" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('keterangan') }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jelaskan area yang perlu dipetakan dan detail teknis yang diperlukan</p>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="surat_permohonan_pemetaan">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
                <input id="surat_permohonan_pemetaan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan Peta Sebaran
            </button>
        </form>
    </div>

    <!-- Survey Tab -->
    <div id="survey-content" class="tab-content hidden">
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-red-900 dark:text-red-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Layanan Survey Geofisika
            </h3>
            <p class="text-sm text-red-800 dark:text-red-200">
                Dapatkan jasa survey geofisika lapangan dari tim ahli BMKG.
            </p>
        </div>
        
        <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_layanan" value="Layanan Survey">

            <div>
                <x-input-label for="nama_lengkap_survey">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap_survey" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp_survey">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp_survey" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email_survey">Email</x-input-label>
                <x-text-input id="email_survey" class="block w-full mt-1" type="email" name="email" 
                    :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan_survey">Deskripsi Survey yang Dibutuhkan</x-input-label>
                <textarea id="keterangan_survey" name="keterangan" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('keterangan') }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jelaskan lokasi survey, durasi, dan tujuan survey</p>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="surat_permohonan_survey">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
                <input id="surat_permohonan_survey" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan Survey
            </button>
        </form>
    </div>

    <!-- Konsultasi Tab -->
    <div id="konsultasi-content" class="tab-content hidden">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Konsultasi Teknis Geofisika
            </h3>
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                Konsultasikan kebutuhan geofisika Anda dengan ahli dari BMKG.
            </p>
        </div>
        
        <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_layanan" value="Layanan Konsultasi">

            <div>
                <x-input-label for="nama_lengkap_konsultasi">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap_konsultasi" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp_konsultasi">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp_konsultasi" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email_konsultasi">Email</x-input-label>
                <x-text-input id="email_konsultasi" class="block w-full mt-1" type="email" name="email" 
                    :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan_konsultasi">Topik dan Detail Konsultasi</x-input-label>
                <textarea id="keterangan_konsultasi" name="keterangan" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('keterangan') }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jelaskan topik konsultasi dan detail yang ingin Anda diskusikan</p>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="surat_permohonan_konsultasi">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
                <input id="surat_permohonan_konsultasi" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan Konsultasi
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
</script>
