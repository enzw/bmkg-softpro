<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}
</style>

<div
    class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

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

    <form action="{{ route('pelayanan-jasa.store') }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @csrf

        <div>
            <x-input-label for="jenis_layanan">Jenis Layanan</x-input-label>
            <select name="jenis_layanan" id="jenis_layanan"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                <option value="">Pilih layanan...</option>
                <option value="Layanan Klaim Asuransi">Layanan Klaim Asuransi</option>
                <option value="Layanan Data">Layanan Data</option>
                <option value="Layanan Pemetaan">Layanan Pemetaan</option>
                <option value="Layanan Survey">Layanan Survey</option>
                <option value="Layanan Konsultasi">Layanan Konsultasi</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_layanan')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="nama_lengkap">Nama Lengkap</x-input-label>
            <x-text-input id="nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap" 
                :value="old('nama_lengkap')" required />
            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="no_whatsapp">No WhatsApp</x-input-label>
            <x-text-input id="no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" 
                :value="old('no_whatsapp')" required />
            <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email">Email</x-input-label>
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" 
                :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="keterangan">Keterangan / Deskripsi Kebutuhan</x-input-label>
            <textarea id="keterangan" name="keterangan" rows="3"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('keterangan') }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

        <button type="submit"
            class="px-6 py-2 w-full text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 font-semibold transition duration-200">
            Kirim Permohonan
        </button>
    </form>
</div>
