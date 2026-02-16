<div class="space-y-4">
    @if ($permohonan->isEmpty())
        <div class="flex flex-col items-center justify-center py-16">
            <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Permohonan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan buat klaim asuransi baru untuk memulai</p>
        </div>
    @else
        @foreach ($permohonan as $item)
            @php
                $statusColor = [
                    'Menunggu' => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800/50 text-orange-700 dark:text-orange-300',
                    'Diproses' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300',
                    'Ditolak' => 'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-800/50 text-rose-700 dark:text-rose-300',
                    'Selesai' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300',
                ];
                $statusValue = $item->status instanceof \App\Enums\Status ? $item->status->value : (string)$item->status;
                $currentStatusColor = $statusColor[$statusValue] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md dark:hover:shadow-lg transition group">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-3 h-3 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600"></div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->nama_user }}</h3>
                            <span class="text-sm px-3 py-1 rounded-full border bg-cyan-50 dark:bg-cyan-900/20 border-cyan-200 dark:border-cyan-800/50 text-cyan-700 dark:text-cyan-300">
                                Klaim Asuransi
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-building mr-2"></i>
                            {{ $item->perusahaan }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                            {{ $item->status }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Tanggal Kejadian</p>
                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">No WhatsApp</p>
                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $item->no_whatsapp ?? '-' }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Lokasi</p>
                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $item->lokasi ?? '-' }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Tarif</p>
                        <p class="font-semibold text-blue-600 dark:text-blue-400">Rp 185.000</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="openDetailModal('modal-detail-{{ $loop->index }}')"
                        class="flex-1 px-4 py-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </button>
                    <a href="{{ route('admin.klaim-asuransi.edit', $item->id) }}"
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
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Klaim Asuransi</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ID: #{{ $item->id }}</p>
                                </div>
                                <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')" 
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-times text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Company Section -->
                                <div class="pb-6 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nama Instansi</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $item->perusahaan }}</p>
                                </div>

                                <!-- Date and Location Section -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Tanggal Kejadian</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Lokasi Kejadian</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->lokasi }}</p>
                                    </div>
                                </div>

                                <!-- Other Details Section -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Nama Lengkap</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->nama_user ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">No WhatsApp</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Latitude</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->latitude ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Longitude</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->longitude ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->status }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Surat Permohonan</p>
                                        @if($item->surat_permohonan)
                                            <a href="{{ route('admin.klaim-asuransi.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-semibold text-sm transition w-full justify-center">
                                                <i class="fas fa-file-pdf mr-2"></i>Download Surat Permohonan
                                            </a>
                                        @else
                                            <p class="text-gray-500">-</p>
                                        @endif
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">KTP</p>
                                        @if($item->ktp)
                                            <a href="{{ route('admin.klaim-asuransi.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold text-sm transition w-full justify-center">
                                                <i class="fas fa-id-card mr-2"></i>Download KTP
                                            </a>
                                        @else
                                            <p class="text-gray-500">-</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 p-6 flex gap-3">
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="flex-1 px-4 py-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold transition">
                                Tutup
                            </button>
                            <a href="{{ route('admin.klaim-asuransi.edit', $item->id) }}"
                                class="flex-1 px-4 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold transition text-center">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>


            @endforeach
        </div>
    @endif
</div>

<script>
function openDetailModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeDetailModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function deleteRecord(recordId, modalId) {
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
        return;
    }
    
    fetch(`/admin/klaim-asuransi/${recordId}`, {
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
            window.location.href = window.location.href;
        } else {
            const errorMsg = result.data && result.data.message ? String(result.data.message) : 'Gagal menghapus klaim asuransi';
            alert(errorMsg);
        }
    })
    .catch(error => {
        const errorMsg = error && error.message ? String(error.message) : 'Terjadi kesalahan';
        alert(errorMsg);
    });
}
</script>
</script>

    <!-- Modal -->
    <div class="fixed inset-0 z-30 overflow-auto bg-black bg-opacity-50" x-show="showModalPermohonan"
        x-transition.opacity x-cloak>

        <!-- Modal inner -->
        <div class="w-full max-w-sm p-6 mx-auto mt-20 mb-10 text-left bg-white rounded-lg shadow-lg dark:bg-slate-800"
            @click.away="showModalPermohonan = false" x-transition>
            <!-- Title / Close-->
            <div class="flex items-center justify-between mb-5">
                <h5 class="mr-3 font-bold max-w-none">Detail Permohonan</h5>

                <button type="button" class="z-50 cursor-pointer" @click="showModalPermohonan = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- content -->
            <div class="modal-content">
                <dl class="grid grid-cols-2 gap-y-3">
                    <dt class="text-sm text-slate-500">Rencana Kunjungan</dt>
                    <dd x-text="data.tanggal"></dd>

                    <dt class="text-sm text-slate-500">Phone Number</dt>
                    <dd x-text="data.lokasi"></dd>

                    <dt class="text-sm text-slate-500">Jumlah Rombongan</dt>
                    <dd x-text="data.jumlah_rombongan"></dd>

                    <dt class="text-sm text-slate-500">Jenis Kunjungan</dt>
                    <dd x-text="data.kejadian"></dd>

                    <dt class="text-sm text-slate-500">Status</dt>
                    <dd x-text="data.status"></dd>

                    <dt x-show="data.status !== `Menunggu`" class="text-sm text-slate-500">Surat Keterangan Klaim</dt>
                    <dd x-show="data.status !== `Menunggu`">
                        <a :href="download" class="font-bold text-green-500 hover:text-green-700">
                            Download
                            <i class="fa-solid fa-file-arrow-down"></i>
                        </a>
                    </dd>
                </dl>

                <div class="flex flex-col">
                    <a :href="edit"
                        class="w-full p-3 mt-5 text-center text-gray-600 uppercase border border-gray-600 rounded hover:bg-gray-500 hover:text-white ms-auto">Edit
                        Permohonan</a>
                    <button type="button" onclick="openDeleteModal('{{ route('admin.permohonan-kunjungan.destroy', $item->id) }}', 'Hapus permohonan ini?')"
                        class="w-full p-3 mt-5 text-center text-white uppercase bg-red-400 rounded hover:bg-red-500 ms-auto">Batalkan
                        Permohonan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->

    <!-- Modal Confirm Delete -->
    <div id="modal-delete-confirm" class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mt-6 rounded-full bg-red-100 dark:bg-red-900/20">
                <i class="fas fa-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
            </div>
            
            <div class="mt-4 text-center px-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Permohonan?</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-2" id="delete-message">Yakin ingin menghapus permohonan ini?</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <div class="flex gap-3 p-6">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="inline w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white font-semibold transition">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal Confirm Delete -->
</div>

<script>
function openDeleteModal(action, message) {
    document.getElementById('delete-form').action = action;
    document.getElementById('delete-message').textContent = message;
    document.getElementById('modal-delete-confirm').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('modal-delete-confirm').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>