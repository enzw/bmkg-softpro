<style>
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2);
    cursor: pointer;
}
</style>

<div class="relative p-6 overflow-hidden text-gray-900 bg-white shadow-sm dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg h-max lg:sticky lg:top-12">

    <h2 class="flex items-center mb-4 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ $is_edit ? 'Edit Permohonan Sewa Alat' : 'Buat Permohonan Sewa Alat' }}
    </h2>

    {{-- Alert --}}
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

    <form
        action="{{ $is_edit ? route('admin.sewa-alat.update', ['sewa_alat' => $permohonan]) : route('admin.sewa-alat.store') }}"
        method="POST" class="grid grid-cols-1 gap-4" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
        @method('put')
        @endif

        <div class="flex gap-3 flex-wrap">
            <div class="flex-1">
                <x-input-label for="alat_id">Alat</x-input-label>
                <select name="alat_id" id="alat_id"
                    class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    <option value="">Pilih alat...</option>
                    @foreach ($alats as $alat)
                    <option value="{{ $alat->id }}" @selected($is_edit ? old('alat_id', $permohonan->alat->id) == $alat->id : old('alat_id') == $alat->id)>
                        {{ $alat->nama }}
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('alat_id')" class="mt-2" />
            </div>

            <div class="flex-1">
                <x-input-label for="banyak_unit">Banyak unit</x-input-label>
                <x-text-input id="banyak_unit" class="block w-full mt-1" type="number" name="banyak_unit"
                    :value="$is_edit ? old('banyak_unit', $permohonan->banyak_unit) : old('banyak_unit', 1)" required />
                <x-input-error :messages="$errors->get('banyak_unit')" class="mt-2" />
            </div>
        </div>

        <div class="flex gap-3 flex-wrap">
            <div class="flex-1">
                <x-input-label for="sewa_mulai">Dari tanggal</x-input-label>
                <x-text-input id="sewa_mulai" class="block w-full mt-1" type="date" name="sewa_mulai"
                    :value="$is_edit ? old('sewa_mulai', $permohonan->sewa_mulai) : old('sewa_mulai')" required />
                <x-input-error :messages="$errors->get('sewa_mulai')" class="mt-2" />
            </div>

            <div class="flex-1">
                <x-input-label for="sewa_berakhir">Hingga tanggal</x-input-label>
                <x-text-input id="sewa_berakhir" class="block w-full mt-1" type="date" name="sewa_berakhir"
                    :value="$is_edit ? old('sewa_berakhir', $permohonan->sewa_berakhir) : old('sewa_berakhir')" required />
                <x-input-error :messages="$errors->get('sewa_berakhir')" class="mt-2" />
            </div>
        </div>

        @if ($is_edit)
        <div>
            <x-input-label for="status">Status</x-input-label>
            <select name="status" id="status"
                class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                @php
                $status = [
                    'Belum Lunas' => '⏳ Menunggu (Belum Lunas)',
                    'Siap Diambil' => '⚙ Diproses (Alat Siap Diambil)',
                    'Dibawa' => '⚙ Diproses (Alat Dibawa)',
                    'Dikembalikan' => '✓ Selesai (Alat Sudah Dikembalikan)',
                ];
                @endphp
                <option value="">Pilih alat...</option>
                @foreach ($status as $dbValue => $displayLabel)
                <option value="{{ $dbValue }}" @selected($is_edit ? old('status', $permohonan->status) == $dbValue : old('status') == $dbValue)>{{ $displayLabel }}
                </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        @endif

        <div>
            <x-input-label for="keterangan">Keterangan</x-input-label>
            <textarea id="keterangan" name="keterangan" rows="3"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $is_edit ? $permohonan->keterangan : old('keterangan') }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

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

        <button type="submit"
            class="px-6 py-2 w-full text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 font-semibold transition duration-200">
            {{ $is_edit ? 'Update' : 'Kirim' }} Permohonan
        </button>
    </form>
</div>