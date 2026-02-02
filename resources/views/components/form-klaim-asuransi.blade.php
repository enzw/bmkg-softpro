<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}
</style>

<div class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

    <h2 class="flex items-center mb-4 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Permohonan Kunjungan
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

    <form action="{{ route('permohonan-kunjungan.store') }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @csrf

        <div>
            <x-input-label for="kejadian">Jenis Kunjungan</x-input-label>
            <select name="kejadian" id="kejadian"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                <option value="">Pilih kunjungan...</option>
                <option value="Go To School" @selected(old('kejadian') == 'Go To School')>Go To School</option>
                <option value="Go To BMKG" @selected(old('kejadian') == 'Go To BMKG')>Go To BMKG</option>
            </select>
            <x-input-error :messages="$errors->get('kejadian')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="perusahaan">Nama Instansi</x-input-label>
            <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" 
                :value="old('perusahaan')" />
            <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="nama_lengkap">Nama Lengkap</x-input-label>
            <x-text-input id="nama_lengkap" class="block w-full mt-1" type="text" name="nama_lengkap"
                :value="old('nama_lengkap')" />
            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="nomor_whatsapp">Nomor Whatsapp</x-input-label>
            <x-text-input id="nomor_whatsapp" class="block w-full mt-1" type="text" name="nomor_whatsapp" 
                :value="old('nomor_whatsapp')" />
            <x-input-error :messages="$errors->get('nomor_whatsapp')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="jumlah_rombongan">Jumlah Rombongan</x-input-label>
            <x-text-input id="jumlah_rombongan" class="block w-full mt-1" type="text" name="jumlah_rombongan"
                :value="old('jumlah_rombongan')" />
            <x-input-error :messages="$errors->get('jumlah_rombongan')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="tanggal">Rencana Kunjungan</x-input-label>
            <x-text-input id="tanggal" class="block w-full mt-1" type="date" name="tanggal"
                :value="old('tanggal')" />
            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
        </div>

        <button type="submit"
            class="px-6 py-2 w-full text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 font-semibold transition duration-200">
            Kirim Permohonan
        </button>
    </form>
</div>
