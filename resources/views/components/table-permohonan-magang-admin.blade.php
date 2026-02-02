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
                    <th class="p-3 text-left">Jenis Layanan</th>
                    <th class="p-3 text-left">Nama Lengkap</th>
                    <th class="p-3 text-left">No WhatsApp</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permohonan as $item)
                <tr class="transition duration-200 border-b border-b-slate-300 dark:border-b-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700">
                    <td class="p-3 align-top">
                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full break-words
                            @if($item->jenis_layanan === 'Magang') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200
                            @elseif($item->jenis_layanan === 'Layanan Klaim Asuransi') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($item->jenis_layanan === 'Layanan Data') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($item->jenis_layanan === 'Layanan Peta Sebaran') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($item->jenis_layanan === 'Layanan Survey') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @elseif($item->jenis_layanan === 'Layanan Konsultasi') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                            @endif">
                            {{ $item->jenis_layanan }}
                        </span>
                    </td>

                    <!-- Detail Modal -->
                    <div id="detail-modal-{{ $loop->index }}" class="hidden fixed inset-0 z-30 overflow-auto bg-black bg-opacity-50">
                        <div class="w-full max-w-2xl p-6 mx-auto mt-10 mb-10 text-left bg-white rounded-lg shadow-lg dark:bg-slate-800">
                            <div class="flex items-center justify-between mb-5">
                                <h5 class="mr-3 font-bold text-lg">Detail Permohonan</h5>
                                <button type="button" class="z-50 cursor-pointer" onclick="closeDetailModal('detail-modal-{{ $loop->index }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="modal-content">
                                <dl class="grid grid-cols-2 gap-y-4 mb-5">
                                    <div>
                                        <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Jenis Layanan</dt>
                                        <dd class="text-slate-900 dark:text-slate-100">{{ $item->jenis_layanan }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Status</dt>
                                        <dd>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($item->status === 'Menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($item->status === 'Diproses') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($item->status === 'Ditolak') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($item->status === 'Selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @endif">
                                                {{ $item->status }}
                                            </span>
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Nama Lengkap</dt>
                                        <dd class="text-slate-900 dark:text-slate-100">{{ $item->nama_lengkap ?? '-' }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">No WhatsApp</dt>
                                        <dd class="text-slate-900 dark:text-slate-100">{{ $item->no_whatsapp ?? '-' }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Email</dt>
                                        <dd class="text-slate-900 dark:text-slate-100">{{ $item->email ?? '-' }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tanggal Permohonan</dt>
                                        <dd class="text-slate-900 dark:text-slate-100">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</dd>
                                    </div>

                                    @if($item->jenis_layanan === 'Magang')
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Universitas</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->universitas ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Fakultas</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->fakultas ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Program Studi</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->prodi ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tanggal Mulai</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tanggal Selesai</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}</dd>
                                        </div>
                                    @elseif($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Nama Perusahaan/Instansi</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->perusahaan ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tanggal Kejadian</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Lokasi Kejadian</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->lokasi ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Koordinat (Lat, Long)</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->latitude ?? '-' }}, {{ $item->longitude ?? '-' }}</dd>
                                        </div>
                                        <div class="col-span-2">
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Deskripsi Kejadian</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->kejadian ?? '-' }}</dd>
                                        </div>
                                    @elseif($item->jenis_layanan === 'Layanan Data')
                                        <div class="col-span-2">
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Deskripsi Data</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->keterangan ?? '-' }}</dd>
                                        </div>
                                    @elseif($item->jenis_layanan === 'Layanan Peta Sebaran')
                                        <div class="col-span-2">
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Deskripsi Pemetaan</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->keterangan ?? '-' }}</dd>
                                        </div>
                                    @elseif($item->jenis_layanan === 'Layanan Survey')
                                        <div class="col-span-2">
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Deskripsi Survey</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->keterangan ?? '-' }}</dd>
                                        </div>
                                    @elseif($item->jenis_layanan === 'Layanan Konsultasi')
                                        <div class="col-span-2">
                                            <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Topik Konsultasi</dt>
                                            <dd class="text-slate-900 dark:text-slate-100">{{ $item->topik ?? '-' }}</dd>
                                        </div>
                                    @endif
                                </dl>

                                <div class="flex gap-3 w-full">
                                    <button type="button" class="flex-1 p-3 rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600" onclick="closeDetailModal('detail-modal-{{ $loop->index }}')">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <td class="p-3 align-top max-w-[150px]">
                        {{ $item->nama_lengkap }}
                    </td>
                    <td class="p-3 align-top">
                        {{ $item->no_whatsapp }}
                    </td>
                    <td class="p-3 align-top">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
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
                            <button type="button" onclick="openDetailModal('detail-modal-{{ $loop->index }}')"
                                class="text-green-600 dark:text-green-400 hover:underline">
                                Detail
                            </button>
                            <a href="{{ route('admin.pelayanan-jasa.edit', ['pelayanan_jasa' => $item]) }}"
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
                                <dt class="text-sm text-slate-500">Jenis Layanan</dt>
                                <dd>{{ $item->jenis_layanan }}</dd>

                                <dt class="text-sm text-slate-500">Nama Lengkap</dt>
                                <dd>{{ $item->nama_lengkap }}</dd>

                                <dt class="text-sm text-slate-500">No WhatsApp</dt>
                                <dd>{{ $item->no_whatsapp }}</dd>

                                <dt class="text-sm text-slate-500">Email</dt>
                                <dd>{{ $item->email ?? '-' }}</dd>

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

function openDetailModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeDetailModal(id) {
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
    fetch(`/admin/pelayanan-jasa/${recordId}`, {
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
</div>