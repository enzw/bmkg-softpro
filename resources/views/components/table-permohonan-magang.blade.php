<div class="col-span-2">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Header -->
        <div class="p-8 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-2">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <i class="fas fa-list text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Permohonan</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 ml-13">Daftar permohonan pelayanan jasa Anda</p>
        </div>


        <!-- Content -->
        <div class="p-8">
            @if ($permohonan->isEmpty())
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                        <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Permohonan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Isi formulir di samping untuk membuat permohonan
                        pelayanan jasa</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($permohonan as $item)
                        @php
                            $statusColor = [
                                'Menunggu' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-300',
                                'Diproses' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300',
                                'Ditolak' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
                                'Selesai' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
                            ];
                            $statusValue = $item->status instanceof \App\Enums\Status ? $item->status->value : (string)$item->status;
                            $statusLabel = $item->status instanceof \App\Enums\Status ? $item->status->label() : $statusValue;
                            $currentStatusColor = $statusColor[$statusValue] ?? 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800/50 text-gray-700 dark:text-gray-300';

                            $serviceColor = [
                                'Magang' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300',
                                'Layanan Kunjungan Teknis' => 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300',
                                'Layanan Klaim Asuransi' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
                                'Layanan Data' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
                                'Layanan Peta Sebaran' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300',
                                'Layanan Survey' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300',
                                'Layanan Konsultasi' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300',
                            ];
                            $currentServiceColor = $serviceColor[$item->jenis_layanan] ?? 'bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-300';
                            
                            $serviceIcon = [
                                'Magang' => 'fa-graduation-cap',
                                'Layanan Kunjungan Teknis' => 'fa-building',
                                'Layanan Klaim Asuransi' => 'fa-file-invoice-dollar',
                                'Layanan Data' => 'fa-database',
                                'Layanan Peta Sebaran' => 'fa-map',
                                'Layanan Survey' => 'fa-compass',
                                'Layanan Konsultasi' => 'fa-comments',
                            ];
                            $currentIcon = $serviceIcon[$item->jenis_layanan] ?? 'fa-list';
                        @endphp

                        <div
                            class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-gray-200 dark:hover:border-gray-600 transition group">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <i class="fas {{ $currentIcon }} text-lg text-gray-900 dark:text-white"></i>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $item->jenis_layanan }}</h3>
                                        <span class="text-sm px-3 py-1 rounded-full {{ $currentServiceColor }}">
                                            <i class="fas {{ $currentIcon }} mr-1"></i>{{ $item->jenis_layanan }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="px-4 py-2 rounded-lg border {{ $currentStatusColor }} font-semibold text-sm">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                                @if($item->table_name === 'kunjungan' || $item->table_name === 'asuransi')
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Nama</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $item->nama_user ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Lokasi</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $item->lokasi ?? '-' }}</p>
                                    </div>
                                    @if($item->table_name === 'asuransi')
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Tarif</p>
                                            <p class="font-semibold text-blue-600 dark:text-blue-400">
                                                Rp 185.000
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Nama Lengkap</p>
                                        <p class="font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $item->nama_lengkap ?? '-' }}</p>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Email</p>
                                        <p class="font-semibold text-gray-900 dark:text-white truncate"
                                            title="{{ $item->email ?? '-' }}">{{ $item->email ?? '-' }}</p>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">WhatsApp</p>
                                        <p class="font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $item->no_whatsapp ?? '-' }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-3">
                                <button type="button" onclick="openModal('modal-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                    <i class="fas fa-eye mr-2"></i>Detail
                                </button>
                                <button type="button"
                                    onclick="openDeleteModal('{{ $item->id }}', 'modal-delete-{{ $loop->index }}')"
                                    class="flex-1 px-4 py-2 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 font-semibold text-sm transition">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Detail Modal -->
                        <div id="modal-{{ $loop->index }}"
                            class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden">
                                <!-- Modal Header with Gradient -->
                                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 dark:from-indigo-700 dark:to-indigo-800 p-6 text-white">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                                                <i class="fas fa-briefcase text-2xl text-white"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold opacity-90">Detail Permohonan</p>
                                                <h3 class="text-lg font-bold">{{ $item->jenis_layanan }}</h3>
                                            </div>
                                        </div>
                                        <button type="button" onclick="closeModal('modal-{{ $loop->index }}')" class="text-white/70 hover:text-white transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal Content -->
                                <div class="p-6 space-y-4 max-h-[calc(100vh-240px)] overflow-y-auto">
                                    <!-- Status Badge -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Saat Ini</span>
                                        @php
                                            $statusValue = $item->status instanceof \App\Enums\Status ? $item->status->value : (string)$item->status;
                                            $statusColor = match($statusValue) {
                                                'Menunggu' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
                                                'Diproses' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                                'Ditolak' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800',
                                                'Selesai' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800',
                                                default => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full font-semibold text-sm {{ $statusColor }}">{{ $statusLabel }}</span>
                                    </div>

                                    <div class="space-y-6">

                                        {{-- Magang Fields --}}
                                        @if($item->jenis_layanan === 'Magang')
                                            <!-- Informasi Peserta -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Informasi Peserta</h4>
                                                <div class="space-y-2">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Nama Lengkap</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->nama_lengkap ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Email</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->email ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">No WhatsApp</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Informasi Kampus -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Informasi Kampus</h4>
                                                <div class="space-y-2">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Universitas</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->universitas ?? '-' }}</p>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Fakultas</p>
                                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->fakultas ?? '-' }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Prodi</p>
                                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->prodi ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Periode Magang -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Periode Magang</h4>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Mulai</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Selesai</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Klaim Asuransi Fields --}}
                                        @elseif($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                            <!-- Informasi Pemohon -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Informasi Pemohon</h4>
                                                <div class="space-y-2">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Nama</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->nama_user ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">No WhatsApp</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Klaim -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Detail Klaim</h4>
                                                <div class="space-y-2">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Perusahaan</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->perusahaan ?? '-' }}</p>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Tanggal Kejadian</p>
                                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Lokasi</p>
                                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->lokasi ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Koordinat -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Koordinat Lokasi</h4>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Latitude</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->latitude ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Longitude</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->longitude ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($item->table_name === 'asuransi')
                                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                                    <p class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2">Tarif Layanan</p>
                                                    <p class="text-lg font-bold text-blue-700 dark:text-blue-300">Rp 185.000</p>
                                                </div>
                                            @endif
                                            {{-- Layanan Data Fields --}}
                                        @elseif($item->jenis_layanan === 'Layanan Data')
                                            <!-- Informasi Pemohon -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Informasi Pemohon</h4>
                                                <div class="space-y-2">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Nama Lengkap</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->nama_lengkap ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Email</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->email ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">No WhatsApp</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Keterangan Permintaan -->
                                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                                <p class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2">Keterangan / Keperluan</p>
                                                <p class="text-sm text-blue-900 dark:text-blue-100 whitespace-pre-wrap">{{ $item->keterangan ?? 'Tidak ada keterangan' }}</p>
                                            </div>
                                            {{-- Layanan Peta Sebaran Fields --}}
                                        @elseif($item->jenis_layanan === 'Layanan Peta Sebaran')
                                            <div>
                                                <p
                                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                    Perusahaan/Instansi</p>
                                                <p class="text-gray-900 dark:text-white font-semibold">
                                                    {{ $item->perusahaan ?? '-' }}</p>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Tanggal Kejadian</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Lokasi</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->lokasi ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Latitude</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->latitude ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Longitude</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->longitude ?? '-' }}</p>
                                                </div>
                                            </div>
                                            {{-- Layanan Survey Fields --}}
                                        @elseif($item->jenis_layanan === 'Layanan Survey')
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Nama Lengkap</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->nama_lengkap ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        No WhatsApp</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->no_whatsapp ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div>
                                                <p
                                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                    Email</p>
                                                <p class="text-gray-900 dark:text-white font-semibold">{{ $item->email ?? '-' }}</p>
                                            </div>

                                            <div class="relative pl-6 border-l-2 border-blue-500/30">
                                                <h4
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    Detail Layanan
                                                </h4>
                                                <div
                                                    class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 text-left">
                                                    <p
                                                        class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-2 text-left">
                                                        Keterangan / Keperluan
                                                    </p>
                                                    <p
                                                        class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed text-left w-full">
                                                        {{ $item->keterangan }}</p>
                                                </div>
                                            </div>
                                            {{-- Layanan Konsultasi Fields --}}
                                        @elseif($item->jenis_layanan === 'Layanan Konsultasi')
                                            <!-- Informasi Pemohon -->
                                            <div class="space-y-3">
                                                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">Informasi Pemohon</h4>
                                                <div class="space-y-2">
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Nama Lengkap</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->nama_lengkap ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Email</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->email ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">No WhatsApp</p>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->no_whatsapp ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Keterangan Konsultasi -->
                                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                                                <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2">Topik Konsultasi</p>
                                                <p class="text-sm text-purple-900 dark:text-purple-100 whitespace-pre-wrap">{{ $item->keterangan ?? 'Tidak ada keterangan' }}</p>
                                            </div>
                                            {{-- Layanan Kunjungan Teknis Fields --}}
                                        @elseif($item->jenis_layanan === 'Layanan Kunjungan Teknis')
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Nama</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->nama_user ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Tanggal</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div>
                                                <p
                                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                    Lokasi</p>
                                                <p class="text-gray-900 dark:text-white font-semibold">{{ $item->lokasi ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Latitude</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->latitude ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                        Longitude</p>
                                                    <p class="text-gray-900 dark:text-white font-semibold">
                                                        {{ $item->longitude ?? '-' }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <p
                                                class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                                Tanggal Permohonan</p>
                                            <p class="text-gray-900 dark:text-white font-semibold">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</p>
                                        </div>

                                        @if($item->surat_permohonan)
                                            <div class="pt-2">
                                                @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                                    <a href="{{ route('permohonan-asuransi.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                                                        <i class="fas fa-file-pdf mr-2"></i>Download Surat Permohonan
                                                    </a>
                                                @else
                                                    <a href="{{ route('pelayanan-jasa.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}"
                                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                                                        <i class="fas fa-file-pdf mr-2"></i>Download Surat Permohonan
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        @if($item->ktp)
                                            <div class="pt-2">
                                                @if($item->jenis_layanan === 'Layanan Klaim Asuransi')
                                                    <a href="{{ route('permohonan-asuransi.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}"
                                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                                        <i class="fas fa-id-card mr-2"></i>Download KTP/Identitas
                                                    </a>
                                                @else
                                                    <a href="{{ route('pelayanan-jasa.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}"
                                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                                        <i class="fas fa-id-card mr-2"></i>Download KTP/Identitas
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        @if($item->kartu_identitas)
                                            <div class="pt-2">
                                                <a href="{{ route('pelayanan-jasa.download-file', ['id' => $item->id, 'fileName' => basename($item->kartu_identitas)]) }}"
                                                    class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 font-semibold text-sm transition">
                                                    <i class="fas fa-id-card mr-2"></i>Download Kartu Identitas
                                                </a>
                                            </div>
                                        @endif

                                        @if($item->kartu_mahasiswa)
                                            <div class="pt-2">
                                                <a href="{{ route('pelayanan-jasa.download-file', ['id' => $item->id, 'fileName' => basename($item->kartu_mahasiswa)]) }}"
                                                    class="inline-flex items-center justify-center w-full px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-semibold text-sm transition">
                                                    <i class="fas fa-graduation-cap mr-2"></i>Download Kartu Mahasiswa
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                    <button type="button" onclick="closeModal('modal-{{ $loop->index }}')"
                                        class="w-full px-4 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold transition shadow-lg">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div id="modal-delete-{{ $loop->index }}"
                            class="hidden fixed inset-0 z-50 overflow-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl">
                                <div
                                    class="flex items-center justify-center w-16 h-16 mx-auto mt-6 rounded-full bg-red-100 dark:bg-red-900/20">
                                    <i class="fas fa-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                                </div>

                                <div class="mt-4 text-center px-6">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Permohonan?</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mt-2">Yakin ingin menghapus permohonan untuk
                                        <strong>{{ $item->jenis_layanan }}</strong>?</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Tindakan ini tidak dapat
                                        dibatalkan.</p>
                                </div>

                                <div class="flex gap-3 p-6">
                                    <button type="button" onclick="closeModal('modal-delete-{{ $loop->index }}')"
                                        class="flex-1 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition">
                                        Batal
                                    </button>
                                    <button type="button" id="delete-btn-{{ $loop->index }}"
                                        onclick="confirmDelete('{{ $item->id }}', this)"
                                        class="flex-1 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openDeleteModal(recordId, modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function confirmDelete(recordId, buttonElement) {
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
            alert('Error: CSRF token tidak ditemukan');
            return;
        }

        // Disable button and change text to loading state
        const originalText = buttonElement.textContent;
        buttonElement.textContent = 'Menghapus...';
        buttonElement.disabled = true;
        buttonElement.classList.add('opacity-70', 'cursor-not-allowed');

        const deleteUrl = `/layanan/pelayanan-jasa/${recordId}`;

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Gagal menghapus permohonan');
                    });
                }
            })
            .catch(error => {
                // Reset button state on error
                buttonElement.textContent = originalText;
                buttonElement.disabled = false;
                buttonElement.classList.remove('opacity-70', 'cursor-not-allowed');
                alert('Error: ' + error.message);
                console.error('Error:', error);
            });
    }
</script>