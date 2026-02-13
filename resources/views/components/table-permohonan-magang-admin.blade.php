<div class="space-y-4">
    @if ($permohonan->isEmpty())
        <div class="flex flex-col items-center justify-center py-16">
            <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Permohonan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan buat permohonan baru untuk memulai</p>
        </div>
    @else
        @foreach ($permohonan as $item)
            @php
                $serviceColors = [
                    'Magang' => 'from-amber-500 to-amber-600',
                    'Layanan Klaim Asuransi' => 'from-red-500 to-red-600',
                    'Layanan Data' => 'from-blue-500 to-blue-600',
                    'Layanan Peta Sebaran' => 'from-green-500 to-green-600',
                    'Layanan Survey' => 'from-purple-500 to-purple-600',
                    'Layanan Konsultasi' => 'from-indigo-500 to-indigo-600',
                ];
                $serviceBgColors = [
                    'Magang' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800/50 text-amber-700 dark:text-amber-300',
                    'Layanan Klaim Asuransi' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
                    'Layanan Data' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                    'Layanan Peta Sebaran' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                    'Layanan Survey' => 'bg-purple-50 dark:bg-purple-900/20 border-purple-200 dark:border-purple-800/50 text-purple-700 dark:text-purple-300',
                    'Layanan Konsultasi' => 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-800/50 text-indigo-700 dark:text-indigo-300',
                ];

                $statusColor = [
                    'Menunggu' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-300',
                    'Diproses' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                    'Ditolak' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
                    'Selesai' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                ];
                $currentStatusColor = $statusColor[$item->status] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                $currentServiceColor = $serviceBgColors[$item->jenis_layanan] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                $gradientColor = $serviceColors[$item->jenis_layanan] ?? 'from-gray-500 to-gray-600';
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md dark:hover:shadow-lg transition group">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-3 h-3 rounded-full bg-gradient-to-r {{ $gradientColor }}"></div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                    {{ $item->nama_user ?? '-' }}
                                @else
                                    {{ $item->nombre_lengkap ?? $item->nama_lengkap ?? $item->nama_user ?? '-' }}
                                @endif
                            </h3>
                            <span class="text-sm px-3 py-1 rounded-full border {{ $currentServiceColor }}">
                                {{ $item->jenis_layanan }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $item->email ?? $item->no_whatsapp ?? '-' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                            {{ $item->status }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">No WhatsApp</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Jam</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="openDetailModal('modal-detail-{{ $loop->index }}')"
                        class="flex-1 px-4 py-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </button>
                    <a href="{{ route('admin.pelayanan-jasa.edit', $item->id) }}"
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
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Hapus Permohonan?</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                    Permohonan <strong class="text-gray-900 dark:text-white">
                                        @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                            {{ $item->nama_user ?? '-' }}
                                        @else
                                            {{ $item->nama_lengkap ?? $item->nombre_lengkap ?? $item->nama_user ?? $item->perusahaan }}
                                        @endif
                                    </strong> akan dihapus secara permanen.
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

                <!-- Detail Modal -->
                <div id="modal-detail-{{ $loop->index }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
                        <!-- Header -->
                        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Permohonan</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ID: #{{ $item->id }}</p>
                                </div>
                                <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')" 
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i class="fas fa-times text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-user text-green-600 dark:text-green-400"></i>
                                    Informasi Dasar
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nama Lengkap:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                                {{ $item->nama_user ?? '-' }}
                                            @else
                                                {{ $item->nombre_lengkap ?? $item->nama_lengkap ?? $item->nama_user ?? $item->perusahaan ?? '-' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">No WhatsApp:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-amber-600 dark:text-amber-400"></i>
                                    Informasi Layanan
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Jenis Layanan:</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold border {{ $currentServiceColor }}">
                                            {{ $item->jenis_layanan }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold border {{ $currentStatusColor }}">
                                            {{ $item->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Magang Specific -->
                            @if($item->jenis_layanan === 'Magang')
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-graduation-cap text-purple-600 dark:text-purple-400"></i>
                                        Informasi Akademik
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Universitas:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->universitas ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Fakultas:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->fakultas ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Program Studi:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->prodi ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Tanggal Mulai:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Tanggal Selesai:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Asuransi Specific -->
                            @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-red-600 dark:text-red-400"></i>
                                        Informasi Asuransi
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Perusahaan:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->perusahaan ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Tanggal Kejadian:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Lokasi:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->lokasi ?? '-' }}</span>
                                        </div>
                                        @if($item->latitude || $item->longitude)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Koordinat:</span>
                                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->latitude ?? '-' }}, {{ $item->longitude ?? '-' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Description for Data/Survey Services -->
                            @if(($item->jenis_layanan === 'Layanan Data' || $item->jenis_layanan === 'Layanan Survey' || $item->jenis_layanan === 'Layanan Konsultasi') && $item->keterangan)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-file-alt text-yellow-600 dark:text-yellow-400"></i>
                                        Keterangan
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                        <p class="text-gray-900 dark:text-white text-sm whitespace-pre-wrap">{{ $item->keterangan }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Documents -->
                            @if($item->surat_permohonan || $item->ktp)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-file text-red-600 dark:text-red-400"></i>
                                        Dokumen
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                        @if($item->surat_permohonan)
                                            <a href="{{ route('admin.pelayanan-jasa.download', ['id' => $item->id]) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-semibold text-sm transition w-full justify-center">
                                                <i class="fas fa-file-pdf mr-2"></i>Download Surat Permohonan
                                            </a>
                                        @endif
                                        @if($item->ktp)
                                            <a href="{{ route('admin.pelayanan-jasa.download', ['id' => $item->id, 'document' => 'ktp']) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold text-sm transition w-full justify-center">
                                                <i class="fas fa-id-card mr-2"></i>Download KTP/Identitas
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($item->jenis_layanan === 'Magang' && ($item->surat_ijin_magang || $item->kartu_mahasiswa))
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <i class="fas fa-file-pdf text-blue-600 dark:text-blue-400"></i>
                                        Dokumen Akademik
                                    </h4>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                        @if($item->surat_ijin_magang)
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Surat Ijin Magang:</p>
                                                <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_ijin_magang)]) }}"
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold text-sm transition w-full justify-center">
                                                    <i class="fas fa-file-pdf mr-2"></i>Download
                                                </a>
                                            </div>
                                        @endif
                                        @if($item->kartu_mahasiswa)
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Kartu Mahasiswa:</p>
                                                <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $item->id, 'fileName' => basename($item->kartu_mahasiswa)]) }}"
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white font-semibold text-sm transition w-full justify-center">
                                                    <i class="fas fa-image mr-2"></i>Download
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Timestamp -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-600 dark:text-gray-400"></i>
                                    Waktu
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Diperbarui:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 p-6 flex gap-3">
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                                Tutup
                            </button>
                            <a href="{{ route('admin.pelayanan-jasa.edit', $item->id) }}"
                                class="flex-1 px-4 py-3 rounded-lg bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-semibold transition">
                                <i class="fas fa-edit mr-2"></i>Edit Permohonan
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        @endforeach
    @endif
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openDetailModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = 'auto';
}
function showToast(type, message) {
    // Ensure toastr is available
    if (typeof toastr === 'undefined') {
        console.warn('Toastr not available, showing alert instead');
        alert(message);
        return;
    }
    
    try {
        if (type === 'success') {
            toastr.success(message);
        } else if (type === 'error') {
            toastr.error(message);
        }
    } catch (e) {
        console.error('Toast error:', e);
        alert(message);
    }
}
function deleteRecord(recordId, modalId) {
    closeModal(modalId);
    
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
    
    fetch(`/admin/pelayanan-jasa/${recordId}`, {
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
            const errorMsg = result.data && result.data.message ? String(result.data.message) : 'Gagal menghapus permohonan';
            alert(errorMsg);
        }
    })
    .catch(error => {
        const errorMsg = error && error.message ? String(error.message) : 'Terjadi kesalahan';
        alert(errorMsg);
    });
}
</script>