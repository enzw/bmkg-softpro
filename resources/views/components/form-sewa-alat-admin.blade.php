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

<div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
    <!-- Header Section -->
    <div class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 dark:text-emerald-400">
                <i class="fas fa-edit text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Formulir Sewa Alat</h2>
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest text-shadow-sm">Lengkapi data penyewaan alat</p>
            </div>
        </div>

        @if($is_edit)
            <button type="button" onclick="showConfirmModal()"
                class="px-5 py-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-emerald-400 hover:border-green-200 dark:hover:border-emerald-800/50 transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        @else
            <a href="{{ url()->previous() }}"
                class="px-5 py-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-emerald-400 hover:border-green-200 dark:hover:border-emerald-800/50 transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        @endif
    </div>

    <div class="p-8">

    {{-- Alert Messages --}}
    @if (session('success'))
        <div
            class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 flex items-start gap-3">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 flex items-start gap-3">
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

    <form action="{{ $is_edit ? route('admin.sewa-alat.update', ['sewa_alat' => $permohonan->id]) : route('admin.sewa-alat.store') }}"
        method="POST" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @if ($is_edit)
            @method('put')
        @endif

        <!-- Row 1: Nama and No WhatsApp -->
        <div class="relative pl-6 border-l-2 border-green-500/30 space-y-6">
            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Informasi Pemohon
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input id="nama" type="text" name="nama" placeholder="Masukkan nama lengkap"
                        value="{{ $is_edit && $permohonan ? old('nama', $permohonan->nama) : old('nama') }}" required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <div>
                    <label for="no_whatsapp" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input id="no_whatsapp" type="tel" name="no_whatsapp" placeholder="62..."
                        value="{{ $is_edit && $permohonan ? old('no_whatsapp', $permohonan->no_whatsapp) : old('no_whatsapp') }}"
                        required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" />
                    <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Row 2: Alat and Quantity -->
        <div class="relative pl-6 border-l-2 border-blue-500/30 space-y-6">
            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                Detail Sewa
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="alat_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Pilih Alat <span class="text-red-500">*</span>
                    </label>
                    <select name="alat_id" id="alat_id" required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                        <option value="">Pilih alat...</option>
                        @foreach ($alats as $alat)
                            <option value="{{ $alat->id }}" @selected($is_edit && $permohonan ? old('alat_id', $permohonan->alat_id) == $alat->id : old('alat_id') == $alat->id)>
                                {{ $alat->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('alat_id')" class="mt-2" />
                </div>

                <div>
                    <label for="banyak_unit" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Jumlah Unit <span class="text-red-500">*</span>
                    </label>
                    <input id="banyak_unit" type="number" name="banyak_unit"
                        value="{{ $is_edit && $permohonan ? old('banyak_unit', $permohonan->banyak_unit) : old('banyak_unit') }}"
                        required min="1"
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" />
                    <x-input-error :messages="$errors->get('banyak_unit')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="sewa_mulai" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input id="sewa_mulai" type="date" name="sewa_mulai"
                        value="{{ $is_edit && $permohonan ? old('sewa_mulai', optional($permohonan->sewa_mulai)->format('Y-m-d')) : old('sewa_mulai') }}"
                        required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" />
                    <x-input-error :messages="$errors->get('sewa_mulai')" class="mt-2" />
                </div>

                <div>
                    <label for="sewa_berakhir" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Tanggal Berakhir <span class="text-red-500">*</span>
                    </label>
                    <input id="sewa_berakhir" type="date" name="sewa_berakhir"
                        value="{{ $is_edit && $permohonan ? old('sewa_berakhir', optional($permohonan->sewa_berakhir)->format('Y-m-d')) : old('sewa_berakhir') }}"
                        required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" />
                    <x-input-error :messages="$errors->get('sewa_berakhir')" class="mt-2" />
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Keterangan
                </label>
                <textarea id="keterangan" name="keterangan" rows="4" placeholder="Jelaskan kebutuhan sewa alat..."
                    class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all resize-none">{{ $is_edit && $permohonan ? old('keterangan', $permohonan->keterangan) : old('keterangan') }}</textarea>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>
        </div>

        <!-- File Section -->
        <div class="relative pl-6 border-l-2 border-orange-500/30 space-y-6">
            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                Dokumen Pendukung
            </h4>
            <!-- File Upload -->
            <div>
                <label for="surat_permohonan" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Surat Permohonan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="surat_permohonan" type="file" name="surat_permohonan"
                        accept=".pdf,.jpg,.jpeg,.png,.docx" {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 dark:hover:file:bg-emerald-900/60 cursor-pointer" />
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG, DOCX. Maksimal 2MB
                </p>
                @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                    <div class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                        <p class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('admin.sewa-alat.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}"
                            class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->surat_permohonan) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <!-- KTP Upload -->
            <div>
                <label for="ktp" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    KTP <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input id="ktp" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png"
                        {{ !$is_edit ? 'required' : '' }}
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 dark:hover:file:bg-emerald-900/60 cursor-pointer" />
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                </p>
                @if ($is_edit && $permohonan && $permohonan->ktp)
                    <div class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                        <p class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                            <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                            File saat ini:
                        </p>
                        <a href="{{ route('admin.sewa-alat.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}"
                            class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                            <i class="fas fa-download"></i>
                            {{ basename($permohonan->ktp) }}
                        </a>
                    </div>
                @endif
                <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
            </div>

            <!-- Status -->
            @if ($is_edit)
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-bold text-xs uppercase tracking-widest">
                         @php
                            $statuses = App\Enums\SewaStatus::cases();
                        @endphp
                        <option value="">Pilih status...</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}"
                                @selected(old('status', isset($permohonan->status) ? ($permohonan->status->value ?? $permohonan->status) : '') == ($status->value ?? $status))>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-6">
            <button type="submit"
                class="w-full py-4 px-8 rounded-2xl bg-green-500 hover:bg-green-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-green-200 dark:shadow-none transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <i class="fas fa-{{ $is_edit ? 'save' : 'paper-plane' }}"></i>
                {{ $is_edit ? 'Simpan Perubahan' : 'Kirim Permohonan' }}
            </button>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
@if($is_edit)
<div id="confirmModal"
    class="hidden fixed inset-0 z-50 overflow-auto bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-gray-800 rounded-[2.5rem] max-w-sm w-full shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden animate-in fade-in zoom-in duration-300">
        <!-- Header -->
        <div
            class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Konfirmasi
                        Kembali</h3>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">Apakah Anda yakin?</p>
                </div>
            </div>
            <button type="button" onclick="closeConfirmModal()"
                class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors shadow-sm border border-gray-100 dark:border-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="px-8 pt-6 pb-8 text-center">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest leading-relaxed mb-2">
                Perubahan yang belum disimpan akan hilang
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-500">
                Pastikan semua data telah disimpan sebelum kembali ke halaman sebelumnya.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 p-6 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700">
            <button type="button" onclick="closeConfirmModal()"
                class="flex-1 px-6 py-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-gray-50 dark:hover:bg-gray-700">
                Batal
            </button>
            <button type="button" onclick="confirmBack()"
                class="flex-1 px-6 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-red-200 dark:shadow-none flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>
    </div>
</div>
@endif

<script>
    function showConfirmModal() {
        @if($is_edit)
            const modal = document.getElementById('confirmModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        @endif
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    function confirmBack() {
        window.history.back();
    }

    @if($is_edit)
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('confirmModal');
        if (modal && !modal.classList.contains('hidden')) {
            const modalContent = modal.querySelector('.bg-white, .dark\:bg-gray-800');
            if (event.target === modal) {
                closeConfirmModal();
            }
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeConfirmModal();
        }
    });
    @endif
</script>