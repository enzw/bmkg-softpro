<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="p-8 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                <i class="fas fa-list text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Permohonan</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 ml-13">Daftar permohonan kunjungan Anda</p>
    </div>

    <!-- Content -->
    <div class="p-8">
        @if ($permohonan->isEmpty())
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                    <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Permohonan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Isi formulir di samping untuk membuat permohonan kunjungan</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($permohonan as $item)
                    @php
                        $statusLabel = match($item->status) {
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'completed' => 'Selesai',
                            default => ucfirst($item->status)
                        };
                        
                        $statusColor = [
                            'pending' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-300',
                            'approved' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                            'rejected' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
                            'completed' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300'
                        ];
                        $currentStatusColor = $statusColor[$item->status] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                    @endphp
                    
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-gray-200 dark:hover:border-gray-600 transition group">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-3 h-3 rounded-full bg-gradient-to-r from-green-500 to-green-600"></div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->nama_instansi }}</h3>
                                    <span class="text-sm px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                        {{ $item->jenis_kunjungan }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-user mr-2"></i>
                                    {{ $item->nama_lengkap }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Rombongan</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $item->jumlah_rombongan }} orang</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">No WhatsApp</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Rencana</p>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ Str::limit($item->rencana_kunjungan, 20) }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="openModal('modal-{{ $loop->index }}')"
                                class="flex-1 px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                <i class="fas fa-eye mr-2"></i>Detail
                            </button>
                            <button type="button" onclick="confirmDelete('{{ $item->id }}', null)"
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
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nama Instansi</p>
                                        <p class="text-gray-900 dark:text-white font-semibold">{{ $item->nama_instansi }}</p>
                                    </div>

                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                                        <p class="text-gray-900 dark:text-white font-semibold">{{ $item->nama_lengkap }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Jenis Kunjungan</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ $item->jenis_kunjungan }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Rombongan</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ $item->jumlah_rombongan }} orang</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">No WhatsApp</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ $item->no_whatsapp }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Status</p>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $currentStatusColor }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Rencana Kunjungan</p>
                                        <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg text-sm">{{ $item->rencana_kunjungan }}</p>
                                    </div>

                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tanggal Permohonan</p>
                                        <p class="text-gray-900 dark:text-white font-semibold">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</p>
                                    </div>

                                    @if($item->surat_permohonan || $item->ktp)
                                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                            @if($item->surat_permohonan)
                                                <a href="{{ route('permohonan-kunjungan.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                    class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                                                    <i class="fas fa-file-pdf mr-2"></i>Download Surat Permohonan
                                                </a>
                                            @endif
                                            @if($item->ktp)
                                                <a href="{{ route('permohonan-kunjungan.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}"
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
                                <p class="text-gray-600 dark:text-gray-400 mt-2">Yakin ingin menghapus permohonan untuk <strong>{{ $item->nama_instansi }}</strong>?</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>

                            <div class="flex gap-3 p-6">
                                <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                                    Batal
                                </button>
                                <button type="button" id="delete-btn-{{ $loop->index }}" onclick="confirmDelete('{{ $item->id }}', 'modal-delete-{{ $loop->index }}', this)"
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

function confirmDelete(recordId, modalId, buttonElement) {
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
        alert('Error: CSRF token tidak ditemukan');
        return;
    }
    
    // Capture original button state for reset on error
    const originalText = buttonElement ? buttonElement.textContent : null;
    
    // Update button state
    if (buttonElement) {
        buttonElement.textContent = 'Menghapus...';
        buttonElement.disabled = true;
        buttonElement.classList.add('opacity-70', 'cursor-not-allowed');
    }
    
    // Close the delete confirmation modal
    if (modalId) {
        closeModal(modalId);
    }
    
    console.log('Starting delete request for record:', recordId);
    
    fetch(`/layanan/permohonan-kunjungan/${recordId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Delete response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (response.status === 403) {
            alert('Error: Anda tidak memiliki izin untuk menghapus permohonan ini');
            return response.json().then(data => {
                console.log('Auth error details:', data);
            });
        }
        
        if (response.status === 404) {
            alert('Error: Permohonan tidak ditemukan');
            return;
        }
        
        if (response.ok) {
            console.log('Delete successful, reloading page...');
            // Reload immediately
            window.location.reload();
        } else {
            // Try to parse as JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    console.log('Delete error response:', data);
                    throw new Error(data.message || `HTTP ${response.status}: Gagal menghapus permohonan`);
                });
            } else {
                throw new Error(`HTTP ${response.status}: Gagal menghapus permohonan`);
            }
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Error: ' + error.message);
        // Reset button state on error
        if (buttonElement && originalText) {
            buttonElement.textContent = originalText;
            buttonElement.disabled = false;
            buttonElement.classList.remove('opacity-70', 'cursor-not-allowed');
        }
    });
}
</script>
