<div class="space-y-4">
    @if ($permohonan->isEmpty())
        <div class="flex flex-col items-center justify-center py-16">
            <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Permohonan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan buat permohonan baru untuk memulai</p>
        </div>
    @else
        @foreach ($permohonan as $item)
            @php
                $date1 = new DateTime($item->sewa_mulai);
                $date2 = new DateTime($item->sewa_berakhir);
                $diff = $date1->diff($date2)->days;
                $lama_sewa = $diff == 0 ? 1 : $diff;
                $total = 'Rp' . number_format($item->alat->harga * ($lama_sewa * $item->banyak_unit), 0, ',', '.');

                $isEnum = $item->status instanceof \App\Enums\SewaStatus;
                $currentStatusColor = $isEnum ? $item->status->color() : 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                $displayStatus = $isEnum ? $item->status->value : ($item->status->value ?? $item->status ?? 'Menunggu');
@endphp

            <div
                class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:shadow-gray-200/40 dark:hover:shadow-none transition-all duration-300 group mb-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg shadow-green-100 dark:shadow-none">
                            <i class="fas fa-tools text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                    {{ $item->alat->nama }}
                                </h3>
                                <span
                                    class="px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-widest border border-amber-100 dark:border-amber-800/30">
                                    {{ $item->banyak_unit }} UNIT
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <i class="fas fa-user text-[10px]"></i>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest">{{ $item->user?->name ?? 'User Tidak Ditemukan' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            class="px-5 py-2 rounded-full border {{ $currentStatusColor }} text-[10px] font-bold uppercase tracking-widest shadow-sm">
                            {{ $displayStatus }}
                        </span>
                    </div>
                </div>

                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 p-6 bg-gray-50/50 dark:bg-gray-900/20 rounded-3xl border border-gray-50 dark:border-gray-700/50">
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Periode Mulai</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                            {{ \Carbon\Carbon::parse($item->sewa_mulai)->format('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Periode Berakhir</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                            {{ \Carbon\Carbon::parse($item->sewa_berakhir)->format('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Durasi Sewa</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $lama_sewa }}
                            HARI</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Estimasi Biaya</p>
                        <p class="text-sm font-bold text-green-600 dark:text-emerald-400 uppercase tracking-tight">{{ $total }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="openDetailModal('modal-detail-{{ $loop->index }}')"
                        class="px-6 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-emerald-900/20 hover:text-green-600 dark:hover:text-emerald-400 font-bold text-[10px] uppercase tracking-widest transition-all duration-300 border border-transparent hover:border-green-100 dark:hover:border-green-800/30">
                        <i class="fas fa-eye mr-2 text-xs"></i>Detail
                    </button>
                    <a href="{{ route('admin.sewa-alat.edit', $item->id) }}"
                        class="px-6 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 font-bold text-[10px] uppercase tracking-widest transition-all duration-300 border border-transparent hover:border-blue-100 dark:hover:border-blue-800/30">
                        <i class="fas fa-edit mr-2 text-xs"></i>Edit
                    </a>
                    <button type="button" onclick="openModal('modal-delete-{{ $loop->index }}')"
                        class="px-6 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 font-bold text-[10px] uppercase tracking-widest transition-all duration-300 border border-transparent hover:border-red-100 dark:hover:border-red-800/30">
                        <i class="fas fa-trash mr-2 text-xs"></i>Hapus
                    </button>
                </div>

                <!-- Detail Modal -->
                <div id="modal-detail-{{ $loop->index }}"
                    class="hidden fixed inset-0 z-50 overflow-auto bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-[2.5rem] max-w-2xl w-full shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden animate-in fade-in zoom-in duration-300">
                        <!-- Header -->
                        <div
                            class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 dark:text-emerald-400">
                                    <i class="fas fa-file-invoice text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Detail
                                        Permohonan</h3>
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">ID:
                                        #{{ substr($item->id, 0, 8) }}</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors shadow-sm border border-gray-100 dark:border-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="p-8 space-y-8 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            <!-- Alat Information -->
                            <div class="relative pl-6 border-l-2 border-green-500/30">
                                <h4
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Informasi Peralatan
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Nama
                                            Alat</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->alat->nama }}</p>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Unit &
                                            Harga</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->banyak_unit }} UNIT |
                                            Rp{{ number_format($item->alat->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rental Period -->
                            <div class="relative pl-6 border-l-2 border-blue-500/30">
                                <h4
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    Periode Sewa
                                </h4>
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700">
                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                Mulai</p>
                                            <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                {{ \Carbon\Carbon::parse($item->sewa_mulai)->format('d M Y') }}</p>
                                        </div>
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600">
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                Berakhir</p>
                                            <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                {{ \Carbon\Carbon::parse($item->sewa_berakhir)->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & User Info -->
                            <div class="relative pl-6 border-l-2 border-purple-500/30">
                                <h4
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    Informasi Pemohon
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div
                                        class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Nama
                                            Kontak</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->nama }}</p>
                                    </div>
                                    <div
                                        class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">No
                                            WhatsApp</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->no_whatsapp }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            @if($item->keterangan)
                                <div class="relative pl-6 border-l-2 border-blue-500/30">
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Detail Layanan
                                    </h4>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 text-left">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-2 text-left">
                                            Keterangan / Keperluan
                                        </p>
                                        <p
                                            class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed selection:bg-transparent text-left w-full">{{ $item->keterangan }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Documents -->
                            @if($item->surat_permohonan || $item->ktp)
                                <div class="relative pl-6 border-l-2 border-red-500/30">
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Berkas Pendukung
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @if($item->surat_permohonan)
                                            <a href="{{ route('admin.sewa-alat.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                class="flex items-center gap-3 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-emerald-900/20 transition-all duration-300 group/link">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600">
                                                    <i class="fas fa-file-pdf"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">
                                                        Surat Permohonan</p>
                                                    <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download
                                                        PDF</p>
                                                </div>
                                            </a>
                                        @endif
                                        @if($item->ktp)
                                            <a href="{{ route('admin.sewa-alat.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}"
                                                class="flex items-center gap-3 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300 group/link">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600">
                                                    <i class="fas fa-id-card"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">
                                                        Identitas (KTP)</p>
                                                    <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download
                                                        Image</p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Timestamp -->
                            <div class="relative pl-6 border-l-2 border-slate-500/30">
                                <h4
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    Waktu Pengajuan
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Dibuat
                                        </p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y H:i') }} WIB
                                        </p>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                            Terakhir Diperbarui</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ \Carbon\Carbon::parse($item->updated_at)->format('d F Y H:i') }} WIB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="p-8 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700 flex flex-wrap gap-3">
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="flex-1 px-8 py-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-sm">
                                Tutup
                            </button>
                            <a href="{{ route('admin.sewa-alat.edit', $item->id) }}"
                                class="flex-1 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-blue-200 dark:shadow-none text-center">
                                Edit Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div id="modal-delete-{{ $loop->index }}"
                    class="hidden fixed inset-0 z-50 overflow-auto bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-[2.5rem] max-w-sm w-full shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300 border border-gray-100 dark:border-gray-700">
                        <!-- Icon -->
                        <div class="flex justify-center pt-8">
                            <div
                                class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-600">
                                <i class="fas fa-trash-alt text-2xl"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="px-8 pt-6 pb-8 text-center">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-2">Hapus
                                Data?</h3>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest leading-relaxed">
                                Permohonan sewa <span class="text-gray-900 dark:text-white">{{ $item->alat->nama }}</span>
                                akan dihapus secara permanen.
                            </p>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex gap-3 p-6 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700">
                            <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                                class="flex-1 px-6 py-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 font-bold text-[10px] uppercase tracking-widest transition-all">
                                Batal
                            </button>
                            <button type="button" id="btn-delete-{{ $loop->index }}"
                                onclick="deleteRecord('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                class="flex-1 px-6 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-red-200 dark:shadow-none">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        @endforeach
    @endif
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openDetailModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function deleteRecord(recordId, modalId) {
        // Get the button element based on modal ID
        const buttonId = modalId.replace('modal-delete-', 'btn-delete-');
        const deleteBtn = document.getElementById(buttonId);

        // Store original text and disable button
        const originalText = deleteBtn.textContent;
        deleteBtn.textContent = 'Menghapus...';
        deleteBtn.disabled = true;

        let csrfToken = null;
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            csrfToken = metaTag.getAttribute('content');
        }

        if (!csrfToken) {
            const tokenInput = document.querySelector('input[name="_token"]');
            if (tokenInput) {
                csrfToken = tokenInput.value;
            }
        }

        if (!csrfToken) {
            alert('CSRF token tidak ditemukan');
            deleteBtn.textContent = originalText;
            deleteBtn.disabled = false;
            return;
        }

        const url = '/admin/sewa-alat/' + recordId;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                return response.json().then(data => {
                    return { status: response.status, ok: response.ok, data: data };
                });
            })
            .then(result => {
                if (result.ok) {
                    closeModal(modalId);
                    window.location.href = window.location.href;
                } else {
                    const errorMsg = result.data && result.data.message ? String(result.data.message) : 'Gagal menghapus permohonan';
                    alert(errorMsg);
                }
            })
            .catch(error => {
                const errorMsg = error && error.message ? String(error.message) : 'Terjadi kesalahan';
                alert(errorMsg);
            });
    }
</script>
</div>