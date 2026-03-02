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
                
                $serviceIcons = [
                    'Magang' => 'fa-graduation-cap',
                    'Layanan Klaim Asuransi' => 'fa-file-invoice-dollar',
                    'Layanan Data' => 'fa-database',
                    'Layanan Peta Sebaran' => 'fa-map',
                    'Layanan Survey' => 'fa-compass',
                    'Layanan Konsultasi' => 'fa-comments',
                ];

                $isEnum = $item->status instanceof \App\Enums\Status;
                $currentStatusColor = $isEnum ? $item->status->color() : 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                $statusLabel = $isEnum ? $item->status->label() : ($item->status->value ?? $item->status ?? 'Menunggu');

                $currentServiceColor = $serviceBgColors[$item->jenis_layanan] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';
                $gradientColor = $serviceColors[$item->jenis_layanan] ?? 'from-gray-500 to-gray-600';
                $serviceIcon = $serviceIcons[$item->jenis_layanan] ?? 'fa-briefcase';
            @endphp

            <div
                class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:shadow-gray-200/40 dark:hover:shadow-none transition-all duration-300 group mb-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $gradientColor }} flex items-center justify-center text-white shadow-lg shadow-gray-100 dark:shadow-none">
                            <i class="fas {{ $serviceIcon }} text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                    @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                        {{ $item->nama_user ?? '-' }}
                                    @else
                                        {{ $item->nama_lengkap ?? $item->nama_user ?? '-' }}
                                    @endif
                                </h3>
                                <span
                                    class="px-3 py-1 rounded-full border {{ $currentServiceColor }} text-[10px] font-bold uppercase tracking-widest">
                                    {{ $item->jenis_layanan }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <i class="fas fa-envelope text-[10px]"></i>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-widest">{{ $item->email ?? $item->no_whatsapp ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            class="px-5 py-2 rounded-full border {{ $currentStatusColor }} text-[10px] font-bold uppercase tracking-widest shadow-sm">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div
                    class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-8 p-6 bg-gray-50/50 dark:bg-gray-900/20 rounded-3xl border border-gray-50 dark:border-gray-700/50">
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Tanggal Request</p>
                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">No WhatsApp</p>
                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                            {{ $item->no_whatsapp ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Waktu</p>
                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="openDetailModal('modal-detail-{{ $loop->index }}')"
                        class="px-6 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-emerald-900/20 hover:text-green-600 dark:hover:text-emerald-400 font-bold text-[10px] uppercase tracking-widest transition-all duration-300 border border-transparent hover:border-green-100 dark:hover:border-emerald-800/30">
                        <i class="fas fa-eye mr-2 text-xs"></i>Detail
                    </button>
                    <a href="{{ route('admin.pelayanan-jasa.edit', $item->id) }}"
                        class="px-6 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 font-bold text-[10px] uppercase tracking-widest transition-all duration-300 border border-transparent hover:border-blue-100 dark:hover:border-blue-800/30">
                        <i class="fas fa-edit mr-2 text-xs"></i>Edit
                    </a>
                    <button type="button" onclick="openModal('modal-delete-{{ $loop->index }}')"
                        class="px-6 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 font-bold text-[10px] uppercase tracking-widest transition-all duration-300 border border-transparent hover:border-red-100 dark:hover:border-red-800/30">
                        <i class="fas fa-trash mr-2 text-xs"></i>Hapus
                    </button>
                    <!-- Delete Modal -->
                    <div id="modal-delete-{{ $loop->index }}"
                        class="hidden fixed inset-0 z-50 overflow-auto bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-[2.5rem] max-w-sm w-full shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300 border border-gray-100 dark:border-gray-700">
                            <!-- Icon -->
                            <div class="flex justify-center pt-8">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-600">
                                    <i class="fas fa-trash-alt text-2xl"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="px-8 pt-6 pb-8 text-center">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-2">Hapus
                                    Data?</h3>
                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest leading-relaxed">
                                    Permohonan <span class="text-gray-900 dark:text-white">
                                        @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                            {{ $item->nama_user ?? '-' }}
                                        @else
                                            {{ $item->nama_lengkap ?? $item->nama_user ?? '-' }}
                                        @endif
                                    </span> akan dihapus secara permanen.
                                </p>
                            </div>

                            <!-- Actions -->
                            <div
                                class="flex gap-3 p-6 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700">
                                <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-6 py-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 font-bold text-[10px] uppercase tracking-widest transition-all">
                                    Batal
                                </button>
                                <button type="button" id="btn-delete-{{ $loop->index }}"
                                    onclick="deleteRecord('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-6 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-red-200 dark:shadow-none">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Modal -->
                <div id="modal-detail-{{ $loop->index }}"
                    class="hidden fixed inset-0 z-50 overflow-auto bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-[2.5rem] max-w-2xl w-full shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden animate-in fade-in zoom-in duration-300">
                        <!-- Header -->
                        <div
                            class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 dark:text-emerald-400">
                                    <i class="fas fa-file-invoice text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Detail
                                        Permohonan</h3>
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">ID:
                                        #{{ substr($item->id, 0, 8) }}</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors shadow-sm border border-gray-100 dark:border-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-8 space-y-8 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            <!-- Basic Information -->
                            <div class="relative pl-6 border-l-2 border-green-500/30">
                                <h4
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Informasi Dasar
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Nama
                                            Pemohon</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                                {{ $item->nama_user ?? '-' }}
                                            @else
                                                {{ $item->nama_lengkap ?? $item->nama_user ?? '-' }}
                                            @endif
                                        </p>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">No
                                            WhatsApp</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->no_whatsapp ?? '-' }}
                                        </p>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Email
                                        </p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->email ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Information -->
                            <div class="relative pl-6 border-l-2 border-amber-500/30">
                                <h4
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Informasi Layanan
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div
                                        class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Jenis
                                            Layanan</p>
                                        <span
                                            class="inline-block mt-1 px-3 py-1 rounded-full border {{ $currentServiceColor }} text-[10px] font-bold uppercase tracking-widest">
                                            {{ $item->jenis_layanan }}
                                        </span>
                                    </div>
                                    <div
                                        class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Status
                                        </p>
                                        <span
                                            class="inline-block mt-1 px-3 py-1 rounded-full border {{ $currentStatusColor }} text-[10px] font-bold uppercase tracking-widest">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Magang Specific -->
                            @if($item->jenis_layanan === 'Magang')
                                <div class="relative pl-6 border-l-2 border-purple-500/30">
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        Informasi Akademik
                                    </h4>
                                    <div class="space-y-4">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700">
                                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                Universitas / Instansi</p>
                                            <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                {{ $item->universitas ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div
                                                class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                    Fakultas</p>
                                                <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                    {{ $item->fakultas ?? '-' }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                    Program Studi</p>
                                                <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                    {{ $item->prodi ?? '-' }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                    Tgl Mulai</p>
                                                <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                    {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d F Y') : '-' }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                    Tgl Selesai</p>
                                                <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                    {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d F Y') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Asuransi Specific -->
                            @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                <div class="relative pl-6 border-l-2 border-red-500/30">
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Informasi Pemohon
                                    </h4>
                                    <div class="space-y-4">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                Perusahaan Asuransi</p>
                                            <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                {{ $item->perusahaan ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div
                                                class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                                <p
                                                    class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                    Tanggal Kejadian</p>
                                                <p
                                                    class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d F Y') : '-' }}
                                                </p>
                                            </div>
                                            <div
                                                class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                                <p
                                                    class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">
                                                    Lokasi Kejadian</p>
                                                <p
                                                    class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                                    {{ $item->lokasi ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <!-- Keterangan -->
                            @if(!in_array($item->jenis_layanan, ['Magang', 'Layanan Klaim Asuransi']) && $item->keterangan)
                                <div class="relative pl-6 border-l-2 border-blue-500/30">
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Detail Layanan
                                    </h4>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 text-left">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-2 text-left">
                                            Keterangan / Keperluan
                                        </p>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed selection:bg-transparent text-left w-full">{{ $item->keterangan }}</p>
                                    </div>
                                </div>
                            @endif


                            <!-- Documents -->
                            @php
                                $hasDocs = false;
                                if ($item->jenis_layanan === 'Magang') {
                                    $hasDocs = $item->surat_permohonan || $item->kartu_mahasiswa;
                                } elseif ($item->jenis_layanan === 'Layanan Klaim Asuransi') {
                                    $hasDocs = $item->surat_permohonan || $item->ktp;
                                } else {
                                    $hasDocs = $item->surat_permohonan || $item->ktp;
                                }
                            @endphp

                            @if($hasDocs)
                                <div class="relative pl-6 border-l-2 border-red-500/30">
                                    <h4
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Dokumen Pendukung
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @if($item->surat_permohonan)
                                            <a href="{{ route('admin.pelayanan-jasa.download', ['id' => $item->id]) }}"
                                                class="flex items-center gap-3 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-emerald-900/20 transition-all duration-300 group/link">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600">
                                                    <i class="fas fa-file-pdf"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">
                                                        Surat Permohonan</p>
                                                    <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download
                                                        PDF</p>
                                                </div>
                                            </a>
                                        @endif

                                        @if($item->jenis_layanan !== 'Magang' && $item->ktp)
                                            <a href="{{ route('admin.pelayanan-jasa.download', ['id' => $item->id, 'document' => 'ktp']) }}"
                                                class="flex items-center gap-3 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300 group/link">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600">
                                                    <i class="fas fa-id-card"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">
                                                        KTP / Identitas</p>
                                                    <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download
                                                        File</p>
                                                </div>
                                            </a>
                                        @endif

                                        @if($item->jenis_layanan === 'Magang' && $item->kartu_mahasiswa)
                                            <a href="{{ route('admin.pelayanan-jasa.download-file', ['id' => $item->id, 'fileName' => basename($item->kartu_mahasiswa)]) }}"
                                                class="flex items-center gap-3 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-300 group/link">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600">
                                                    <i class="fas fa-address-card"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">
                                                        Kartu Pelajar</p>
                                                    <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download
                                                        Image</p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div
                            class="p-8 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700 flex flex-wrap gap-3">
                            <button type="button" onclick="closeDetailModal('modal-detail-{{ $loop->index }}')"
                                class="flex-1 px-8 py-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-sm">
                                Tutup
                            </button>
                            <a href="{{ route('admin.pelayanan-jasa.edit', $item->id) }}"
                                class="flex-1 px-8 py-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-green-200 dark:shadow-none text-center">
                                Edit Data
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
        // Get the button element based on modal ID
        const buttonId = modalId.replace('modal-delete-', 'btn-delete-');
        const deleteBtn = document.getElementById(buttonId);

        let originalText = 'Hapus';
        if (deleteBtn) {
            originalText = deleteBtn.textContent;
            deleteBtn.textContent = 'Menghapus...';
            deleteBtn.disabled = true;
        }

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
            if (deleteBtn) {
                deleteBtn.textContent = originalText;
                deleteBtn.disabled = false;
            }
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
                    closeModal(modalId);
                    window.location.reload();
                } else {
                    const errorMsg = result.data && result.data.message ? String(result.data.message) : 'Gagal menghapus permohonan';
                    alert(errorMsg);
                    if (deleteBtn) {
                        deleteBtn.textContent = originalText;
                        deleteBtn.disabled = false;
                    }
                }
            })
            .catch(error => {
                const errorMsg = error && error.message ? String(error.message) : 'Terjadi kesalahan';
                alert(errorMsg);
                if (deleteBtn) {
                    deleteBtn.textContent = originalText;
                    deleteBtn.disabled = false;
                }
            });
    }
</script>