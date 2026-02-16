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

<div
    class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden h-max lg:sticky lg:top-12">
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
                    {{ $is_edit ? 'Edit' : 'Buat' }} Permohonan Jasa Konsultasi
                </h2>
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest text-shadow-sm">
                    Lengkapi data permohonan jasa konsultasi
                </p>
            </div>
        </div>

        <a href="{{ url()->previous() }}"
            class="px-5 py-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-emerald-400 hover:border-green-200 dark:hover:border-emerald-800/50 transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    @php
        $permohonan = $permohonan ?? null;
    @endphp

    <div class="p-8">
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

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50">
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
            action="{{ $is_edit ? route('admin.jasa-konsultasi.update', ['jasa_konsultasi' => $permohonan->id]) : route('admin.jasa-konsultasi.store') }}"
            method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf
            @if ($is_edit)
                @method('put')
            @endif

            <div class="relative pl-6 border-l-2 border-green-500/30 space-y-6">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                    Informasi Pemohon
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap"
                            value="{{ $is_edit ? old('nama_lengkap', $permohonan->nama_lengkap) : old('nama_lengkap') }}"
                            required
                            class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                        <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                    </div>

                    <div>
                        <label for="no_whatsapp" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            No WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input id="no_whatsapp" type="text" name="no_whatsapp" placeholder="62..."
                            value="{{ $is_edit ? old('no_whatsapp', $permohonan->no_whatsapp) : old('no_whatsapp') }}"
                            required
                            class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                        <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input id="email" type="email" name="email" placeholder="example@mail.com"
                        value="{{ $is_edit ? old('email', $permohonan->email) : old('email') }}" required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <div class="relative pl-6 border-l-2 border-blue-500/30 space-y-6">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Detail Permohonan
                </h4>
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Deskripsi Layanan yang Dibutuhkan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        placeholder="Jelaskan kebutuhan layanan Anda..." required
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium resize-none">{{ $is_edit ? old('keterangan', $permohonan->keterangan) : old('keterangan') }}</textarea>
                    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="surat_permohonan"
                            class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Surat Permohonan (Opsional)
                        </label>
                        <div class="relative">
                            <input id="surat_permohonan" type="file" name="surat_permohonan"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                        </p>
                        @if ($is_edit && $permohonan && $permohonan->surat_permohonan)
                            <div
                                class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                <p
                                    class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                    <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                    File saat ini:
                                </p>
                                <a href="{{ route('admin.jasa-konsultasi.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->surat_permohonan)]) }}"
                                    class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                    <i class="fas fa-download"></i> {{ basename($permohonan->surat_permohonan) }}
                                </a>
                            </div>
                        @endif
                        <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
                    </div>

                    <div>
                        <label for="ktp" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            KTP <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="ktp" type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" {{ !$is_edit ? 'required' : '' }}
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 dark:file:bg-emerald-900/40 file:text-green-700 dark:file:text-emerald-400 hover:file:bg-green-200 cursor-pointer" />
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1 text-green-500"></i>Format: PDF, JPG, PNG. Maksimal 2MB
                        </p>
                        @if ($is_edit && $permohonan && $permohonan->ktp)
                            <div
                                class="mt-4 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/20">
                                <p
                                    class="text-[10px] font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2 uppercase tracking-widest">
                                    <i class="fas fa-file text-blue-600 dark:text-blue-400"></i>
                                    File saat ini:
                                </p>
                                <a href="{{ route('admin.jasa-konsultasi.download-file', ['id' => $permohonan->id, 'fileName' => basename($permohonan->ktp)]) }}"
                                    class="inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition uppercase tracking-widest">
                                    <i class="fas fa-download"></i> {{ basename($permohonan->ktp) }}
                                </a>
                            </div>
                        @endif
                        <x-input-error :messages="$errors->get('ktp')" class="mt-2" />
                    </div>
                </div>
            </div>

            @if ($is_edit)
                <div class="relative pl-6 border-l-2 border-orange-500/30 space-y-6">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                        Status Permohonan
                    </h4>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-bold text-xs uppercase tracking-widest">
                            <option value="">Pilih status...</option>
                            @php
                                $statuses = App\Enums\Status::cases();
                            @endphp
                            @foreach ($statuses as $status)
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
</div>