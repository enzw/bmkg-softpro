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
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $is_edit ? 'Edit Permohonan' : 'Buat Permohonan' }}</h2>
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

    @php
    $permohonan = $permohonan ?? null;
    
    // Tentukan jenis_layanan berdasarkan tipe model saat edit
    if ($is_edit && $permohonan) {
        $modelClass = class_basename($permohonan::class);
        if ($modelClass === 'Magang') {
            $jenis_layanan = 'Magang';
        } elseif ($modelClass === 'Asuransi') {
            $jenis_layanan = 'Layanan Klaim Asuransi';
        } elseif ($modelClass === 'LayananData') {
            $jenis_layanan = 'Layanan Data';
        } elseif ($modelClass === 'Survey') {
            $jenis_layanan = 'Layanan Survey';
        } elseif ($modelClass === 'JasaKonsultasi') {
            $jenis_layanan = 'Layanan Konsultasi';
        } else {
            $jenis_layanan = old('jenis_layanan', 'Magang');
        }
    } else {
        $jenis_layanan = old('jenis_layanan', 'Magang');
    }
    @endphp

    <!-- Tabs Navigation - Hanya tampil di CREATE mode -->
    @if (!$is_edit)
    <div class="border-b border-gray-200 dark:border-gray-700 mb-8 overflow-x-auto">
        <div class="flex gap-0" role="tablist">
            <button 
                role="tab"
                onclick="switchTab('Magang', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Magang') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Magang') true @else false @endif">
                <i class="fas fa-user-graduate mr-2"></i>Magang
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Klaim Asuransi', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Klaim Asuransi') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Klaim Asuransi') true @else false @endif">
                <i class="fas fa-shield-alt mr-2"></i>Asuransi
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Data', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Data') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Data') true @else false @endif">
                <i class="fas fa-database mr-2"></i>Data
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Survey', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Survey') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Survey') true @else false @endif">
                <i class="fas fa-map-marked-alt mr-2"></i>Survey
            </button>
            <button 
                role="tab"
                onclick="switchTab('Layanan Konsultasi', this)"
                class="tab-button px-4 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition @if($jenis_layanan == 'Layanan Konsultasi') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                aria-selected="@if($jenis_layanan == 'Layanan Konsultasi') true @else false @endif">
                <i class="fas fa-comments mr-2"></i>Konsultasi
            </button>
        </div>
    </div>
    @endif

    <form action="{{ $is_edit ? url('admin/pelayanan-jasa/' . $permohonan->id) : route('admin.pelayanan-jasa.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data" id="form-layanan">
        @csrf
        @if ($is_edit)
        @method('put')
        @endif

        <!-- Hidden input for jenis_layanan -->
        <input type="hidden" name="jenis_layanan" id="jenis_layanan" value="{{ $jenis_layanan }}" />

        <!-- MAGANG Fields -->
        <div id="magang-fields" style="display: {{ $jenis_layanan == 'Magang' ? 'block' : 'none' }}" class="space-y-6">
            <!-- Row 1: Basic Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="magang_nama_lengkap" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input id="magang_nama_lengkap" type="text" name="nama_lengkap" value="{{ $is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="magang_email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input id="magang_email" type="email" name="email" value="{{ $is_edit ? old('email', $permohonan->email ?? '') : old('email') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: WhatsApp -->
            <div>
                <label for="magang_no_whatsapp" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    No WhatsApp <span class="text-red-500">*</span>
                </label>
                <input id="magang_no_whatsapp" type="text" name="no_whatsapp" value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                @error('no_whatsapp')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 3: Academic Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="universitas" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Universitas <span class="text-red-500">*</span>
                    </label>
                    <input id="universitas" type="text" name="universitas" value="{{ $is_edit ? old('universitas', $permohonan->universitas ?? '') : old('universitas') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('universitas')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fakultas" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Fakultas <span class="text-red-500">*</span>
                    </label>
                    <input id="fakultas" type="text" name="fakultas" value="{{ $is_edit ? old('fakultas', $permohonan->fakultas ?? '') : old('fakultas') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('fakultas')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prodi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Program Studi <span class="text-red-500">*</span>
                    </label>
                    <input id="prodi" type="text" name="prodi" value="{{ $is_edit ? old('prodi', $permohonan->prodi ?? '') : old('prodi') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('prodi')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Dates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ $is_edit ? old('tanggal_mulai', $permohonan->tanggal_mulai ?? '') : old('tanggal_mulai') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ $is_edit ? old('tanggal_selesai', $permohonan->tanggal_selesai ?? '') : old('tanggal_selesai') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 5: File Uploads -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="magang_surat_permohonan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Surat Permohonan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="magang_surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
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
                            <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                                <i class="fas fa-download"></i>
                                {{ basename($permohonan->surat_permohonan) }}
                            </a>
                        </div>
                    @endif
                    <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
                </div>

                <div>
                    <label for="magang_kartu_mahasiswa" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Kartu Mahasiswa <span class="text-xs text-gray-500 dark:text-gray-400">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <input id="magang_kartu_mahasiswa" type="file" name="kartu_mahasiswa" accept=".jpg,.jpeg,.png"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG. Maksimal 2MB
                    </p>
                    @if ($is_edit && $permohonan && $permohonan->kartu_mahasiswa)
                        <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                                <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                File saat ini:
                            </p>
                            <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->kartu_mahasiswa)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                                <i class="fas fa-download"></i>
                                {{ basename($permohonan->kartu_mahasiswa) }}
                            </a>
                        </div>
                    @endif
                    <x-input-error :messages="$errors->get('kartu_mahasiswa')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- ASURANSI Fields -->
        <div id="asuransi-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Klaim Asuransi' ? 'block' : 'none' }}" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_user" value="{{ $is_edit ? old('nama_user', $permohonan->nama_user ?? '') : old('nama_user') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('nama_user')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">No WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_whatsapp" value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('no_whatsapp')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Lokasi Kejadian <span class="text-red-500">*</span></label>
                <input type="text" name="lokasi" value="{{ $is_edit ? old('lokasi', $permohonan->lokasi ?? '') : old('lokasi') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                @error('lokasi')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Latitude <span class="text-red-500">*</span></label>
                    <input type="number" step="0.00000001" min="-90" max="90" name="latitude" value="{{ $is_edit ? old('latitude', $permohonan->latitude ?? '') : old('latitude') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="-90 hingga 90" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Range: -90 hingga 90</p>
                    @error('latitude')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Longitude <span class="text-red-500">*</span></label>
                    <input type="number" step="0.00000001" min="-180" max="180" name="longitude" value="{{ $is_edit ? old('longitude', $permohonan->longitude ?? '') : old('longitude') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" placeholder="-180 hingga 180" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Range: -180 hingga 180</p>
                    @error('longitude')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Tanggal Kejadian <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ $is_edit ? old('tanggal', $permohonan->tanggal ?? '') : old('tanggal') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                @error('tanggal')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Surat Permohonan <span class="text-red-500">*</span></label>
                <input type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->surat_permohonan) }}
                        </a>
                    </div>
                @endif
                @error('surat_permohonan')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">KTP <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->ktp)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->ktp) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>
        </div>

        <!-- DATA GEOFISIKA Fields -->
        <div id="data-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Data' ? 'block' : 'none' }}" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ $is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('nama_lengkap')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ $is_edit ? old('email', $permohonan->email ?? '') : old('email') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('email')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">No WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                @error('no_whatsapp')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Deskripsi Data <span class="text-red-500">*</span></label>
                <textarea name="keterangan" rows="4" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none">{{ $is_edit ? old('keterangan', $permohonan->keterangan ?? '') : old('keterangan') }}</textarea>
                @error('keterangan')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Surat Permohonan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->surat_permohonan) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">KTP <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->ktp)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->ktp) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>
        </div>

        <!-- SURVEY Fields -->
        <div id="survey-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Survey' ? 'block' : 'none' }}" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ $is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('nama_lengkap')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ $is_edit ? old('email', $permohonan->email ?? '') : old('email') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('email')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">No WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                @error('no_whatsapp')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Deskripsi Survey <span class="text-red-500">*</span></label>
                <textarea name="keterangan" rows="4" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none">{{ $is_edit ? old('keterangan', $permohonan->keterangan ?? '') : old('keterangan') }}</textarea>
                @error('keterangan')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Surat Permohonan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->surat_permohonan) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">KTP <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->ktp)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->ktp) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>
        </div>

        <!-- KONSULTASI Fields -->
        <div id="konsultasi-fields" style="display: {{ $is_edit && $jenis_layanan == 'Layanan Konsultasi' ? 'block' : 'none' }}" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ $is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('nama_lengkap')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ $is_edit ? old('email', $permohonan->email ?? '') : old('email') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                    @error('email')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">No WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition" />
                @error('no_whatsapp')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Topik Konsultasi <span class="text-red-500">*</span></label>
                <textarea name="keterangan" rows="4" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition resize-none">{{ $is_edit ? old('keterangan', $permohonan->keterangan ?? '') : old('keterangan') }}</textarea>
                @error('keterangan')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Surat Permohonan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->surat_permohonan) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">KTP <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-green-900/30 file:text-green-700 dark:file:text-green-300 hover:file:bg-green-200 dark:hover:file:bg-green-900/50 cursor-pointer" />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: PDF, JPG, PNG. Maksimal 2MB</p>
                @if ($is_edit && $permohonan && $permohonan->ktp)
                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->ktp) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>
        </div>

        @if ($is_edit)
        <div>
            <label for="status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Status <span class="text-red-500">*</span>
            </label>
            <select name="status" id="status" required
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:focus:ring-green-400 transition">
                <option value="">Pilih status...</option>
                <option value="Menunggu" @selected(old('status', $permohonan->status ?? '') == 'Menunggu')>Menunggu</option>
                <option value="Diproses" @selected(old('status', $permohonan->status ?? '') == 'Diproses')>Diproses</option>
                <option value="Ditolak" @selected(old('status', $permohonan->status ?? '') == 'Ditolak')>Ditolak</option>
                <option value="Selesai" @selected(old('status', $permohonan->status ?? '') == 'Selesai')>Selesai</option>
            </select>
            @error('status')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        @endif

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit"
                class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 dark:from-green-700 dark:to-green-800 dark:hover:from-green-800 dark:hover:to-green-900 text-white font-semibold transition">
                <i class="fas fa-paper-plane mr-2"></i>{{ $is_edit ? 'Perbarui' : 'Kirim' }} Permohonan
            </button>
        </div>
    </form>
