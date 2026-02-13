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
                            <th class="p-3 text-left">Tanggal</th>
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
                                    <button type="button" onclick="deleteRecord('{{ $item->id }}')"
                                        class="text-red-600 dark:text-red-400 hover:underline">
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 z-30 overflow-auto bg-black bg-opacity-50">
                                <div class="w-full max-w-2xl p-6 mx-auto mt-20 mb-10 text-left bg-white rounded-lg shadow-lg dark:bg-slate-800">
                                    <div class="flex items-center justify-between mb-5">
                                        <h5 class="mr-3 font-bold">Detail Permohonan Asuransi</h5>
                                        <button type="button" class="cursor-pointer" onclick="closeModal('modal-{{ $loop->index }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="modal-content space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Nama Lengkap</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->nama_user ?? '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">No WhatsApp</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->no_whatsapp ?? '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Nama Instansi</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->perusahaan ?? '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Lokasi Kejadian</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->lokasi ?? '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tanggal Kejadian</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Latitude</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->latitude ?? '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Longitude</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->longitude ?? '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Status</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ $item->status ?? '-' }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tanggal Permohonan</dt>
                                                <dd class="text-slate-900 dark:text-slate-100 font-semibold">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</dd>
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">Surat Permohonan</dt>
                                                @if($item->surat_permohonan)
                                                    <dd>
                                                        <a href="{{ route('permohonan-asuransi.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-semibold text-sm transition">
                                                            <i class="fas fa-download"></i>Download
                                                        </a>
                                                    </dd>
                                                @else
                                                    <dd class="text-slate-900 dark:text-slate-100 font-semibold">-</dd>
                                                @endif
                                            </div>

                                            <div>
                                                <dt class="text-sm font-semibold text-slate-600 dark:text-slate-400">KTP</dt>
                                                @if($item->ktp)
                                                    <dd>
                                                        <a href="{{ route('permohonan-asuransi.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}"
                                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold text-sm transition">
                                                            <i class="fas fa-download"></i>Download
                                                        </a>
                                                    </dd>
                                                @else
                                                    <dd class="text-slate-900 dark:text-slate-100 font-semibold">-</dd>
                                                @endif
                                            </div>
                                        </div>

                                        <button type="button" onclick="closeModal('modal-{{ $loop->index }}')"
                                            class="w-full p-3 mt-5 text-center text-white bg-slate-400 rounded hover:bg-slate-500">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
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
