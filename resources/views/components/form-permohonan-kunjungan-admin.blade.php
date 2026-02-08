<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 h-max">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                <i class="fas fa-edit text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Formulir Permohonan</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Lengkapi semua field yang wajib diisi</p>
    </div>

    <!-- Alert Messages -->
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

    <form
        action="{{ $is_edit ? route('admin.permohonan-kunjungan.update', ['permohonan_kunjungan' => $permohonan]) : route('admin.permohonan-kunjungan.store') }}"
        method="POST" class="space-y-5" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
            @method('put')
        @else
            @method('post')
        @endif

        <!-- Row 1: Jenis Kunjungan and Nama Instansi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Jenis Kunjungan <span class="text-red-500">*</span></label>
                <select name="jenis_kunjungan" id="jenis_kunjungan" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition">
                    <option value="">Pilih jenis kunjungan...</option>
                    <option value="goes to BMKG" @selected($is_edit && $permohonan ? old('jenis_kunjungan', $permohonan->jenis_kunjungan) == 'goes to BMKG' : old('jenis_kunjungan') == 'goes to BMKG')>Datang ke BMKG</option>
                    <option value="goes to school" @selected($is_edit && $permohonan ? old('jenis_kunjungan', $permohonan->jenis_kunjungan) == 'goes to school' : old('jenis_kunjungan') == 'goes to school')>BMKG Datang ke Sekolah</option>
                </select>
                <x-input-error :messages="$errors->get('jenis_kunjungan')" class="mt-2" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama Instansi <span class="text-red-500">*</span></label>
                <input type="text" name="nama_instansi" id="nama_instansi" required
                    value="{{ $is_edit && $permohonan ? old('nama_instansi', $permohonan->nama_instansi) : old('nama_instansi') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('nama_instansi')" class="mt-2" />
            </div>
        </div>

        <!-- Row 2: Nama Lengkap and No WhatsApp -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" required
                    value="{{ $is_edit && $permohonan ? old('nama_lengkap', $permohonan->nama_lengkap) : old('nama_lengkap') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">No WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" id="no_whatsapp" required
                    value="{{ $is_edit && $permohonan ? old('no_whatsapp', $permohonan->no_whatsapp) : old('no_whatsapp') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
            </div>
        </div>

        <!-- Jumlah Rombongan -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Jumlah Rombongan <span class="text-red-500">*</span></label>
            <input type="number" name="jumlah_rombongan" id="jumlah_rombongan" required
                value="{{ $is_edit && $permohonan ? old('jumlah_rombongan', $permohonan->jumlah_rombongan) : old('jumlah_rombongan') }}"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
            <x-input-error :messages="$errors->get('jumlah_rombongan')" class="mt-2" />
        </div>

        <!-- Rencana Kunjungan -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Rencana Kunjungan <span class="text-red-500">*</span></label>
            <textarea name="rencana_kunjungan" id="rencana_kunjungan" rows="4" required
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none">{{ $is_edit && $permohonan ? old('rencana_kunjungan', $permohonan->rencana_kunjungan) : old('rencana_kunjungan') }}</textarea>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Min. 20 karakter</p>
            <x-input-error :messages="$errors->get('rencana_kunjungan')" class="mt-2" />
        </div>

        <!-- Surat Permohonan -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Surat Permohonan <span class="text-red-500">*</span></label>
            <input type="file" name="surat_permohonan" id="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
            <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            
            @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('admin.permohonan-kunjungan.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" 
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($permohonan->surat_permohonan) }}
                    </a>
                </div>
            @endif
        </div>

        <!-- KTP -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">KTP <span class="text-red-500">*</span></label>
            <input type="file" name="ktp" id="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
            <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            
            @if ($is_edit && $permohonan && $permohonan->ktp)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('admin.permohonan-kunjungan.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" 
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($permohonan->ktp) }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Status (Only for Edit) -->
        @if ($is_edit && $permohonan)
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" required>
                    @php
                        $statuses = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'completed' => 'Selesai'];
                    @endphp
                    <option value="">Pilih status...</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(old('status', $permohonan->status) == $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>
        @endif

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
            <i class="fas fa-{{ $is_edit ? 'save' : 'paper-plane' }} mr-2"></i>{{ $is_edit ? 'Ubah Permohonan' : 'Buat Permohonan' }}
        </button>
    </form>
</div>
