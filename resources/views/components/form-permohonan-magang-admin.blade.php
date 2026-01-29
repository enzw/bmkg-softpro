<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}
</style>

<div class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

    <h2 class="flex items-center mb-4 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ $is_edit ? 'Edit' : 'Buat' }} Permohonan Pelayanan Jasa
    </h2>

    @php
    $permohonan = $permohonan ?? null;
    $jenis_layanan = $is_edit ? ($permohonan->jenis_layanan ?? old('jenis_layanan', '')) : old('jenis_layanan', '');
    @endphp

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

    <form action="{{ $is_edit ? url('admin/pelayanan-jasa/' . $permohonan->id) : route('admin.pelayanan-jasa.store') }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data" id="form-layanan">
        @csrf
        @if ($is_edit)
        @method('put')
        @endif

        <div>
            <x-input-label for="jenis_layanan">Jenis Layanan</x-input-label>
            <select name="jenis_layanan" id="jenis_layanan"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                onchange="updateFormFields()">
                <option value="">Pilih layanan...</option>
                <option value="Magang" @selected($jenis_layanan == 'Magang')>Magang</option>
                <option value="Layanan Klaim Asuransi" @selected($jenis_layanan == 'Layanan Klaim Asuransi')>Layanan Klaim Asuransi</option>
                <option value="Layanan Data" @selected($jenis_layanan == 'Layanan Data')>Layanan Data</option>
                <option value="Layanan Pemetaan" @selected($jenis_layanan == 'Layanan Pemetaan')>Layanan Pemetaan</option>
                <option value="Layanan Survey" @selected($jenis_layanan == 'Layanan Survey')>Layanan Survey</option>
                <option value="Layanan Konsultasi" @selected($jenis_layanan == 'Layanan Konsultasi')>Layanan Konsultasi</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_layanan')" class="mt-2" />
        </div>

        <!-- MAGANG Fields -->
        <div id="magang-fields" style="display: {{ $jenis_layanan == 'Magang' ? 'block' : 'none' }}">
            <div>
                <x-input-label for="nama_lengkap">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="$is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="no_whatsapp">No WhatsApp</x-input-label>
                <x-text-input id="no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                    :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp')" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email">Email</x-input-label>
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" 
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
        <div id="asuransi-fields" style="display: {{ $jenis_layanan == 'Asuransi' ? 'block' : 'none' }}">
            <div>
                <x-input-label for="perusahaan">Nama Instansi</x-input-label>
                <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" 
                    :value="$is_edit ? old('perusahaan', $permohonan->perusahaan ?? '') : old('perusahaan')" />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="kejadian">Jenis Kunjungan</x-input-label>
                <x-text-input id="kejadian" class="block w-full mt-1" type="text" name="kejadian" 
                    :value="$is_edit ? old('kejadian', $permohonan->kejadian ?? '') : old('kejadian')" />
                <x-input-error :messages="$errors->get('kejadian')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="tanggal_asuransi">Tanggal Kunjungan</x-input-label>
                <x-text-input id="tanggal_asuransi" class="block w-full mt-1" type="date" name="tanggal" 
                    :value="$is_edit ? old('tanggal', $permohonan->tanggal ?? '') : old('tanggal')" />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>
        </div>

        <!-- COMMON Fields (Data, Pemetaan, Survey, Konsultasi) -->
        <div id="common-fields" style="display: {{ in_array($jenis_layanan, ['Data', 'Pemetaan', 'Survey', 'Konsultasi']) ? 'block' : 'none' }}">
            <div>
                <x-input-label for="nama_lengkap">Nama Lengkap</x-input-label>
                <x-text-input id="nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                    :value="$is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap')" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_telepon">No Telepon</x-input-label>
                <x-text-input id="no_telepon" class="block w-full mt-1" type="text" name="no_telepon" 
                    :value="$is_edit ? old('no_telepon', $permohonan->no_telepon ?? '') : old('no_telepon')" />
                <x-input-error :messages="$errors->get('no_telepon')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email">Email</x-input-label>
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" 
                    :value="$is_edit ? old('email', $permohonan->email ?? '') : old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="deskripsi">Deskripsi Kebutuhan</x-input-label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('deskripsi', $permohonan->deskripsi ?? '') : old('deskripsi') }}</textarea>
                <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
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
function updateFormFields() {
    const jenis_layanan = document.getElementById('jenis_layanan').value;
    const magangFields = document.getElementById('magang-fields');
    const asuransiFields = document.getElementById('asuransi-fields');
    const commonFields = document.getElementById('common-fields');
    
    magangFields.style.display = jenis_layanan === 'Magang' ? 'block' : 'none';
    asuransiFields.style.display = jenis_layanan === 'Layanan Klaim Asuransi' ? 'block' : 'none';
    commonFields.style.display = ['Layanan Data', 'Layanan Pemetaan', 'Layanan Survey', 'Layanan Konsultasi'].includes(jenis_layanan) ? 'block' : 'none';
}
</script>
</div>