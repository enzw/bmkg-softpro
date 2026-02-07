<div x-data="{
    showModalPermohonan: false,
    expanded: false,
    showModalBatalPermohonan: false,
    data: { id: null, pemohon: null, perihal: null, tanggal: null },
    edit: null,
}" class="flex flex-col h-full text-gray-900 dark:text-gray-100">
    @if ($permohonan->isEmpty())
        <div class="grid min-h-[400px] flex-1 place-content-center">
            <img src="{{ asset('images/alat-tidak-tersedia.svg') }}" alt="" width="200">
            <p>Belum ada permohonan</p>
        </div>
    @else
        <div class="w-full -mr-6 overflow-x-auto">
            <table class="w-full overflow-hidden rounded table-auto text-slate-600 dark:text-slate-400">
                <thead class="border-b bg-slate-100 dark:bg-slate-900 border-b-slate-300 dark:border-b-slate-500">
                    <tr>
                        <th class="p-3 text-left">Pemohon</th>
                        <th class="p-3 text-left">Perihal</th>
                        <th class="p-3 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permohonan as $item)
                        <tr class="transition duration-200 border-b hover:cursor-pointer border-b-slate-300 dark:border-b-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700"
                            @click="
                                        showModalPermohonan = true;
                                        data.id = `{{ $item->id }}`;
                                        data.pemohon = `{{ $item->user->nama }}`;
                                        {{-- data.perihal = `{{ $item->alat->nama }}`; --}}
                                        data.tanggal = `{{ $item->created_at }}`;
                                        edit = `{{ route('admin.sewa-alat.edit', ['sewa_alat' => $item]) }}`;
                                    ">
                            <td class="p-3 align-top max-w-[200px]">
                                {{ $item->user->name }}
                            </td>
                            <td class="p-3 align-top max-w-[200px]">
                                {{-- {{ $item-> }} --}}
                            </td>
                            <td class="p-3 align-top">
                                {{ $item->created_at }}
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
                    <dt class="text-sm text-slate-500">Pemohon</dt>
                    <dd x-text="data.pemohon"></dd>

                    <dt class="text-sm text-slate-500">Perihal</dt>
                    <dd x-text="data.perihal"></dd>

                    <dt class="text-sm text-slate-500">Tanggal</dt>
                    <dd x-text="data.tanggal"></dd>
                </dl>

                {{-- <div class="flex flex-col">
                    <a :href="edit"
                        class="w-full p-3 mt-5 text-center text-gray-600 uppercase border border-gray-600 rounded dark:text-white dark:border-gray-500 hover:bg-gray-500 hover:text-white ms-auto">
                        Edit Permohonan
                    </a>
                    <button type="button" @click="showModalPermohonan = false; showModalBatalPermohonan = true"
                        class="w-full p-3 mt-5 text-center text-white uppercase bg-red-400 rounded hover:bg-red-500 ms-auto">Batalkan
                        Permohonan</button>
                </div> --}}
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
