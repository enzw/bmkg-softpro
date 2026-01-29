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
                            <th class="p-3 text-left">Barang</th>
                            <th class="p-3 text-left">Detail</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permohonan as $item)
                            @php
                                $date1 = new DateTime($item->sewa_mulai);
                                $date2 = new DateTime($item->sewa_berakhir);
                                $diff = $date1->diff($date2)->days;
                                $lama_sewa = $diff == 0 ? 1 : $diff;
                                $total = 'Rp' . number_format($item->alat->harga * ($lama_sewa * $item->banyak_unit), 0, ',', '.');
                            @endphp
                            <tr class="transition duration-200 border-b border-b-slate-300 dark:border-b-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700">
                                <td class="p-3 align-top max-w-[150px]">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                        {{ $item->alat->nama }}
                                    </span>
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
                                        @elseif($item->status === 'Belum Lunas') text-yellow-600
                                        @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <button type="button" onclick="deleteRecord({{ $item->id }})"
                                        class="text-red-600 dark:text-red-400 hover:underline">
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 z-30 overflow-auto bg-black bg-opacity-50">
                                <div class="w-full max-w-xl p-6 mx-auto mt-20 mb-10 text-left bg-white rounded-lg shadow-lg dark:bg-slate-800">
                                    <div class="flex items-center justify-between mb-5">
                                        <h5 class="mr-3 font-bold">Detail Sewa Alat</h5>
                                        <button type="button" class="cursor-pointer" onclick="closeModal('modal-{{ $loop->index }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="modal-content space-y-3">
                                        <dl class="grid grid-cols-2 gap-y-3">
                                            <dt class="text-sm text-slate-500">Nama Alat</dt>
                                            <dd>{{ $item->alat->nama ?? '-' }}</dd>

                                            <dt class="text-sm text-slate-500">Tanggal Mulai Sewa</dt>
                                            <dd>{{ \Carbon\Carbon::parse($item->sewa_mulai)->format('d/m/Y') }}</dd>

                                            <dt class="text-sm text-slate-500">Tanggal Akhir Sewa</dt>
                                            <dd>{{ \Carbon\Carbon::parse($item->sewa_berakhir)->format('d/m/Y') }}</dd>

                                            <dt class="text-sm text-slate-500">Jumlah Unit</dt>
                                            <dd>{{ $item->banyak_unit }} unit</dd>

                                            <dt class="text-sm text-slate-500">Total Biaya</dt>
                                            <dd class="font-semibold">{{ $total }}</dd>

                                            <dt class="text-sm text-slate-500">Status</dt>
                                            <dd>{{ $item->status }}</dd>

                                            <dt class="text-sm text-slate-500">Tanggal Permohonan</dt>
                                            <dd>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</dd>

                                            @if($item->keterangan)
                                                <dt class="text-sm text-slate-500">Keterangan</dt>
                                                <dd class="col-span-2 p-2 bg-slate-100 dark:bg-slate-700 rounded">{{ $item->keterangan }}</dd>
                                            @endif

                                            @if($item->expedisi && $item->resi)
                                                <dt class="text-sm text-slate-500">Pengiriman</dt>
                                                <dd>
                                                    <p>{{ $item->expedisi }}</p>
                                                    <p class="text-xs text-slate-500">{{ $item->resi }}</p>
                                                </dd>
                                            @endif

                                            @if($item->surat_permohonan)
                                                <dt class="text-sm text-slate-500">Surat Permohonan</dt>
                                                <dd>
                                                    <a href="{{ route('sewa-alat.download-permohonan', ['sewa_alat' => $item->id]) }}"
                                                        class="text-blue-600 dark:text-blue-400 hover:underline">
                                                        Download Dokumen
                                                    </a>
                                                </dd>
                                            @endif
                                        </dl>

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
        fetch(`/layanan/sewa-alat/${recordId}`, {
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

