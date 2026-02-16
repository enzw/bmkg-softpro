<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="p-8 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                <i class="fas fa-list text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Permohonan</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 ml-13">Daftar permohonan sewa alat Anda</p>
    </div>

    <!-- Content -->
    <div class="p-8">
        @if ($permohonan->isEmpty())
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                    <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Permohonan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Isi formulir di samping untuk membuat permohonan sewa alat</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($permohonan as $item)
                    @php
                        $date1 = new DateTime($item->sewa_mulai);
                        $date2 = new DateTime($item->sewa_berakhir);
                        $diff = $date1->diff($date2)->days;
                        $lama_sewa = $diff == 0 ? 1 : $diff;
                        $total = 'Rp' . number_format($item->alat->harga * ($lama_sewa * $item->banyak_unit), 0, ',', '.');
                        
                        $statusColor = [
                            'Menunggu' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-300',
                            'Diproses' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300',
                            'Ditolak' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
                            'Selesai' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                            'Belum Lunas' => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800/50 text-orange-700 dark:text-orange-300'
                        ];
                        // Convert enum to string value for array access
                        $statusValue = $item->status instanceof \App\Enums\SewaStatus ? $item->status->value : (string)$item->status;
                        $currentStatusColor = $statusColor[$statusValue] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                    @endphp
                    
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-gray-200 dark:hover:border-gray-600 transition group">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-3 h-3 rounded-full bg-gradient-to-r from-green-500 to-green-600"></div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ e($item->alat->nama) }}</h3>
                                    <span class="text-sm px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                        {{ e($item->banyak_unit) }} unit
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                                    {{ e($item->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Mulai</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->sewa_mulai)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Berakhir</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->sewa_berakhir)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Durasi</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $lama_sewa }} hari</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Total Biaya</p>
                                <p class="font-semibold text-green-600 dark:text-green-400">{{ $total }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="openModal('modal-{{ $loop->index }}')"
                                class="flex-1 px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                <i class="fas fa-eye mr-2"></i>Detail
                            </button>
                            <button type="button" onclick="openDeleteModal('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                class="flex-1 px-4 py-2 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 font-semibold text-sm transition">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </div>
                    </div>

                    <!-- Detail Modal -->
                    <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                        <i class="fas fa-info-circle text-white"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Permohonan</h3>
                                </div>
                                <button type="button" onclick="closeModal('modal-{{ $loop->index }}')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Content -->
                            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nama Alat</p>
                                        <p class="text-gray-900 dark:text-white font-semibold">{{ e($item->alat->nama ?? '-') }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nama Penyewa</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ e($item->nama) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">No WhatsApp</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ e($item->no_whatsapp) }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Mulai Sewa</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ \Carbon\Carbon::parse($item->sewa_mulai)->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Akhir Sewa</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ \Carbon\Carbon::parse($item->sewa_berakhir)->format('d/m/Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Unit</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ $item->banyak_unit }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total</p>
                                            <p class="text-green-600 dark:text-green-400 font-bold">{{ $total }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Status</p>
                                        <p class="text-gray-900 dark:text-white font-semibold">{{ e($item->status) }}</p>
                                    </div>

                                    @if($item->keterangan)
                                        <div class="relative pl-6 border-l-2 border-blue-500/30">
                                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                Detail Layanan
                                            </h4>
                                            <div class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 text-left">
                                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-2 text-left">
                                                    Keterangan / Keperluan
                                                </p>
                                                <p class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed selection:bg-transparent text-left w-full">{{ $item->keterangan }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($item->expedisi && $item->resi)
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Pengiriman</p>
                                            <p class="text-gray-900 dark:text-white">{{ e($item->expedisi) }}</p>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm">No. Resi: {{ e($item->resi) }}</p>
                                        </div>
                                    @endif

                                    @if($item->surat_permohonan || $item->ktp)
                                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                            @if($item->surat_permohonan)
                                                <a href="{{ route('sewa-alat.download-file', ['sewa_alat' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                    class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                                                    <i class="fas fa-file-pdf mr-2"></i>Download Surat Permohonan
                                                </a>
                                            @endif
                                            @if($item->ktp)
                                                <a href="{{ route('sewa-alat.download-file', ['sewa_alat' => $item->id, 'fileName' => basename($item->ktp)]) }}"
                                                    class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                                    <i class="fas fa-id-card mr-2"></i>Download KTP/Identitas
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" onclick="closeModal('modal-{{ $loop->index }}')"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div id="modal-delete-{{ $loop->index }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mt-6 rounded-full bg-red-100 dark:bg-red-900/20">
                                <i class="fas fa-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                            </div>
                            
                            <div class="mt-4 text-center px-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Permohonan?</h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">Yakin ingin menghapus permohonan untuk <strong>{{ e($item->alat->nama) }}</strong>?</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>

                            <div class="flex gap-3 p-6">
                                <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                                    Batal
                                </button>
                                <button type="button" id="btn-delete-{{ $loop->index }}" onclick="deleteRecord('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white font-semibold transition">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
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

function openDeleteModal(recordId, modalId) {
    openModal(modalId);
    window.deleteRecordId = recordId;
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
    
    const url = '/layanan/sewa-alat/permohonan/' + recordId + '/hapus';
    
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
        console.error('Delete error:', error);
        alert('Error: ' + error.message);
    });
}
</script>