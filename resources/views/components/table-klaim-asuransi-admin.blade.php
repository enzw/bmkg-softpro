<div class="flex flex-col h-full text-gray-900 dark:text-gray-100">
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
                    <th class="p-3 text-left">Jenis Kunjungan</th>
                    <th class="p-3 text-left">Instansi</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Nama Lengkap</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permohonan as $item)
                <tr class="transition duration-200 border-b border-b-slate-300 dark:border-b-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700">
                    <td class="p-3 align-top max-w-[150px]">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $item->kejadian }}
                        </span>
                    </td>
                    <td class="p-3 align-top max-w-[150px]">
                        <span class="text-sm">{{ $item->perusahaan }}</span>
                    </td>
                    <td class="p-3 align-top">
                        {{ $item->tanggal }}
                    </td>
                    <td class="p-3 align-top max-w-[150px]">
                        {{ $item->latitude }}
                    </td>
                    <td class="p-3 font-bold align-top dark:text-white">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($item->status === 'Menunggu') text-yellow-600
                            @elseif($item->status === 'Diproses') text-blue-600
                            @elseif($item->status === 'Ditolak') text-red-600
                            @elseif($item->status === 'Selesai') text-green-600
                            @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="p-3 text-center">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.permohonan-kunjungan.edit', ['permohonan_kunjungan' => $item]) }}"
                                class="text-blue-600 dark:text-blue-400 hover:underline">
                                Edit
                            </a>
                            <button type="button" onclick="openModal('modal-{{ $loop->index }}')"
                                class="text-red-600 dark:text-red-400 hover:underline">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Modal Detail & Delete -->
                <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 z-30 overflow-auto bg-black bg-opacity-50">
                    <div class="w-full max-w-sm p-6 mx-auto mt-20 mb-10 text-left bg-white rounded-lg shadow-lg dark:bg-slate-800">
                        <div class="flex items-center justify-between mb-5">
                            <h5 class="mr-3 font-bold max-w-none">Batalkan Permohonan?</h5>
                            <button type="button" class="z-50 cursor-pointer" onclick="closeModal('modal-{{ $loop->index }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="modal-content">
                            <dl class="grid grid-cols-2 gap-y-3 mb-5">
                                <dt class="text-sm text-slate-500">Jenis Kunjungan</dt>
                                <dd>{{ $item->kejadian }}</dd>

                                <dt class="text-sm text-slate-500">Nama Instansi</dt>
                                <dd>{{ $item->perusahaan }}</dd>

                                <dt class="text-sm text-slate-500">Tanggal Kunjungan</dt>
                                <dd>{{ $item->tanggal }}</dd>

                                <dt class="text-sm text-slate-500">Nama Lengkap</dt>
                                <dd>{{ $item->latitude }}</dd>

                                <dt class="text-sm text-slate-500">Nomor WhatsApp</dt>
                                <dd>{{ $item->longitude }}</dd>

                                <dt class="text-sm text-slate-500">Jumlah Rombongan</dt>
                                <dd>{{ $item->lokasi }}</dd>

                                <dt class="text-sm text-slate-500">Status</dt>
                                <dd>{{ $item->status }}</dd>

                                <dt class="text-sm text-slate-500">Tanggal Permohonan</dt>
                                <dd>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</dd>
                            </dl>

                            <p class="text-sm text-red-600 dark:text-red-400 mb-4">Anda yakin akan menghapus permohonan ini?</p>

                            <div class="flex gap-3 w-full">
                                <button type="button" class="flex-1 p-3 rounded" onclick="closeModal('modal-{{ $loop->index }}')">
                                    Batal
                                </button>
                                <button type="button" class="flex-1 p-3 text-center text-white bg-red-600 rounded hover:bg-red-700" onclick="deleteRecord({{ $item->id }})">
                                    Ya, Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function deleteRecord(recordId) {
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
    fetch(`/admin/permohonan-kunjungan/${recordId}`, {
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
                    <button type="button" onclick="openDeleteModal('{{ route('admin.permohonan-kunjungan.destroy', ['permohonan_kunjungan' => $item]) }}', 'Hapus permohonan ini?')"
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