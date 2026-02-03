<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}
</style>

<div class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

    <h2 class="flex items-center mb-4 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ $is_edit ? 'Edit' : 'Buat' }} Permohonan Pemetaan
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

    <form action="{{ $is_edit ? route('admin.pemetaan.update', ['pemetaan' => $permohonan]) : route('admin.pemetaan.store') }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
        @method('put')
        @endif

        <div>
            <x-input-label for="perusahaan">Nama Perusahaan/Instansi</x-input-label>
            <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" 
                :value="$is_edit ? old('perusahaan', $permohonan->perusahaan) : old('perusahaan')" required />
            <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <x-input-label for="tanggal">Tanggal Kejadian</x-input-label>
                <x-text-input id="tanggal" class="block w-full mt-1" type="date" name="tanggal" 
                    :value="$is_edit ? old('tanggal', $permohonan->tanggal) : old('tanggal')" required />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="lokasi">Lokasi Kejadian</x-input-label>
                <x-text-input id="lokasi" class="block w-full mt-1" type="text" name="lokasi" 
                    :value="$is_edit ? old('lokasi', $permohonan->lokasi) : old('lokasi')" required />
                <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <x-input-label for="latitude">Latitude</x-input-label>
                <x-text-input id="latitude" class="block w-full mt-1" type="number" step="0.000001" name="latitude" 
                    :value="$is_edit ? old('latitude', $permohonan->latitude) : old('latitude')" required />
                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="longitude">Longitude</x-input-label>
                <x-text-input id="longitude" class="block w-full mt-1" type="number" step="0.000001" name="longitude" 
                    :value="$is_edit ? old('longitude', $permohonan->longitude) : old('longitude')" required />
                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="kejadian">Deskripsi Kejadian</x-input-label>
            <textarea id="kejadian" name="kejadian" rows="3" required
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? old('kejadian', $permohonan->kejadian) : old('kejadian') }}</textarea>
            <x-input-error :messages="$errors->get('kejadian')" class="mt-2" />
        </div>

        @if ($is_edit)
        <div>
            <x-input-label for="status">Status</x-input-label>
            <select name="status" id="status"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                <option value="">Pilih status...</option>
                <option value="Menunggu" @selected(old('status', $permohonan->status) == 'Menunggu')>Menunggu</option>
                <option value="Diproses" @selected(old('status', $permohonan->status) == 'Diproses')>Diproses</option>
                <option value="Selesai" @selected(old('status', $permohonan->status) == 'Selesai')>Selesai</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        @endif

        <div>
            <x-input-label for="surat_permohonan">Surat Permohonan (PDF, JPG, PNG) - Opsional</x-input-label>
            <input id="surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
            @if ($is_edit && $permohonan->surat_permohonan)
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">File saat ini: {{ basename($permohonan->surat_permohonan) }}</p>
            @endif
            <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
        </div>

        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-500 transition">
            {{ $is_edit ? 'Update' : 'Buat' }} Permohonan
        </button>
    </form>
</div>
