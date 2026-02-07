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
                
                $statusMap = [
                    'Belum Lunas' => 'Menunggu',
                    'Siap Diambil' => 'Diproses',
                    'Dibawa' => 'Diproses',
                    'Dikembalikan' => 'Selesai',
                ];
                $displayStatus = $statusMap[$item->status] ?? $item->status;
                
                $statusColor = [
                    'Menunggu' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-300',
                    'Diproses' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300',
                    'Ditolak' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
                    'Selesai' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                ];
                $currentStatusColor = $statusColor[$displayStatus] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
            @endphp
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md dark:hover:shadow-lg transition group">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-3 h-3 rounded-full bg-gradient-to-r from-green-500 to-green-600"></div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->alat->nama }}</h3>
                            <span class="text-sm px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                {{ $item->banyak_unit }} unit
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-user mr-2"></i>
                            {{ $item->user->name }}
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                            {{ $displayStatus }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 pt-4 border-t border-gray-200 dark:border-gray-700">
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
                    <button type="button" onclick="openDetailModal('modal-detail-{{ $loop->index }}')"
                        class="flex-1 px-4 py-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </button>
                    <a href="{{ route('admin.sewa-alat.edit', ['sewa_alat' => $item]) }}"
                        class="flex-1 px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <button type="button" onclick="openModal('modal-delete-{{ $loop->index }}')"
                        class="flex-1 px-4 py-2 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 font-semibold text-sm transition">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>

                <!-- Detail Modal -->
                <div id="modal-detail-{{ $loop->index }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
                        <!-- Header -->
                        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Permohonan</h3>
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
                            <!-- Alat Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-dolly text-green-600 dark:text-green-400"></i>
                                    Informasi Alat
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nama Alat:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->alat->nama }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Jumlah Unit:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->banyak_unit }} unit</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Harga per Unit:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">Rp{{ number_format($item->alat->harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Rental Period -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-calendar text-blue-600 dark:text-blue-400"></i>
                                    Periode Sewa
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Tanggal Mulai:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->sewa_mulai)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Tanggal Berakhir:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->sewa_berakhir)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Durasi Sewa:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $lama_sewa }} hari</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-phone text-green-600 dark:text-green-400"></i>
                                    Informasi Kontak
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->nama }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">No WhatsApp:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Cost -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-amber-600 dark:text-amber-400"></i>
                                    Status & Biaya
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $currentStatusColor }}">
                                            {{ $displayStatus }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Biaya:</span>
                                        <span class="font-semibold text-lg text-green-600 dark:text-green-400">{{ $total }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Information -->
                            @if($item->expedisi || $item->resi)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-truck text-orange-600 dark:text-orange-400"></i>
                                        Informasi Pengiriman
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                        @if($item->expedisi)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Jasa Pengiriman:</span>
                                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->expedisi }}</span>
                                            </div>
                                        @endif
                                        @if($item->resi)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">No. Resi:</span>
                                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->resi }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- User Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
                                    Informasi Pemohon
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->user->email }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($item->keterangan)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-sticky-note text-yellow-600 dark:text-yellow-400"></i>
                                        Keterangan
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                        <p class="text-gray-900 dark:text-white text-sm">{{ $item->keterangan }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Document -->
                            @if($item->surat_permohonan)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-file text-red-600 dark:text-red-400"></i>
                                        Surat Permohonan
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                        <a href="{{ route('admin.sewa-alat.download', ['sewa_alat' => $item]) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-semibold text-sm transition">
                                            <i class="fas fa-download mr-2"></i>Download Surat
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 p-6 flex gap-3">
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                                Tutup
                            </button>
                            <a href="{{ route('admin.sewa-alat.edit', ['sewa_alat' => $item]) }}"
                                class="flex-1 px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold transition">
                                <i class="fas fa-edit mr-2"></i>Edit Permohonan
                            </a>
                        </div>
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
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Yakin ingin menghapus permohonan untuk <strong>{{ $item->alat->nama }}</strong>?</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>

                    <div class="flex gap-3 p-6">
                        <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                            class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                            Batal
                        </button>
                        <button type="button" onclick="deleteRecord({{ $item->id }})"
                            class="flex-1 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white font-semibold transition">
                            Hapus
                        </button>
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

function deleteRecord(recordId) {
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
    
    fetch(`/admin/sewa-alat/${recordId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Gagal menghapus permohonan');
            });
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        console.error('Error:', error);
    });
}
</script>
</div>
