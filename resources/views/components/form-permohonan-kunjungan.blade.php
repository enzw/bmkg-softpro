<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 h-max lg:sticky lg:top-20">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                <i class="fas fa-calendar-check text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Formulir Permohonan</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Isi semua field yang wajib diisi</p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 flex items-start gap-3">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
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

    <form action="{{ isset($kunjungan) ? route('permohonan-kunjungan.update', $kunjungan) : route('permohonan-kunjungan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @if(isset($kunjungan))
            @method('PUT')
        @endif

        <!-- Jenis Kunjungan -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Jenis Kunjungan <span class="text-red-500">*</span></label>
            <select id="jenis_kunjungan" name="jenis_kunjungan" required
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition">
                <option value="">Pilih jenis kunjungan...</option>
                <option value="goes to BMKG" {{ old('jenis_kunjungan') === 'goes to BMKG' ? 'selected' : '' }}>Datang ke BMKG</option>
                <option value="goes to school" {{ old('jenis_kunjungan') === 'goes to school' ? 'selected' : '' }}>BMKG Datang ke Sekolah</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_kunjungan')" class="mt-2" />
        </div>

        <!-- Nama Instansi -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama Instansi <span class="text-red-500">*</span></label>
            <input type="text" id="nama_instansi" name="nama_instansi" required
                value="{{ old('nama_instansi') }}"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
            <x-input-error :messages="$errors->get('nama_instansi')" class="mt-2" />
        </div>

        <!-- Nama Lengkap -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" required
                value="{{ old('nama_lengkap') }}"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
        </div>

        <!-- No WhatsApp -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">No WhatsApp <span class="text-red-500">*</span></label>
            <input type="text" id="no_whatsapp" name="no_whatsapp" required
                value="{{ old('no_whatsapp') }}"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
            <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
        </div>

        <!-- Jumlah Rombongan -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Jumlah Rombongan <span class="text-red-500">*</span></label>
            <input type="number" id="jumlah_rombongan" name="jumlah_rombongan" required min="1"
                value="{{ old('jumlah_rombongan') }}"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
            <x-input-error :messages="$errors->get('jumlah_rombongan')" class="mt-2" />
        </div>

        <!-- Rencana Kunjungan -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Rencana Kunjungan <span class="text-red-500">*</span></label>
            <textarea id="rencana_kunjungan" name="rencana_kunjungan" rows="4" required
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none">{{ old('rencana_kunjungan') }}</textarea>
            <x-input-error :messages="$errors->get('rencana_kunjungan')" class="mt-2" />
        </div>

        <!-- Surat Permohonan -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Surat Permohonan <span class="text-red-500">*</span></label>
            <input type="file" id="surat_permohonan" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($kunjungan) ? 'required' : '' }}
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
            <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            
            @if (isset($kunjungan) && $kunjungan->surat_permohonan)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('permohonan-kunjungan.download-file', ['id' => $kunjungan->id, 'fileName' => basename($kunjungan->surat_permohonan)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($kunjungan->surat_permohonan) }}
                    </a>
                </div>
            @endif
        </div>

        <!-- KTP -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">KTP <span class="text-red-500">*</span></label>
            <input type="file" id="ktp" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !isset($kunjungan) ? 'required' : '' }}
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
            <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            
            @if (isset($kunjungan) && $kunjungan->ktp)
                <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                        File saat ini:
                    </p>
                    <a href="{{ route('permohonan-kunjungan.download-file', ['id' => $kunjungan->id, 'fileName' => basename($kunjungan->ktp)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                        <i class="fas fa-download"></i>
                        {{ basename($kunjungan->ktp) }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3 px-6 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 dark:from-green-600 dark:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
            <i class="fas fa-paper-plane mr-2"></i>Kirim Permohonan
        </button>
    </form>
</div>
