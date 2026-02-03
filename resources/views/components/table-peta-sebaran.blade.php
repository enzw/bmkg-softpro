<div class="overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
            Daftar Permohonan Anda
        </h3>

        @if($permohonan->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                    <thead class="text-xs font-semibold text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Perusahaan</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permohonan as $item)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 font-medium">{{ $item->perusahaan }}</td>
                                <td class="px-4 py-3">{{ $item->tanggal }}</td>
                                <td class="px-4 py-3">{{ $item->lokasi }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($item->status == 'Menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($item->status == 'Diproses') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($item->status == 'Selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($item->status == 'Ditolak') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" onclick="deletePermohonan({{ $item->id }}, 'peta-sebaran')"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                <p>Belum ada permohonan</p>
            </div>
        @endif
    </div>
</div>

<script>
function deletePermohonan(id, type) {
    if (confirm('Apakah Anda yakin ingin menghapus permohonan ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/${type}/${id}`;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
