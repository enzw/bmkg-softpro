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
                                    <button type="button" onclick="deleteRecord('{{ $item->id }}')"
                                        class="text-red-600 dark:text-red-400 hover:underline">
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full shadow-2xl">
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
                                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Perusahaan</p>
                                                <p class="text-gray-900 dark:text-white font-semibold">{{ $item->perusahaan ?? '-' }}</p>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $item->nama_user ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">No WhatsApp</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $item->no_whatsapp ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tanggal Kejadian</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Lokasi Kejadian</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $item->lokasi ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Latitude</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $item->latitude ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Longitude</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $item->longitude ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Status</p>
                                                <p class="text-gray-900 dark:text-white font-semibold">{{ $statusLabel }}</p>
                                            </div>
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
                                        <p class="text-gray-600 dark:text-gray-400 mt-2">Yakin ingin menghapus permohonan asuransi dari <strong>{{ $item->perusahaan }}</strong>?</p>
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
                </table>
            </div>
        @endif
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function deleteRecord(recordId) {
    if (confirm('Apakah Anda yakin ingin menghapus permohonan ini?')) {
        // Get CSRF token from meta tag or form input
        let csrfToken = null;
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            csrfToken = metaTag.getAttribute('content');
        }
        
        // Fallback: get CSRF from input hidden field
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
        
        // Use fetch API for DELETE request
        fetch(`/layanan/permohonan-kunjungan/${recordId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                // Reload page to show updated data
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
}
</script>
