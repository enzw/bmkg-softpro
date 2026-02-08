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
        {{ $is_edit ? 'Edit' : 'Buat' }} Permohonan Survey
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

    <form action="{{ $is_edit ? route('admin.survey.update', ['survey' => $permohonan]) : route('admin.survey.store') }}" method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
        @method('put')
        @endif

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
            <x-input-label for="keterangan">Deskripsi Survei yang Dibutuhkan</x-input-label>
            <textarea id="keterangan" name="keterangan" rows="3"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600">{{ $is_edit ? old('keterangan', $permohonan->keterangan) : old('keterangan') }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

        @if ($is_edit)
        <div>
            <x-input-label for="status">Status</x-input-label>
            <select name="status" id="status"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600">
                <option value="">Pilih status...</option>
                <option value="Menunggu" @selected(old('status', $permohonan->status) == 'Menunggu')>Menunggu</option>
                <option value="Diproses" @selected(old('status', $permohonan->status) == 'Diproses')>Diproses</option>
                <option value="Selesai" @selected(old('status', $permohonan->status) == 'Selesai')>Selesai</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        @endif

        <div>
            <x-input-label for="surat_permohonan">Surat Permohonan (PDF) - Opsional</x-input-label>
            <input id="surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal ukuran file: 2MB</p>
            @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('admin.survey.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" 
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($permohonan->surat_permohonan) }}
                    </a>
                </div>
            @endif
            <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="ktp">KTP (PDF) - Wajib</x-input-label>
            <input id="ktp" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" required
                class="block w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600" />
            @if ($is_edit && $permohonan && $permohonan->ktp)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('admin.survey.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" 
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($permohonan->ktp) }}
                    </a>
                </div>
            @endif
            <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
        </div>

        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-500 transition">
            {{ $is_edit ? 'Update' : 'Buat' }} Permohonan
        </button>
    </form>
</div>
