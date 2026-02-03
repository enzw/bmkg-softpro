<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}
</style>

<div class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

    <h2 class="flex items-center mb-6 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        <i class="fas fa-clipboard-list mr-3 text-green-600"></i>
        {{ $is_edit ? 'Edit' : 'Buat' }} Permohonan Pelayanan Informasi Geofisika
    </h2>

    @php
    $permohonan = $permohonan ?? null;
    
    // Tentukan jenis_layanan berdasarkan tipe model saat edit
    if ($is_edit && $permohonan) {
        $modelClass = class_basename($permohonan::class);
        if ($modelClass === 'Magang') {
            $jenis_layanan = 'Magang';
        } elseif ($modelClass === 'Asuransi') {
            $jenis_layanan = 'Layanan Klaim Asuransi';
        } elseif ($modelClass === 'LayananData') {
            $jenis_layanan = 'Layanan Data';
        } elseif ($modelClass === 'Pemetaan') {
            $jenis_layanan = 'Layanan Peta Sebaran';
        } elseif ($modelClass === 'PetaSebaran') {
            $jenis_layanan = 'Layanan Peta Sebaran';
        } elseif ($modelClass === 'Survey') {
            $jenis_layanan = 'Layanan Survey';
        } elseif ($modelClass === 'JasaKonsultasi') {
            $jenis_layanan = 'Layanan Konsultasi';
        } else {
            $jenis_layanan = old('jenis_layanan', '');
        }
    } else {
        $jenis_layanan = old('jenis_layanan', '');
    }
    @endphp

    <!-- Tabs Navigation - Hanya tampil di CREATE mode -->
    @if (!$is_edit)
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6 overflow-x-auto">
        <div class="flex gap-0" role="tablist">
            <button 
                role="tab"
                onclick="switchTab('Magang', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Magang') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Magang') true @else false @endif">
                <i class="fas fa-graduation-cap mr-2"></i>Magang
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Klaim Asuransi', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Klaim Asuransi') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Klaim Asuransi') true @else false @endif">
                <i class="fas fa-file-invoice-dollar mr-2"></i>Klaim Asuransi
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Data', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Data') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Data') true @else false @endif">
                <i class="fas fa-database mr-2"></i>Data Geofisika
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Peta Sebaran', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Peta Sebaran') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Peta Sebaran') true @else false @endif">
                <i class="fas fa-map mr-2"></i>Peta Sebaran
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Survey', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Survey') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Survey') true @else false @endif">
                <i class="fas fa-compass mr-2"></i>Survey
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Konsultasi', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Konsultasi') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Konsultasi') true @else false @endif">
                <i class="fas fa-comments mr-2"></i>Konsultasi
            </button>
        </div>
    </div>
    @endif

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

    <form action="{{ $is_edit ? url('admin/pelayanan-jasa/' . $permohonan->id) : route('admin.pelayanan-jasa.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data" id="form-layanan">
        @csrf
        @if ($is_edit)
        @method('put')
        @endif

        <!-- Hidden input for jenis_layanan -->
        <input type="hidden" name="jenis_layanan" id="jenis_layanan" value="{{ $jenis_layanan }}" />

        <!-- MAGANG Fields -->
        <div id="magang-fields" style="display: {{ !$is_edit && ($jenis_layanan == '' || $jenis_layanan == 'Magang') ? 'block' : 'none' }}" class="space-y-4">
            <div>
                <x-input-label for="magang_nama_lengkap">Nama Lengkap</x-input-label>
                <x-text-input id="magang_nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="$is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="magang_no_whatsapp">No WhatsApp</x-input-label>
                <x-text-input id="magang_no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="magang_email">Email</x-input-label>
                <x-text-input id="magang_email" class="block w-full mt-1" type="email" name="email" 
                    :value="$is_edit ? old('email', $permohonan->email ?? '') : old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="universitas">Universitas</x-input-label>
                <x-text-input id="universitas" class="block w-full mt-1" type="text" name="universitas" 
                    :value="$is_edit ? old('universitas', $permohonan->universitas ?? '') : old('universitas')" />
                <x-input-error :messages="$errors->get('universitas')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="fakultas">Fakultas</x-input-label>
                <x-text-input id="fakultas" class="block w-full mt-1" type="text" name="fakultas" 
                    :value="$is_edit ? old('fakultas', $permohonan->fakultas ?? '') : old('fakultas')" />
                <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="prodi">Program Studi</x-input-label>
                <x-text-input id="prodi" class="block w-full mt-1" type="text" name="prodi" 
                    :value="$is_edit ? old('prodi', $permohonan->prodi ?? '') : old('prodi')" />
                <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
            </div>
            <div class="flex gap-3">
                <div class="flex-1">
                    <x-input-label for="tanggal_mulai">Tanggal Mulai</x-input-label>
                    <x-text-input id="tanggal_mulai" class="block w-full mt-1" type="date" name="tanggal_mulai"
                        :value="$is_edit ? old('tanggal_mulai', $permohonan->tanggal_mulai ?? '') : old('tanggal_mulai')" />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>
                <div class="flex-1">
                    <x-input-label for="tanggal_selesai">Tanggal Selesai</x-input-label>
                    <x-text-input id="tanggal_selesai" class="block w-full mt-1" type="date" name="tanggal_selesai"
                        :value="$is_edit ? old('tanggal_selesai', $permohonan->tanggal_selesai ?? '') : old('tanggal_selesai')" />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- ASURANSI Fields -->
        <div id="asuransi-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Klaim Asuransi' ? 'block' : 'none' }}" class="space-y-4">
            <div>
                <x-input-label for="perusahaan">Nama Perusahaan/Instansi</x-input-label>
                <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" 
                    :value="$is_edit ? old('perusahaan', $permohonan->perusahaan ?? '') : old('perusahaan')" />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="tanggal_asuransi">Tanggal Kejadian</x-input-label>
                    <x-text-input id="tanggal_asuransi" class="block w-full mt-1" type="date" name="tanggal" 
                        :value="$is_edit ? old('tanggal', $permohonan->tanggal ?? '') : old('tanggal')" />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="lokasi_asuransi">Lokasi Kejadian</x-input-label>
                    <x-text-input id="lokasi_asuransi" class="block w-full mt-1" type="text" name="lokasi" 
                        :value="$is_edit ? old('lokasi', $permohonan->lokasi ?? '') : old('lokasi')" />
                    <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="latitude_asuransi">Latitude</x-input-label>
                    <x-text-input id="latitude_asuransi" class="block w-full mt-1" type="number" step="0.000001" name="latitude" 
                        :value="$is_edit ? old('latitude', $permohonan->latitude ?? '') : old('latitude')" />
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="longitude_asuransi">Longitude</x-input-label>
                    <x-text-input id="longitude_asuransi" class="block w-full mt-1" type="number" step="0.000001" name="longitude" 
                        :value="$is_edit ? old('longitude', $permohonan->longitude ?? '') : old('longitude')" />
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="kejadian">Deskripsi Kejadian</x-input-label>
                <textarea id="kejadian" name="kejadian" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('kejadian', $permohonan->kejadian ?? '') : old('kejadian') }}</textarea>
                <x-input-error :messages="$errors->get('kejadian')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp_asuransi">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp_asuransi" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>
        </div>

        <!-- DATA GEOFISIKA Fields -->
        <div id="data-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Data' ? 'block' : 'none' }}" class="space-y-4">
            <div>
                <x-input-label for="data_nama_lengkap">Nama Lengkap</x-input-label>
                <x-text-input id="data_nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="$is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="data_no_whatsapp">No WhatsApp</x-input-label>
                <x-text-input id="data_no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="data_email">Email</x-input-label>
                <x-text-input id="data_email" class="block w-full mt-1" type="email" name="email" 
                    :value="$is_edit ? old('email', $permohonan->email ?? '') : old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="data_keterangan">Deskripsi Data Geofisika</x-input-label>
                <textarea id="data_keterangan" name="keterangan" rows="4"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('keterangan', $permohonan->keterangan ?? '') : old('keterangan') }}</textarea>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="data_surat_permohonan">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
                <input id="data_surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
                @if ($is_edit && $permohonan->surat_permohonan)
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">File saat ini: {{ basename($permohonan->surat_permohonan) }}</p>
                @endif
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>
        </div>

        <!-- PETA SEBARAN Fields (combines Pemetaan and PetaSebaran) -->
        <div id="peta-sebaran-fields" style="display: {{ $is_edit && in_array($jenis_layanan, ['Layanan Peta Sebaran', 'Layanan Pemetaan']) ? 'block' : 'none' }}" class="space-y-4">
            <div>
                <x-input-label for="peta_sebaran_perusahaan">Nama Perusahaan/Instansi</x-input-label>
                <x-text-input id="peta_sebaran_perusahaan" class="block w-full mt-1" type="text" name="perusahaan" 
                    :value="$is_edit ? old('perusahaan', $permohonan->perusahaan ?? '') : old('perusahaan')" />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="peta_sebaran_tanggal">Tanggal Kejadian</x-input-label>
                    <x-text-input id="peta_sebaran_tanggal" class="block w-full mt-1" type="date" name="tanggal" 
                        :value="$is_edit ? old('tanggal', $permohonan->tanggal ?? '') : old('tanggal')" />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="peta_sebaran_lokasi">Lokasi Kejadian</x-input-label>
                    <x-text-input id="peta_sebaran_lokasi" class="block w-full mt-1" type="text" name="lokasi" 
                        :value="$is_edit ? old('lokasi', $permohonan->lokasi ?? '') : old('lokasi')" />
                    <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="peta_sebaran_latitude">Latitude</x-input-label>
                    <x-text-input id="peta_sebaran_latitude" class="block w-full mt-1" type="number" step="0.000001" name="latitude" 
                        :value="$is_edit ? old('latitude', $permohonan->latitude ?? '') : old('latitude')" />
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="peta_sebaran_longitude">Longitude</x-input-label>
                    <x-text-input id="peta_sebaran_longitude" class="block w-full mt-1" type="number" step="0.000001" name="longitude" 
                        :value="$is_edit ? old('longitude', $permohonan->longitude ?? '') : old('longitude')" />
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="peta_sebaran_kejadian">Deskripsi Kejadian</x-input-label>
                <textarea id="peta_sebaran_kejadian" name="kejadian" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('kejadian', $permohonan->kejadian ?? '') : old('kejadian') }}</textarea>
                <x-input-error :messages="$errors->get('kejadian')" class="mt-2" />
            </div>
        </div>

        <!-- SURVEY Fields -->
        <div id="survey-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Survey' ? 'block' : 'none' }}" class="space-y-4">
            <div>
                <x-input-label for="survey_nama_lengkap">Nama Lengkap</x-input-label>
                <x-text-input id="survey_nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="$is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="survey_no_whatsapp">No WhatsApp</x-input-label>
                <x-text-input id="survey_no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="survey_email">Email</x-input-label>
                <x-text-input id="survey_email" class="block w-full mt-1" type="email" name="email" 
                    :value="$is_edit ? old('email', $permohonan->email ?? '') : old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="survey_keterangan">Deskripsi Survey</x-input-label>
                <textarea id="survey_keterangan" name="keterangan" rows="4"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('keterangan', $permohonan->keterangan ?? '') : old('keterangan') }}</textarea>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>
        </div>

        <!-- KONSULTASI Fields -->
        <div id="konsultasi-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Konsultasi' ? 'block' : 'none' }}" class="space-y-4">
            <div>
                <x-input-label for="konsultasi_nama_lengkap">Nama Lengkap</x-input-label>
                <x-text-input id="konsultasi_nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="$is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="konsultasi_no_whatsapp">No WhatsApp</x-input-label>
                <x-text-input id="konsultasi_no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="konsultasi_email">Email</x-input-label>
                <x-text-input id="konsultasi_email" class="block w-full mt-1" type="email" name="email" 
                    :value="$is_edit ? old('email', $permohonan->email ?? '') : old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="konsultasi_keterangan">Topik/Deskripsi Konsultasi</x-input-label>
                <textarea id="konsultasi_keterangan" name="keterangan" rows="4"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('keterangan', $permohonan->keterangan ?? '') : old('keterangan') }}</textarea>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>
        </div>

        @if ($is_edit)
        <div>
            <x-input-label for="status">Status</x-input-label>
            <select name="status" id="status"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                <option value="">Pilih status...</option>
                <option value="Menunggu" @selected(old('status', $permohonan->status ?? '') == 'Menunggu')>Menunggu</option>
                <option value="Diproses" @selected(old('status', $permohonan->status ?? '') == 'Diproses')>Diproses</option>
                <option value="Ditolak" @selected(old('status', $permohonan->status ?? '') == 'Ditolak')>Ditolak</option>
                <option value="Selesai" @selected(old('status', $permohonan->status ?? '') == 'Selesai')>Selesai</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        @endif

        <button type="submit"
            class="px-6 py-2 w-full text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 font-semibold transition duration-200">
            {{ $is_edit ? 'Perbarui' : 'Kirim' }} Permohonan
        </button>
    </form>
