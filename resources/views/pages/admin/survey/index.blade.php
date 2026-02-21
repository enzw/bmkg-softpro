@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Data Survey
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Kelola semua permohonan survey di sini.
                            </p>
                        </div>
                        <a href="{{ route('admin.survey.create') }}"
                            class="px-4 py-3 text-white bg-green-600 rounded hover:bg-green-500 transition">
                            + Tambah Permohonan
                        </a>
                    </div>
                </div>

                @if ($survey->count() > 0)
                <div class="space-y-4">
                    @foreach ($survey as $item)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md dark:hover:shadow-lg transition group">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-3 h-3 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600"></div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->nama_lengkap }}</h3>
                                        <span class="text-sm px-3 py-1 rounded-full border bg-cyan-50 dark:bg-cyan-900/20 border-cyan-200 dark:border-cyan-800/50 text-cyan-700 dark:text-cyan-300">
                                            Survey
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-envelope mr-2"></i>
                                        {{ $item->email }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    @php
                                        // Get status value from enum
                                        $statusValue = $item->status instanceof \App\Enums\Status ? $item->status->value : ($item->status ?? 'Menunggu');
                                        $statusLabel = $item->status instanceof \App\Enums\Status ? $item->status->label() : $statusValue;
                                        $statusColor = [
                                            'Disetujui' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300',
                                            'Diterima' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300',
                                            'Diproses' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300',
                                            'Menunggu' => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800/50 text-orange-700 dark:text-orange-300',
                                        ];
                                        $currentStatusColor = $statusColor[$statusValue] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                                    @endphp
                                    <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">No WhatsApp</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Tgl Permohonan</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $item->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Keterangan</p>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $item->keterangan ? substr($item->keterangan, 0, 20) . '...' : '-' }}</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('admin.survey.edit', $item->id) }}"
                                    class="flex-1 px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                <button type="button" onclick="openModal('modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-2 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 font-semibold text-sm transition">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>

                                <!-- Delete Modal -->
                                <div id="modal-delete-{{ $loop->index }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300">
                                    <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-sm w-full shadow-2xl transform transition-all duration-300 scale-95 hover:scale-100">
                                        <!-- Icon -->
                                        <div class="flex justify-center pt-8">
                                            <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                                                <i class="fas fa-trash text-2xl text-red-600 dark:text-red-400"></i>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="px-8 pt-6 pb-8 text-center">
                                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Hapus Survey?</h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                                Data survey <strong class="text-gray-900 dark:text-white">{{ $item->nama }}</strong> akan dihapus secara permanen.
                                            </p>
                                        </div>

                                        <!-- Divider -->
                                        <div class="h-px bg-gray-100 dark:bg-gray-700"></div>

                                        <!-- Actions -->
                                        <div class="flex gap-3 p-6">
                                            <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                                                class="flex-1 px-4 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition-all duration-200 hover:shadow-md">
                                                Batal
                                            </button>
                                            <button type="button" onclick="deleteRecord('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                                class="flex-1 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition-all duration-200 hover:shadow-md">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
                @else
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center">
                    <p class="text-gray-600 dark:text-gray-400">Belum ada permohonan survey.</p>
                </div>
                @endif

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function deleteRecord(recordId, modalId) {
    closeModal(modalId);
    
    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) csrfToken = tokenInput.value;
    }
    
    if (!csrfToken) {
        alert('CSRF token tidak ditemukan');
        return;
    }
    
    fetch(`/admin/survey/${recordId}`, {
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
            const errorMsg = result.data && result.data.message ? String(result.data.message) : 'Gagal menghapus survey';
            alert(errorMsg);
        }
    })
    .catch(error => {
        const errorMsg = error && error.message ? String(error.message) : 'Terjadi kesalahan';
        alert(errorMsg);
    });
}
</script>
@endsection
