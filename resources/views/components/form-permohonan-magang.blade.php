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

<div class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

    <h2 class="flex items-center mb-4 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Permohonan Pelayanan Jasa
    </h2>

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

    <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data" id="formPelayananJasa">
        @csrf

        <div>
            <x-input-label for="jenis_layanan">Jenis Layanan</x-input-label>
            <select name="jenis_layanan" id="jenis_layanan" onchange="toggleFormFields(this.value)"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                <option value="">Pilih layanan...</option>
                <option value="Magang" @selected(old('jenis_layanan') == 'Magang')>Magang</option>
                <option value="Layanan Klaim Asuransi" @selected(old('jenis_layanan') == 'Layanan Klaim Asuransi')>Layanan Klaim Asuransi</option>
                <option value="Layanan Data" @selected(old('jenis_layanan') == 'Layanan Data')>Layanan Data</option>
                <option value="Layanan Pemetaan" @selected(old('jenis_layanan') == 'Layanan Pemetaan')>Layanan Pemetaan</option>
                <option value="Layanan Survey" @selected(old('jenis_layanan') == 'Layanan Survey')>Layanan Survey</option>
                <option value="Layanan Konsultasi" @selected(old('jenis_layanan') == 'Layanan Konsultasi')>Layanan Konsultasi</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_layanan')" class="mt-2" />
        </div>

        <!-- Magang Fields -->
        <div id="magang-fields" class="hidden space-y-4">
            <div>
                <x-input-label for="nama_lengkap_magang">Nama Lengkap <span class="text-xs text-gray-500">(Opsional - akan diisi dari profil jika kosong)</span></x-input-label>
                <x-text-input id="nama_lengkap_magang" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp_magang">No WhatsApp <span class="text-xs text-gray-500">(Opsional - akan diisi dari profil jika kosong)</span></x-input-label>
                <x-text-input id="no_whatsapp_magang" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email_magang">Email <span class="text-xs text-gray-500">(Opsional - akan diisi dari profil jika kosong)</span></x-input-label>
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
        </div>

        <!-- Layanan Klaim Asuransi Fields -->
        <div id="asuransi-fields" class="hidden space-y-4">
            <div>
                <x-input-label for="perusahaan">Nama Perusahaan/Instansi</x-input-label>
                <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" 
                    :value="old('perusahaan')" />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="tanggal">Tanggal Kejadian</x-input-label>
                    <x-text-input id="tanggal" class="block w-full mt-1" type="date" name="tanggal" 
                        :value="old('tanggal')" />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="lokasi">Lokasi Kejadian</x-input-label>
                    <x-text-input id="lokasi" class="block w-full mt-1" type="text" name="lokasi" 
                        :value="old('lokasi')" />
                    <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="latitude">Latitude</x-input-label>
                    <x-text-input id="latitude" class="block w-full mt-1" type="number" step="0.000001" name="latitude" 
                        :value="old('latitude')" />
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="longitude">Longitude</x-input-label>
                    <x-text-input id="longitude" class="block w-full mt-1" type="number" step="0.000001" name="longitude" 
                        :value="old('longitude')" />
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Layanan Data, Pemetaan, Survey, Konsultasi Fields -->
        <div id="standard-fields" class="hidden space-y-4">
            <div>
                <x-input-label for="nama_lengkap">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email">Email</x-input-label>
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" 
                    :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan">Deskripsi Kebutuhan</x-input-label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600">{{ old('keterangan') }}</textarea>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>
        </div>

        <!-- File Upload (untuk semua jenis layanan) -->
        <div id="file-fields" class="hidden">
            <x-input-label for="surat_permohonan">Surat Permohonan (PDF) <span class="text-red-500">*</span></x-input-label>
            <input id="surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" required
                class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
            <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
        </div>

        <button type="submit" id="submitBtn" disabled
            class="px-6 py-2 w-full text-white bg-gray-400 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-600 font-semibold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
            Kirim Permohonan
        </button>
    </form>
</div>

<script>
function toggleFormFields(value) {
    // Hide all fields first
    document.getElementById('magang-fields').classList.add('hidden');
    document.getElementById('asuransi-fields').classList.add('hidden');
    document.getElementById('standard-fields').classList.add('hidden');
    document.getElementById('file-fields').classList.add('hidden');
    
    // Disable all inputs first
    disableFieldsInContainer('magang-fields');
    disableFieldsInContainer('asuransi-fields');
    disableFieldsInContainer('standard-fields');
    disableFieldsInContainer('file-fields');
    
    // Enable/disable submit button
    const submitBtn = document.getElementById('submitBtn');
    
    if (value === '') {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Pilih layanan terlebih dahulu';
        return;
    }
    
    submitBtn.disabled = false;
    submitBtn.classList.remove('bg-gray-400', 'hover:bg-gray-400', 'dark:bg-gray-600', 'dark:hover:bg-gray-600');
    submitBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'dark:bg-green-700', 'dark:hover:bg-green-800');
    submitBtn.textContent = 'Kirim Permohonan';
    
    // Show appropriate fields based on selection
    if (value === 'Magang') {
        document.getElementById('magang-fields').classList.remove('hidden');
        enableFieldsInContainer('magang-fields');
    } else if (value === 'Layanan Klaim Asuransi') {
        document.getElementById('asuransi-fields').classList.remove('hidden');
        enableFieldsInContainer('asuransi-fields');
    } else if (['Layanan Data', 'Layanan Pemetaan', 'Layanan Survey', 'Layanan Konsultasi'].includes(value)) {
        document.getElementById('standard-fields').classList.remove('hidden');
        enableFieldsInContainer('standard-fields');
    }
    
    document.getElementById('file-fields').classList.remove('hidden');
    enableFieldsInContainer('file-fields');
}

function disableFieldsInContainer(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
        const inputs = container.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.disabled = true;
        });
    }
}

function enableFieldsInContainer(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
        const inputs = container.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.disabled = false;
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const jeniLayanan = document.getElementById('jenis_layanan');
    if (jeniLayanan.value) {
        toggleFormFields(jeniLayanan.value);
    }
});
</script>
