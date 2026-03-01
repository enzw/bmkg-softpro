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

<div
    class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden h-max lg:sticky lg:top-20">
    <!-- Header Section -->
    <div
        class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 dark:text-emerald-400">
                <i class="fas fa-edit text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                    {{ $is_edit ? 'Edit' : 'Buat' }} Formulir Pelayanan Jasa
                </h2>
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest text-shadow-sm">
                    Lengkapi data permohonan layanan
                </p>
            </div>
        </div>

        <a href="{{ url()->previous() }}"
            class="px-5 py-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-emerald-400 hover:border-green-200 dark:hover:border-emerald-800/50 transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    @if(!$is_edit)
        <!-- Tab Navigation -->
        <div class="px-8 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex overflow-x-auto no-scrollbar scroll-smooth">
                <button type="button" role="tab" onclick="switchTab('Magang', this)"
                    class="tab-button px-6 py-4 font-bold text-[10px] uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition whitespace-nowrap @if($jenis_layanan == 'Magang') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                    aria-selected="@if($jenis_layanan == 'Magang') true @else false @endif">
                    <i class="fas fa-user-graduate mr-2"></i>Magang
                </button>
                <button type="button" role="tab" onclick="switchTab('Layanan Klaim Asuransi', this)"
                    class="tab-button px-6 py-4 font-bold text-[10px] uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition whitespace-nowrap @if($jenis_layanan == 'Layanan Klaim Asuransi') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                    aria-selected="@if($jenis_layanan == 'Layanan Klaim Asuransi') true @else false @endif">
                    <i class="fas fa-shield-alt mr-2"></i>Asuransi
                </button>
                <button type="button" role="tab" onclick="switchTab('Layanan Data', this)"
                    class="tab-button px-6 py-4 font-bold text-[10px] uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition whitespace-nowrap @if($jenis_layanan == 'Layanan Data') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                    aria-selected="@if($jenis_layanan == 'Layanan Data') true @else false @endif">
                    <i class="fas fa-database mr-2"></i>Data
                </button>
                <button type="button" role="tab" onclick="switchTab('Layanan Survey', this)"
                    class="tab-button px-6 py-4 font-bold text-[10px] uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition whitespace-nowrap @if($jenis_layanan == 'Layanan Survey') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                    aria-selected="@if($jenis_layanan == 'Layanan Survey') true @else false @endif">
                    <i class="fas fa-map-marked-alt mr-2"></i>Survey
                </button>
                <button type="button" role="tab" onclick="switchTab('Layanan Konsultasi', this)"
                    class="tab-button px-6 py-4 font-bold text-[10px] uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition whitespace-nowrap @if($jenis_layanan == 'Layanan Konsultasi') active-tab border-green-500 text-green-600 dark:text-green-400 @endif"
                    aria-selected="@if($jenis_layanan == 'Layanan Konsultasi') true @else false @endif">
                    <i class="fas fa-comments mr-2"></i>Konsultasi
                </button>
            </div>
        </div>
    @endif

    <div class="p-8">
        {{-- Alert Messages --}}
        @if (session('success'))
            <div
                class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mt-0.5"></i>
                <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        @endif

        <form
            action="{{ $is_edit ? url('admin/pelayanan-jasa/' . $permohonan->id) : route('admin.pelayanan-jasa.store') }}"
            method="POST" class="space-y-8" enctype="multipart/form-data" id="form-layanan">
            @csrf
            @if ($is_edit)
                @method('put')
            @endif

            <!-- Hidden input for jenis_layanan -->
            <input type="hidden" name="jenis_layanan" id="jenis_layanan" value="{{ $jenis_layanan }}" />

            <!-- MAGANG Fields -->
            <div id="magang-fields" style="display: {{ $jenis_layanan == 'Magang' ? 'block' : 'none' }}"
                class="space-y-8">
                <div class="relative pl-6 border-l-2 border-green-500/30 space-y-6">
                    <h4
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Informasi Personal
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Nama Lengkap
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap"
                                value="{{ $is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email"
                                value="{{ $is_edit ? old('email', $permohonan->email ?? '') : old('email') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">WhatsApp <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="no_whatsapp"
                                value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="relative pl-6 border-l-2 border-blue-500/30 space-y-6">
                    <h4
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Informasi Akademik & Durasi
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Universitas
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="universitas"
                                value="{{ $is_edit ? old('universitas', $permohonan->universitas ?? '') : old('universitas') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('universitas')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Fakultas <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="fakultas"
                                value="{{ $is_edit ? old('fakultas', $permohonan->fakultas ?? '') : old('fakultas') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Program Studi
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="prodi"
                                value="{{ $is_edit ? old('prodi', $permohonan->prodi ?? '') : old('prodi') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Tgl Mulai
                                    <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai"
                                    value="{{ $is_edit && $permohonan ? old('tanggal_mulai', optional($permohonan->tanggal_mulai)->format('Y-m-d')) : old('tanggal_mulai') }}"
                                    class="w-full px-4 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                                <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Tgl Selesai
                                    <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_selesai"
                                    value="{{ $is_edit && $permohonan ? old('tanggal_selesai', optional($permohonan->tanggal_selesai)->format('Y-m-d')) : old('tanggal_selesai') }}"
                                    class="w-full px-4 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                                <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative pl-6 border-l-2 border-orange-500/30 space-y-6">
                    <h4
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                        Dokumen Pendukung
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="surat_permohonan"
                                class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Surat Permohonan (Image/PDF)
                            </label>
                            <div class="relative">
                                <input id="surat_permohonan" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                            </div>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                            </p>
                            @if ($is_edit && $permohonan && !empty($permohonan->surat_permohonan))
                                <div
                                    class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                    <p
                                        class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                        File saat ini:
                                    </p>
                                    <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}"
                                        class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                        <i class="fas fa-download"></i> {{ basename($permohonan->surat_permohonan) }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="kartu_mahasiswa"
                                class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Kartu Mahasiswa (Image)
                            </label>
                            <div class="relative">
                                <input id="kartu_mahasiswa" type="file" name="kartu_mahasiswa" accept="image/*"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                            </div>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: JPG, PNG. Maksimal 2MB
                            </p>
                            @if ($is_edit && $permohonan && !empty($permohonan->kartu_mahasiswa))
                                <div
                                    class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                    <p
                                        class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                        File saat ini:
                                    </p>
                                    <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->kartu_mahasiswa)]) }}"
                                        class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                        <i class="fas fa-download"></i> {{ basename($permohonan->kartu_mahasiswa) }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ASURANSI Fields -->
            <div id="asuransi-fields"
                style="display: {{ $jenis_layanan == 'Layanan Klaim Asuransi' ? 'block' : 'none' }}" class="space-y-8">
                <div class="relative pl-6 border-l-2 border-green-500/30 space-y-6">
                    <h4
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Informasi Pemohon & Lokasi
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Nama <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama_user"
                                value="{{ $is_edit ? old('nama_user', $permohonan->nama_user ?? '') : old('nama_user') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('nama_user')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">WhatsApp <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="no_whatsapp"
                                value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Institusi/Perusahaan
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="perusahaan"
                                value="{{ $is_edit ? old('perusahaan', $permohonan->perusahaan ?? '') : old('perusahaan') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('perusahaan')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Tanggal Kejadian
                                <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal"
                                value="{{ $is_edit && $permohonan ? old('tanggal', optional($permohonan->tanggal)->format('Y-m-d')) : old('tanggal') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="relative pl-6 border-l-2 border-blue-500/30 space-y-6">
                    <h4
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Detail Lokasi & Berkas
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Lokasi Kejadian
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi"
                                value="{{ $is_edit ? old('lokasi', $permohonan->lokasi ?? '') : old('lokasi') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Latitude</label>
                            <input type="number" step="any" name="latitude"
                                value="{{ $is_edit ? old('latitude', $permohonan->latitude ?? '') : old('latitude') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40" />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Longitude</label>
                            <input type="number" step="any" name="longitude"
                                value="{{ $is_edit ? old('longitude', $permohonan->longitude ?? '') : old('longitude') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40" />
                        </div>
                        <div>
                            <label for="surat_permohonan_asuransi"
                                class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Surat Permohonan (Image/PDF)
                            </label>
                            <div class="relative">
                                <input id="surat_permohonan_asuransi" type="file" name="surat_permohonan" accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                            </div>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                            </p>
                            @if ($is_edit && $permohonan && !empty($permohonan->surat_permohonan))
                                <div
                                    class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                    <p
                                        class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                        File saat ini:
                                    </p>
                                    <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}"
                                        class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                        <i class="fas fa-download"></i> {{ basename($permohonan->surat_permohonan) }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="ktp_asuransi"
                                class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                KTP (Image/PDF)
                            </label>
                            <div class="relative">
                                <input id="ktp_asuransi" type="file" name="ktp" accept=".pdf,image/*"
                                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                            </div>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG. Maksimal
                                2MB
                            </p>
                            @if ($is_edit && $permohonan && !empty($permohonan->ktp))
                                <div
                                    class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                    <p
                                        class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                        <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                        File saat ini:
                                    </p>
                                    <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}"
                                        class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                        <i class="fas fa-download"></i> {{ basename($permohonan->ktp) }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA, SURVEY, KONSULTASI Fields -->
            <div id="common-fields"
                style="display: {{ in_array($jenis_layanan, ['Layanan Data', 'Layanan Survey', 'Layanan Konsultasi']) ? 'block' : 'none' }}"
                class="space-y-8">
                <div class="relative pl-6 border-l-2 border-green-500/30 space-y-6">
                    <h4
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Informasi Pemohon
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Nama Lengkap
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap"
                                value="{{ $is_edit ? old('nama_lengkap', $permohonan->nama_lengkap ?? '') : old('nama_lengkap') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">WhatsApp <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="no_whatsapp"
                                value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp ?? '') : old('no_whatsapp') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email"
                                value="{{ $is_edit ? old('email', $permohonan->email ?? '') : old('email') }}"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="relative pl-6 border-l-2 border-blue-500/30 space-y-6">
                    <h4
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Dokumen Pendukung
                    </h4>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="surat_permohonan_common"
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Surat Permohonan (Image/PDF)
                                </label>
                                <div class="relative">
                                    <input id="surat_permohonan_common" type="file" name="surat_permohonan"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                                </div>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                                    <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                                </p>
                                @if ($is_edit && $permohonan && !empty($permohonan->surat_permohonan))
                                    <div
                                        class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                        <p
                                            class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                            File saat ini:
                                        </p>
                                        <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}"
                                            class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                            <i class="fas fa-download"></i> {{ basename($permohonan->surat_permohonan) }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label for="ktp_common"
                                    class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    KTP (Image/PDF)
                                </label>
                                <div class="relative">
                                    <input id="ktp_common" type="file" name="ktp" accept=".pdf,image/*"
                                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                                </div>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                                    <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG.
                                    Maksimal 2MB
                                </p>
                                @if ($is_edit && $permohonan && !empty($permohonan->ktp))
                                    <div
                                        class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                        <p
                                            class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                            File saat ini:
                                        </p>
                                        <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}"
                                            class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                            <i class="fas fa-download"></i> {{ basename($permohonan->ktp) }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($is_edit)
                <div class="relative pl-6 border-l-2 border-orange-500/30 space-y-6 pt-4">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                        Status Permohonan
                    </h4>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Status
                            <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required
                            class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-bold text-xs uppercase tracking-widest">
                            <option value="">Pilih status...</option>
                            @foreach (\App\Enums\Status::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status', isset($permohonan->status) ? ($permohonan->status->value ?? $permohonan->status) : '') == ($status->value ?? $status))>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
            @endif

            <div class="pt-6">
                <button type="submit"
                    class="w-full py-4 px-8 rounded-2xl bg-green-500 hover:bg-green-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-green-200 dark:shadow-none transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fas fa-{{ $is_edit ? 'save' : 'paper-plane' }}"></i>
                    {{ $is_edit ? 'Simpan Perubahan' : 'Kirim Permohonan' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function disableHiddenFields(visibleSectionId) {
        // List semua field section IDs yang mungkin
        const allSections = ['magang-fields', 'asuransi-     fields', 'common-fields'];

        allSections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                const inputs = section.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.disabled = (sectionId !== visibleSectionId);
                });
            }
        });
    }

    function switchTab(serviceType, buttonElement) {
        // Update hidden input
        document.getElementById('jenis_layanan').value = serviceType;

        // Hide all field sections
        document.getElementById('magang-fields').style.display = 'none';
        document.getElementById('asuransi-fields').style.display = 'none';
        document.getElementById('common-fields').style.display = 'none';

        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active-tab', 'border-green-500', 'text-green-600', 'dark:text-green-400');
            btn.classList.add('border-transparent', 'text-gray-400');
            btn.setAttribute('aria-selected', 'false');
        });

        // Show relevant section
        let visibleSectionId;
        if (serviceType === 'Magang') {
            visibleSectionId = 'magang-fields';
        } else if (serviceType === 'Layanan Klaim Asuransi') {
            visibleSectionId = 'asuransi-fields';
        } else {
            // Data, Survey, Konsultasi use the same template but different labels (handled by logic)
            visibleSectionId = 'common-fields';
        }

        if (visibleSectionId) {
            document.getElementById(visibleSectionId).style.display = 'block';
            disableHiddenFields(visibleSectionId);
        }

        // Activate current tab
        buttonElement.classList.add('active-tab', 'border-green-500', 'text-green-600', 'dark:text-green-400');
        buttonElement.classList.remove('border-transparent', 'text-gray-400');
        buttonElement.setAttribute('aria-selected', 'true');
    }

    function updateFormFields() {
        const jenis_layanan = document.getElementById('jenis_layanan').value;
        const tabs = Array.from(document.querySelectorAll('.tab-button'));
        const activeTab = tabs.find(btn => btn.innerText.trim().includes(jenis_layanan.split(' ').pop()) || btn.innerText.trim().includes(jenis_layanan));

        if (activeTab) {
            switchTab(jenis_layanan, activeTab);
        } else if (tabs.length > 0) {
            // Default to first tab if no match
            switchTab('Magang', tabs[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', updateFormFields);
</script>

<style>
    .active-tab {
        border-bottom-color: rgb(34, 197, 94) !important;
        color: rgb(22, 163, 74) !important;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>