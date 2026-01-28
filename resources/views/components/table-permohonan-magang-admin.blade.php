<div x-data="{
    showModalPermohonan: false,
    expanded: false,
    showModalBatalPermohonan: false,
    data: { id: null, jenisLayanan: null, namaLengkap: null, noWhatsapp: null, email: null, keterangan: null, status: null, tanggalPermohonan: null },
    edit: null,
    action: null,
}" class="flex flex-col h-full text-gray-900 dark:text-gray-100">
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
                </tr>
            </thead>
            <tbody>
                @foreach ($permohonan as $item)
                <tr class="transition duration-200 border-b hover:cursor-pointer border-b-slate-300 dark:border-b-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700"
                    @click="
                                showModalPermohonan = true;
                                expanded = false;
                                data.id = {{ $item->id }};
                                data.jenisLayanan = `{{ $item->jenis_layanan }}`;
                                data.namaLengkap = `{{ $item->nama_lengkap }}`;
                                data.noWhatsapp = `{{ $item->no_whatsapp }}`;
                                data.email = `{{ $item->email }}`;
                                data.keterangan = `{{ $item->keterangan }}`;
                                data.status = `{{ $item->status }}`;
                                data.tanggalPermohonan = `{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}`;
                                edit = `{{ route('admin.pelayanan-jasa.edit', ['pelayanan_jasa' => $item]) }}`;
                                action = `{{ route('admin.pelayanan-jasa.destroy', ['pelayanan_jasa' => $item]) }}`;
                                ">
                    <td class="p-3 align-top max-w-[150px]">
                        {{ $item->jenis_layanan }}
                    </td>
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
                        <span class="text-yellow-500">
                            {{ $item->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

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
                    <dt class="text-sm text-slate-500">Jenis Layanan</dt>
                    <dd x-text="data.jenisLayanan"></dd>

                    <dt class="text-sm text-slate-500">Nama Lengkap</dt>
                    <dd x-text="data.namaLengkap"></dd>

                    <dt class="text-sm text-slate-500">No WhatsApp</dt>
                    <dd x-text="data.noWhatsapp"></dd>

                    <dt class="text-sm text-slate-500">Email</dt>
                    <dd x-text="data.email"></dd>

                    <dt class="text-sm text-slate-500">Keterangan</dt>
                    <dd x-text="data.keterangan"></dd>

                    <dt class="text-sm text-slate-500">Status</dt>
                    <dd x-text="data.status"></dd>

                    <dt class="text-sm text-slate-500">Tanggal Permohonan</dt>
                    <dd x-text="data.tanggalPermohonan"></dd>
                </dl>

                <div class="flex flex-col gap-3">
                    <a :href="edit"
                        class="w-full p-3 mt-5 text-center text-gray-600 uppercase border border-gray-600 rounded hover:bg-gray-500 hover:text-white">Edit
                        Permohonan</a>
                    <button type="button" @click="showModalPermohonan = false; showModalBatalPermohonan = true"
                        class="w-full p-3 text-center text-white uppercase bg-red-400 rounded hover:bg-red-500">Batalkan
                        Permohonan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->

    <!-- Modal Confirm Delete -->
    <div class="fixed inset-0 z-30 overflow-auto bg-black bg-opacity-50" x-show="showModalBatalPermohonan"
        x-transition.opacity x-cloak>

        <!-- Modal inner -->
        <div class="w-full max-w-sm p-6 mx-auto mt-20 mb-10 text-left bg-white rounded-lg shadow-lg dark:bg-slate-800"
            @click.away="showModalBatalPermohonan = false" x-transition>
            <!-- Title / Close-->
            <div class="flex items-center justify-between mb-5">
                <h5 class="mr-3 font-bold max-w-none">Batalkan Permohonan</h5>

                <button type="button" class="z-50 cursor-pointer" @click="showModalBatalPermohonan = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- content -->
            <div class="modal-content">
                <p>Anda yakin akan membatalkan permohonan?</p>

                <form :action="action" method="post" class="flex gap-3 w-full *:flex-1 mt-5">
                    @csrf @method('delete')

                    <button type="button" class="p-3 rouded" @click="showModalBatalPermohonan = false">Tidak</button>
                    <button type="submit" class="p-3 text-center text-white bg-red-400 rounded hover:bg-red-500">Ya,
    
</div>