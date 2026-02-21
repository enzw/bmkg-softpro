<div class="col-span-2 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
    <div class="flex flex-col h-full p-6 text-gray-900 dark:text-gray-100">
        <h2 class="flex items-center mb-4 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Permohonan Anda
        </h2>

        @if ($permohonan->isEmpty())
            <div class="grid flex-1 place-content-center">
                <img src="{{ asset('images/alat-tidak-tersedia.svg') }}" alt="" width="200">
                <p>Belum ada permohonan</p>
            </div>
        @else
            <div class="w-full -mr-6 overflow-x-auto">
                <table class="w-full overflow-hidden rounded table-auto text-slate-600 dark:text-slate-400">
                    <thead class="border-b bg-slate-100 dark:bg-slate-900 border-b-slate-300 dark:border-b-slate-500">
                        <tr>
                            <th class="p-3 text-left">Aplikasi</th>
                            <th class="p-3 text-left">Instansi</th>
                            <th class="p-3 text-left">Detail</th>
                            <th class="p-3 text-left">Tarif</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permohonan as $item)
                            <tr class="transition duration-200 border-b border-b-slate-300 dark:border-b-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700">
                                <td class="p-3 align-top max-w-[150px]">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Asuransi
                                    </span>
                                </td>
                                <td class="p-3 align-top max-w-[150px]">
                                    <span class="text-sm">{{ $item->perusahaan }}</span>
                                </td>
                                <td class="p-3 align-top max-w-[150px]">
                                    <button type="button" onclick="openModal('modal-{{ $loop->index }}')"
                                        class="text-blue-600 dark:text-blue-400 hover:underline">
                                        Lihat Detail
                                    </button>
                                </td>
                                <td class="p-3 align-top max-w-[150px]">
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp 185.000</span>
                                </td>
                                <td class="p-3 font-bold align-top dark:text-white">
                                    @php
                                        // Handle enum status
                                        $statusValue = $item->status instanceof \App\Enums\Status ? $item->status->value : (string)$item->status;
                                        $statusLabel = $item->status instanceof \App\Enums\Status ? $item->status->label() : $statusValue;
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($statusValue === 'Menunggu') text-yellow-600
                                        @elseif($statusValue === 'Diproses') text-blue-600
                                        @elseif($statusValue === 'Ditolak') text-red-600
                                        @elseif($statusValue === 'Selesai') text-green-600
                                        @endif">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <button type="button" onclick="openDeleteModal('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                        class="text-red-600 dark:text-red-400 hover:underline">
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden">
                                    <!-- Modal Header with Gradient -->
                                    <div class="bg-gradient-to-r from-red-600 to-red-700 dark:from-red-700 dark:to-red-800 p-6 text-white">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                                                    <i class="fas fa-shield-alt text-2xl text-white"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-semibold opacity-90">Detail Permohonan</p>
                                                    <h3 class="text-lg font-bold">{{ e($item->perusahaan ?? '-') }}</h3>
                                                </div>
                                            </div>
                                            <button type="button" onclick="closeModal('modal-{{ $loop->index }}')" class="text-white/70 hover:text-white transition">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Modal Content -->
                                    <div class="p-6 space-y-6 max-h-[calc(100vh-240px)] overflow-y-auto">
                                        <!-- Status Badge -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Saat Ini</span>
                                            <span class="px-3 py-1 rounded-full font-semibold text-sm border {{ $statusColor = match($statusValue) {
                                                'Menunggu' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
                                                'Diproses' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                                'Ditolak' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800',
                                                'Selesai' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800',
                                                default => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                                            } }}">{{ $statusLabel }}</span>
                                        </div>

                                        <!-- Informasi Peudiklaim -->
                                        <div class="space-y-3">
                                            <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Informasi Pemohon</h4>
                                            <div class="space-y-2">
                                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Nama Lengkap</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->nama_user ?? '-' }}</p>
                                                </div>
                                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">No WhatsApp</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Detail Klaim -->
                                        <div class="space-y-3">
                                            <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Detail Klaim</h4>
                                            <div class="space-y-2">
                                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Tanggal Kejadian</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</p>
                                                </div>
                                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Lokasi Kejadian</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->lokasi ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Koordinat -->
                                        <div class="space-y-3">
                                            <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Koordinat Lokasi</h4>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Latitude</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->latitude ?? '-' }}</p>
                                                </div>
                                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Longitude</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->longitude ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                        <button type="button" onclick="closeModal('modal-{{ $loop->index }}')"
                                            class="w-full px-4 py-3 rounded-lg bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold transition shadow-lg">
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
                                        <p class="text-gray-600 dark:text-gray-400 mt-2">Yakin ingin menghapus permohonan asuransi dari <strong>{{ $item->perusahaan }}</strong>?</p>
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
                </table>
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
    
    fetch(`/layanan/permohonan-asuransi/${recordId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Delete response status:', response.status);
        
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
            window.location.reload();
        } else {
            return response.json().then(data => {
                console.error('Delete error response:',data);
                throw new Error(data.message || 'Gagal menghapus permohonan');
            });
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Error: ' + error.message);
        
        // Reset button state on error
        if (buttonElement) {
            buttonElement.textContent = originalText;
            buttonElement.disabled = false;
            buttonElement.classList.remove('opacity-70', 'cursor-not-allowed');
        }
    });
}
</script>
