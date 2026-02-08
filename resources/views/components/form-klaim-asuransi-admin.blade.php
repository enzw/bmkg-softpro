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

<div class="relative text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 h-max lg:sticky lg:top-12">

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
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form
        action="{{ $is_edit ? route('admin.permohonan-kunjungan.update', ['permohonan_kunjungan' => $permohonan]) : route('admin.permohonan-kunjungan.store') }}"
        method="POST" class="grid max-w-md grid-cols-1 gap-3" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
        @method('put')
        @else
        @method('post')
        @endif

        <div class="flex flex-col gap-3">
            <div>
                <x-input-label for="kejadian">Jenis Kunjungan <span class="text-red-500">*</span></x-input-label>
                <select name="kejadian" id="kejadian"
                    class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                    <option value="">Pilih kunjungan...</option>
                    <option value="Go To School" @selected($is_edit ? old('kejadian', $permohonan->kejadian) == 'Go To School' : old('kejadian') == 'Go To School')>Go To School</option>
                    <option value="Go To BMKG" @selected($is_edit ? old('kejadian', $permohonan->kejadian) == 'Go To BMKG' : old('kejadian') == 'Go To BMKG')>Go To BMKG</option>
                </select>
                <x-input-error :messages="$errors->get('kejadian')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="perusahaan">Nama Instansi <span class="text-red-500">*</span></x-input-label>
                <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" :value="$is_edit ? old('perusahaan', $permohonan->perusahaan) : old('perusahaan')"
                    required />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>


            <div class="relative flex-1">
                <x-input-label for="longitude">Full Name</x-input-label>
                <x-text-input id="longitude" class="block w-full mt-1" type="text" name="longitude" :value="$is_edit ? old('longitude', $permohonan->longitude) : old('longitude')" />
                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
            </div>


            <div>
                <x-input-label for="lokasi">Phone Number</x-input-label>
                <x-text-input id="lokasi" class="block w-full mt-1" type="text" name="lokasi" :value="$is_edit ? old('lokasi', $permohonan->lokasi) : old('lokasi')"
                    required />
                <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
            </div>
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <x-input-label for="latitude">Jumlah Rombongan</x-input-label>
                    <x-text-input id="latitude" class="block w-full mt-1" type="text" name="latitude" :value="$is_edit ? old('latitude', $permohonan->latitude) : old('latitude')" />
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                </div>
                <div>
                    <x-input-label class="w-full" for="tanggal">Rencana Kunjungan</x-input-label>
                    <x-text-input id="tanggal" class="block w-full mt-1" type="date" name="tanggal"
                        :value="$is_edit ? old('tanggal', $permohonan->tanggal) : old('tanggal')" placeholder="Dari tanggal" required />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="surat_permohonan">Surat Permohonan <span class="text-red-500">*</span></x-input-label>
                <input id="surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" required
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" />
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="ktp">KTP <span class="text-red-500">*</span></x-input-label>
                <input id="ktp" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" required
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" />
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>

            @if ($is_edit)
            <div>
                <x-input-label for="status">Status</x-input-label>
                <select name="status" id="status"
                    class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    @php
                    $status = ['Menunggu','Diproses', 'Selesai'];
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