</div>

<script>
function disableHiddenFields(visibleSectionId) {
    // List semua field section IDs
    const allSections = ['magang-fields', 'asuransi-fields', 'data-fields', 'peta-sebaran-fields', 'survey-fields', 'konsultasi-fields'];
    
    // Disable/enable inputs berdasarkan visibility
    allSections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const inputs = section.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (sectionId === visibleSectionId) {
                    input.disabled = false;
                } else {
                    input.disabled = true;
                }
            });
        }
    });
}

function switchTab(serviceType, buttonElement) {
    // Update hidden input
    document.getElementById('jenis_layanan').value = serviceType;
    
    // Hide all field divs
    document.getElementById('magang-fields').style.display = 'none';
    document.getElementById('asuransi-fields').style.display = 'none';
    document.getElementById('data-fields').style.display = 'none';
    document.getElementById('peta-sebaran-fields').style.display = 'none';
    document.getElementById('survey-fields').style.display = 'none';
    document.getElementById('konsultasi-fields').style.display = 'none';
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active-tab', 'border-green-500', 'text-green-600', 'dark:text-green-400');
        btn.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    });
    
    // Show relevant fields based on service type
    let visibleSectionId;
    if (serviceType === 'Magang') {
        visibleSectionId = 'magang-fields';
        document.getElementById('magang-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Klaim Asuransi') {
        visibleSectionId = 'asuransi-fields';
        document.getElementById('asuransi-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Data') {
        visibleSectionId = 'data-fields';
        document.getElementById('data-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Peta Sebaran') {
        visibleSectionId = 'peta-sebaran-fields';
        document.getElementById('peta-sebaran-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Survey') {
        visibleSectionId = 'survey-fields';
        document.getElementById('survey-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Konsultasi') {
        visibleSectionId = 'konsultasi-fields';
        document.getElementById('konsultasi-fields').style.display = 'block';
    }
    
    // Disable fields yang tidak visible
    if (visibleSectionId) {
        disableHiddenFields(visibleSectionId);
    }
    
    // Activate current tab
    buttonElement.classList.add('active-tab', 'border-green-500', 'text-green-600', 'dark:text-green-400');
    buttonElement.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
}

function updateFormFields() {
    const jenis_layanan = document.getElementById('jenis_layanan').value;
    
    // Hide all field sections
    document.getElementById('magang-fields').style.display = 'none';
    document.getElementById('asuransi-fields').style.display = 'none';
    document.getElementById('data-fields').style.display = 'none';
    document.getElementById('peta-sebaran-fields').style.display = 'none';
    document.getElementById('survey-fields').style.display = 'none';
    document.getElementById('konsultasi-fields').style.display = 'none';
    
    // Show relevant section and enable its fields
    let visibleSectionId;
    if (jenis_layanan === 'Magang') {
        visibleSectionId = 'magang-fields';
        document.getElementById('magang-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Klaim Asuransi') {
        visibleSectionId = 'asuransi-fields';
        document.getElementById('asuransi-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Data') {
        visibleSectionId = 'data-fields';
        document.getElementById('data-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Peta Sebaran') {
        visibleSectionId = 'peta-sebaran-fields';
        document.getElementById('peta-sebaran-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Survey') {
        visibleSectionId = 'survey-fields';
        document.getElementById('survey-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Konsultasi') {
        visibleSectionId = 'konsultasi-fields';
        document.getElementById('konsultasi-fields').style.display = 'block';
    }
    
    // Disable fields yang tidak visible
    if (visibleSectionId) {
        disableHiddenFields(visibleSectionId);
    }
}

// Initialize form fields on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFormFields();
});
</script>

<style>
.active-tab {
    border-bottom-color: rgb(34, 197, 94) !important;
    color: rgb(22, 163, 74) !important;
}
</style>