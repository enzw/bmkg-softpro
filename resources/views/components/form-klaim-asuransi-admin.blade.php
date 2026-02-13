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

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 h-max lg:sticky lg:top-20">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                <i class="fas fa-clipboard-check text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $is_edit ? 'Edit Klaim Asuransi' : 'Buat Klaim Asuransi' }}</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Lengkapi data dengan akurat dan benar</p>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 flex items-start gap-3">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mt-0.5"></i>
            <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mt-0.5"></i>
            <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ $is_edit ? route('admin.klaim-asuransi.update', $permohonan->id) : route('admin.klaim-asuransi.store') }}"
        method="POST" class="grid max-w-md grid-cols-1 gap-3" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
        @method('put')
        @else
        @method('post')
        @endif

        <div class="flex flex-col gap-3">
            <div>
                <x-input-label for="nama_user">Nama Lengkap <span class="text-red-500">*</span></x-input-label>
                <x-text-input id="nama_user" class="block w-full mt-1" type="text" name="nama_user" :value="$is_edit ? old('nama_user', $permohonan->nama_user) : old('nama_user')"
                    required />
                <x-input-error :messages="$errors->get('nama_user')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_whatsapp">No WhatsApp <span class="text-red-500">*</span></x-input-label>
                <x-text-input id="no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" :value="$is_edit ? old('no_whatsapp', $permohonan->no_whatsapp) : old('no_whatsapp')"
                    required />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="perusahaan">Nama Instansi <span class="text-red-500">*</span></x-input-label>
                <x-text-input id="perusahaan" class="block w-full mt-1" type="text" name="perusahaan" :value="$is_edit ? old('perusahaan', $permohonan->perusahaan) : old('perusahaan')"
                    required />
                <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="lokasi">Lokasi Kejadian <span class="text-red-500">*</span></x-input-label>
                <x-text-input id="lokasi" class="block w-full mt-1" type="text" name="lokasi" :value="$is_edit ? old('lokasi', $permohonan->lokasi) : old('lokasi')"
                    required />
                <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
            </div>

            <div class="flex gap-3">
                <div class="relative flex-1">
                    <x-input-label for="latitude">Latitude <span class="text-gray-500 font-normal text-xs">(Opsional)</span></x-input-label>
                    <x-text-input id="latitude" class="block w-full mt-1" type="number" step="0.00000001" name="latitude" :value="$is_edit ? old('latitude', $permohonan->latitude) : old('latitude')" placeholder="-90 hingga 90" />
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                </div>
                <div class="relative flex-1">
                    <x-input-label for="longitude">Longitude <span class="text-gray-500 font-normal text-xs">(Opsional)</span></x-input-label>
                    <x-text-input id="longitude" class="block w-full mt-1" type="number" step="0.00000001" name="longitude" :value="$is_edit ? old('longitude', $permohonan->longitude) : old('longitude')" placeholder="-180 hingga 180" />
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label class="w-full" for="tanggal">Tanggal Kejadian <span class="text-red-500">*</span></x-input-label>
                <x-text-input id="tanggal" class="block w-full mt-1" type="date" name="tanggal"
                    :value="$is_edit ? old('tanggal', $permohonan->tanggal) : old('tanggal')" required />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="surat_permohonan">Surat Permohonan <span class="text-red-500">*</span></x-input-label>
                <input id="surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" />
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
                @if ($is_edit && $permohonan->surat_permohonan)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <i class="fas fa-file"></i>
                        <a href="{{ route('admin.klaim-asuransi.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" 
                           class="text-green-600 dark:text-green-400 hover:underline" target="_blank">
                            Lihat Surat Permohonan
                        </a>
                    </p>
                @endif
            </div>

            <div>
                <x-input-label for="ktp">KTP <span class="text-red-500">*</span></x-input-label>
                <input id="ktp" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                    class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" />
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
                @if ($is_edit && $permohonan->ktp)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <i class="fas fa-file"></i>
                        <a href="{{ route('admin.klaim-asuransi.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" 
                           class="text-green-600 dark:text-green-400 hover:underline" target="_blank">
                            Lihat KTP
                        </a>
                    </p>
                @endif
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

            <div class="pt-4">
                <button type="submit"
                    class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 dark:from-green-700 dark:to-green-800 dark:hover:from-green-800 dark:hover:to-green-900 text-white font-semibold transition">
                    <i class="fas fa-paper-plane mr-2"></i>{{ $is_edit ? 'Perbarui' : 'Kirim' }} Permohonan
                </button>
            </div>
    </form>
</div>