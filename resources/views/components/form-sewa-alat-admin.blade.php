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
                <i class="fas fa-dolly text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $is_edit ? 'Edit Permohonan' : 'Buat Permohonan' }}</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Isi data dengan lengkap dan benar</p>
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
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50">
            <ul class="space-y-1 text-sm text-red-700 dark:text-red-300">
                @foreach ($errors->all() as $error)
                    <li class="flex items-start gap-2">
                        <span class="text-red-600 dark:text-red-400 mt-0.5">•</span>
                        <span>{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $is_edit ? route('admin.sewa-alat.update', $permohonan->id) : route('admin.sewa-alat.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
            @method('put')
        @endif

        <!-- Row 1: Nama and No WhatsApp -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input id="nama" type="text" name="nama" placeholder="Masukkan nama lengkap" value="{{ $is_edit ? old('nama', $permohonan->nama) : old('nama') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div>
                <label for="no_whatsapp" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <input id="no_whatsapp" type="tel" name="no_whatsapp" placeholder="62..." value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp) : old('no_whatsapp') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>
        </div>

        <!-- Row 2: Alat and Quantity -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="alat_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Pilih Alat <span class="text-red-500">*</span>
                </label>
                <select name="alat_id" id="alat_id" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition">
                    <option value="">Pilih alat...</option>
                    @foreach ($alats as $alat)
                        <option value="{{ $alat->id }}" @selected($is_edit ? old('alat_id', $permohonan->alat->id) == $alat->id : old('alat_id') == $alat->id)>
                            {{ $alat->nama }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('alat_id')" class="mt-2" />
            </div>

            <div>
                <label for="banyak_unit" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Jumlah Unit <span class="text-red-500">*</span>
                </label>
                <input id="banyak_unit" type="number" name="banyak_unit" value="{{ $is_edit ? old('banyak_unit', $permohonan->banyak_unit) : old('banyak_unit', 1) }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('banyak_unit')" class="mt-2" />
            </div>
        </div>

        <!-- Row 3: Tanggal Mulai and Tanggal Berakhir -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="sewa_mulai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input id="sewa_mulai" type="date" name="sewa_mulai" value="{{ $is_edit ? ($permohonan->sewa_mulai ? $permohonan->sewa_mulai->format('Y-m-d') : '') : old('sewa_mulai') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('sewa_mulai')" class="mt-2" />
            </div>

            <div>
                <label for="sewa_berakhir" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Tanggal Berakhir <span class="text-red-500">*</span>
                </label>
                <input id="sewa_berakhir" type="date" name="sewa_berakhir" value="{{ $is_edit ? ($permohonan->sewa_berakhir ? $permohonan->sewa_berakhir->format('Y-m-d') : '') : old('sewa_berakhir') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('sewa_berakhir')" class="mt-2" />
            </div>
        </div>

        @if ($is_edit)
        <!-- Status (Admin Only) -->
        <div>
            <label for="status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Status <span class="text-red-500">*</span>
            </label>
            <select name="status" id="status" required
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition">
                @php
                    $statusOptions = [
                        'Belum Lunas' => 'Menunggu (Belum Lunas)',
                        'Siap Diambil' => 'Diproses (Siap Diambil)',
                        'Dibawa' => 'Diproses (Dibawa)',
                        'Dikembalikan' => 'Selesai (Dikembalikan)',
                    ];
                @endphp
                @foreach ($statusOptions as $dbValue => $displayLabel)
                    <option value="{{ $dbValue }}" @selected(old('status', $is_edit ? $permohonan->status : '') == $dbValue)>
                        {{ $displayLabel }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        @endif

        <!-- Description -->
        <div>
            <label for="keterangan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Keterangan
            </label>
            <textarea id="keterangan" name="keterangan" rows="4" placeholder="Jelaskan kebutuhan sewa alat..."
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none">{{ $is_edit ? $permohonan->keterangan : old('keterangan') }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

        <!-- File Upload -->
        <div>
            <label for="surat_permohonan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Surat Permohonan <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input id="surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
            </p>
            @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('admin.sewa-alat.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" 
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($permohonan->surat_permohonan) }}
                    </a>
                </div>
            @endif
            <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
        </div>

        <!-- KTP Upload -->
        <div>
            <label for="ktp" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                KTP <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input id="ktp" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB
            </p>
            @if ($is_edit && $permohonan && $permohonan->ktp)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('admin.sewa-alat.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" 
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($permohonan->ktp) }}
                    </a>
                </div>
            @endif
            <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
            <i class="fas fa-paper-plane mr-2"></i>{{ $is_edit ? 'Update' : 'Kirim' }} Permohonan
        </button>
    </form>
</div>