</div>

<script>
function disableHiddenFields(visibleSectionId) {
    // List semua field section IDs
    const allSections = ['magang-fields', 'asuransi-fields', 'data-fields', 'survey-fields', 'konsultasi-fields'];
    
    // Disable/enable inputs berdasarkan visibility
    allSections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const inputs = section.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (sectionId === visibleSectionId) {
                    input.disabled = false;
                } else {
                    input.disabled = true;
                }
            });
        }
    });
}

function switchTab(serviceType, buttonElement) {
    // Update hidden input
    document.getElementById('jenis_layanan').value = serviceType;
    
    // Hide all field divs
    document.getElementById('magang-fields').style.display = 'none';
    document.getElementById('asuransi-fields').style.display = 'none';
    document.getElementById('data-fields').style.display = 'none';
    document.getElementById('survey-fields').style.display = 'none';
    document.getElementById('konsultasi-fields').style.display = 'none';
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active-tab', 'border-green-500', 'text-green-600', 'dark:text-green-400');
        btn.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    });
    
    // Show relevant fields based on service type
    let visibleSectionId;
    if (serviceType === 'Magang') {
        visibleSectionId = 'magang-fields';
        document.getElementById('magang-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Klaim Asuransi') {
        visibleSectionId = 'asuransi-fields';
        document.getElementById('asuransi-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Data') {
        visibleSectionId = 'data-fields';
        document.getElementById('data-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Survey') {
        visibleSectionId = 'survey-fields';
        document.getElementById('survey-fields').style.display = 'block';
    } else if (serviceType === 'Layanan Konsultasi') {
        visibleSectionId = 'konsultasi-fields';
        document.getElementById('konsultasi-fields').style.display = 'block';
    }
    
    // Disable fields yang tidak visible
    if (visibleSectionId) {
        disableHiddenFields(visibleSectionId);
    }
    
    // Activate current tab
    buttonElement.classList.add('active-tab', 'border-green-500', 'text-green-600', 'dark:text-green-400');
    buttonElement.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
}

function updateFormFields() {
    const jenis_layanan = document.getElementById('jenis_layanan').value;
    
    // Hide all field sections
    document.getElementById('magang-fields').style.display = 'none';
    document.getElementById('asuransi-fields').style.display = 'none';
    document.getElementById('data-fields').style.display = 'none';
    document.getElementById('survey-fields').style.display = 'none';
    document.getElementById('konsultasi-fields').style.display = 'none';
    
    // Show relevant section and enable its fields
    let visibleSectionId;
    if (jenis_layanan === 'Magang') {
        visibleSectionId = 'magang-fields';
        document.getElementById('magang-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Klaim Asuransi') {
        visibleSectionId = 'asuransi-fields';
        document.getElementById('asuransi-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Data') {
        visibleSectionId = 'data-fields';
        document.getElementById('data-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Survey') {
        visibleSectionId = 'survey-fields';
        document.getElementById('survey-fields').style.display = 'block';
    } else if (jenis_layanan === 'Layanan Konsultasi') {
        visibleSectionId = 'konsultasi-fields';
        document.getElementById('konsultasi-fields').style.display = 'block';
    }
    
    // Disable fields yang tidak visible
    if (visibleSectionId) {
        disableHiddenFields(visibleSectionId);
    }
}

// Initialize form fields on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFormFields();
});
</script>

<style>
.active-tab {
    border-bottom-color: rgb(34, 197, 94) !important;
    color: rgb(22, 163, 74) !important;
}
</style>