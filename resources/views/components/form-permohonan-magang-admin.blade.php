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

    <form action="{{ $is_edit ? route('admin.pelayanan-jasa.update', ['pelayanan_jasa' => $permohonan]) : route('admin.pelayanan-jasa.store') }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
        @method('put')
        @endif

        <div>
            <x-input-label for="jenis_layanan">Jenis Layanan</x-input-label>
            <select name="jenis_layanan" id="jenis_layanan"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                <option value="">Pilih layanan...</option>
                <option value="Layanan Klaim Asuransi" @selected($is_edit ? old('jenis_layanan', $permohonan->jenis_layanan) == 'Layanan Klaim Asuransi' : false)>Layanan Klaim Asuransi</option>
                <option value="Layanan Data" @selected($is_edit ? old('jenis_layanan', $permohonan->jenis_layanan) == 'Layanan Data' : false)>Layanan Data</option>
                <option value="Layanan Pemetaan" @selected($is_edit ? old('jenis_layanan', $permohonan->jenis_layanan) == 'Layanan Pemetaan' : false)>Layanan Pemetaan</option>
                <option value="Layanan Survey" @selected($is_edit ? old('jenis_layanan', $permohonan->jenis_layanan) == 'Layanan Survey' : false)>Layanan Survey</option>
                <option value="Layanan Konsultasi" @selected($is_edit ? old('jenis_layanan', $permohonan->jenis_layanan) == 'Layanan Konsultasi' : false)>Layanan Konsultasi</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_layanan')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="nama_lengkap">Nama Lengkap</x-input-label>
            <x-text-input id="nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                :value="$is_edit ? old('nama_lengkap', $permohonan->nama_lengkap) : old('nama_lengkap')" required />
            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="no_whatsapp">No WhatsApp</x-input-label>
            <x-text-input id="no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp) : old('no_whatsapp')" required />
            <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email">Email</x-input-label>
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" 
                :value="$is_edit ? old('email', $permohonan->email) : old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="keterangan">Keterangan / Deskripsi Kebutuhan</x-input-label>
            <textarea id="keterangan" name="keterangan" rows="3"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('keterangan', $permohonan->keterangan) : old('keterangan') }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

        @if ($is_edit)
        <div>
            <x-input-label for="status">Status</x-input-label>
            <select name="status" id="status"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                <option value="">Pilih status...</option>
                <option value="Menunggu" @selected(old('status', $permohonan->status) == 'Menunggu')>Menunggu</option>
                <option value="Diterima" @selected(old('status', $permohonan->status) == 'Diterima')>Diterima</option>
                <option value="Ditolak" @selected(old('status', $permohonan->status) == 'Ditolak')>Ditolak</option>
                <option value="Dikirim" @selected(old('status', $permohonan->status) == 'Dikirim')>Dikirim</option>
                <option value="Selesai" @selected(old('status', $permohonan->status) == 'Selesai')>Selesai</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        @endif

        <button type="submit"
            class="px-6 py-2 w-full text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 font-semibold transition duration-200">
            {{ $is_edit ? 'Perbarui' : 'Kirim' }} Permohonan
        </button>
    </form>
</div>            <!-- <div>
                <x-input-label for="prodi">Keterangan</x-input-label>
                <textarea id="prodi"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                    name="prodi" :value="$is_edit ? old('prodi', $permohonan->prodi) : old('prodi')"></textarea>
                <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
            </div> -->

            <!-- <div class="flex gap-3">
                <div class="relative flex-1">
                    <x-input-label class="w-full" for="tanggal_mulai">Tanggal Mulai</x-input-label>
                    <x-text-input id="tanggal_mulai" class="block w-full mt-1" type="date" name="tanggal_mulai"
                        :value="$is_edit ? old('tanggal_mulai', $permohonan->tanggal_mulai) : old('tanggal_mulai')" placeholder="Dari tanggal" required />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>
    
                <div class="relative flex-1">
                    <x-input-label class="w-full" for="tanggal_selesai">Tanggal Selesai</x-input-label>
                    <x-text-input id="tanggal_selesai" class="block w-full mt-1" type="date" name="tanggal_selesai"
                        :value="$is_edit ? old('tanggal_selesai', $permohonan->tanggal_selesai) : old('tanggal_selesai')" placeholder="Hingga tanggal" required />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>
            </div> -->
            @if ($is_edit)
            <div>
                <x-input-label for="status">Status</x-input-label>
                <select name="status" id="status"
                    class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    @php
                    $status = ['Menunggu','Diterima', 'Ditolak', 'Dikirim', 'Selesai'];
                    @endphp
                    <option value="">Pilih status...</option>
                    @foreach ($status as $item)
                    <option value="{{ $item }}" @selected($is_edit ? old('status', $permohonan->status) == $item : old('status') == $item)>{{ $item }}
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>
            @endif

            <button type="submit"
                class="px-3 mt-5 mr-auto leading-10 text-white bg-green-600 rounded w-max hover:bg-green-500">
                {{ $is_edit ? 'Update' : 'Buat' }} Permohonan
            </button>
    </form>
</div>