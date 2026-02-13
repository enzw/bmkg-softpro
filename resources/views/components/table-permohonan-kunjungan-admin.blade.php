<div class="space-y-4">
    @if ($permohonan->isEmpty())
        <div class="flex flex-col items-center justify-center py-16">
            <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Permohonan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan buat permohonan kunjungan baru untuk memulai</p>
        </div>
    @else
        @foreach ($permohonan as $item)
            @php
                $statusColor = [
                    'pending' => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800/50 text-orange-700 dark:text-orange-300',
                    'approved' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300',
                    'rejected' => 'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-800/50 text-rose-700 dark:text-rose-300',
                    'completed' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300',
                ];
                $currentStatusColor = $statusColor[$item->status] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';

                $statusLabel = match ($item->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                    default => ucfirst($item->status)
                };
            @endphp

            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md dark:hover:shadow-lg transition group">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-3 h-3 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600"></div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->nama_lengkap }}</h3>
                            <span
                                class="text-sm px-3 py-1 rounded-full border bg-cyan-50 dark:bg-cyan-900/20 border-cyan-200 dark:border-cyan-800/50 text-cyan-700 dark:text-cyan-300">
                                {{ $item->jenis_kunjungan }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-building mr-2"></i>
                            {{ $item->nama_instansi }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">No WhatsApp</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Jam</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="openDetailModal('modal-detail-{{ $loop->index }}')"
                        class="flex-1 px-4 py-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </button>
                    <a href="{{ route('admin.permohonan-kunjungan.edit', $item->id) }}"
                        class="flex-1 px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <button type="button" onclick="openModal('modal-delete-{{ $loop->index }}')"
                        class="flex-1 px-4 py-2 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 font-semibold text-sm transition">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                    <!-- Delete Modal -->
                    <div id="modal-delete-{{ $loop->index }}"
                        class="hidden fixed inset-0 z-50 overflow-auto bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-3xl max-w-sm w-full shadow-2xl transform transition-all duration-300 scale-95 hover:scale-100">
                            <!-- Icon -->
                            <div class="flex justify-center pt-8">
                                <div
                                    class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                                    <i class="fas fa-trash text-2xl text-red-600 dark:text-red-400"></i>
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="px-8 pt-6 pb-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Hapus Permohonan?</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                    Permohonan <strong class="text-gray-900 dark:text-white">{{ $item->nama_lengkap }}</strong>
                                    akan dihapus secara permanen.
                                </p>
                            </div>
                            <!-- Divider -->
                            <div class="h-px bg-gray-100 dark:bg-gray-700"></div>
                            <!-- Actions -->
                            <div class="flex gap-3 p-6">
                                <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition-all duration-200 hover:shadow-md">
                                    Batal
                                </button>
                                <button type="button" id="btn-delete-{{ $loop->index }}"
                                    onclick="deleteRecord('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition-all duration-200 hover:shadow-md">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Modal -->
                <div id="modal-detail-{{ $loop->index }}"
                    class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
                        <!-- Header -->
                        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Permohonan Kunjungan</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ID: #{{ $item->id }}</p>
                                </div>
                                <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-times text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-6">
                            <!-- Jenis Kunjungan -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-building text-cyan-600 dark:text-cyan-400"></i>
                                    Informasi Kunjungan
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Jenis Kunjungan:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $item->jenis_kunjungan }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Instansi:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $item->nama_instansi }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Jumlah Rombongan:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->jumlah_rombongan }}
                                            orang</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                    Informasi Kontak
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nama Lengkap:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $item->nama_lengkap }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">No WhatsApp:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Visit Plan -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-calendar text-amber-600 dark:text-amber-400"></i>
                                    Rencana Kunjungan
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <p class="text-gray-900 dark:text-white text-sm whitespace-pre-wrap">
                                        {{ $item->rencana_kunjungan }}</p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-purple-600 dark:text-purple-400"></i>
                                    Status
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-sm font-semibold border {{ $currentStatusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                            <!-- Document -->
                            @if($item->surat_permohonan || $item->ktp)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-file text-red-600 dark:text-red-400"></i>
                                        Dokumen
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                        @if($item->surat_permohonan)
                                            <a href="{{ route('admin.permohonan-kunjungan.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-semibold text-sm transition w-full justify-center">
                                                <i class="fas fa-file-pdf mr-2"></i>Download Surat Permohonan
                                            </a>
                                        @endif
                                        @if($item->ktp)
                                            <a href="{{ route('admin.permohonan-kunjungan.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold text-sm transition w-full justify-center">
                                                <i class="fas fa-id-card mr-2"></i>Download KTP/Identitas
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Timestamp -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-600 dark:text-gray-400"></i>
                                    Waktu
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Diperbarui:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 p-6 flex gap-3">
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                                Tutup
                            </button>
                            <a href="{{ route('admin.permohonan-kunjungan.edit', $item->id) }}"
                                class="flex-1 px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold transition">
                                <i class="fas fa-edit mr-2"></i>Edit Permohonan
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        @endforeach
    @endif
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function openDetailModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeDetailModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function deleteRecord(recordId, modalId) {
        // Get the button element based on modal ID
        const buttonId = modalId.replace('modal-delete-', 'btn-delete-');
        const deleteBtn = document.getElementById(buttonId);
        
        let originalText = 'Hapus';
        if (deleteBtn) {
            originalText = deleteBtn.textContent;
            deleteBtn.textContent = 'Menghapus...';
            deleteBtn.disabled = true;
        }

        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            const tokenInput = document.querySelector('input[name="_token"]');
            if (tokenInput) csrfToken = tokenInput.value;
        }

        if (!csrfToken) {
            alert('CSRF token tidak ditemukan');
            if (deleteBtn) {
                deleteBtn.textContent = originalText;
                deleteBtn.disabled = false;
            }
            return;
        }

        fetch(`/admin/permohonan-kunjungan/${recordId}`, {
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
                    window.location.reload();
                } else {
                    const errorMsg = result.data && result.data.message ? String(result.data.message) : 'Gagal menghapus permohonan';
                    alert(errorMsg);
                    if (deleteBtn) {
                        deleteBtn.textContent = originalText;
                        deleteBtn.disabled = false;
                    }
                }
            })
            .catch(error => {
                const errorMsg = error && error.message ? String(error.message) : 'Terjadi kesalahan';
                alert(errorMsg);
                if (deleteBtn) {
                    deleteBtn.textContent = originalText;
                    deleteBtn.disabled = false;
                }
            });
    }
</script>