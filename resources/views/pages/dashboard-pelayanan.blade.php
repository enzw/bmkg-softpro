@extends('layouts.main')

@section('content')
    <x-rating-modal :pendingRating="$pendingRating ?? null" />
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="container px-4 mx-auto py-10">
            <!-- Header Section -->
            <div class="mt-12 mb-10">
                <div class="mb-8">
                    <h1 class="text-5xl font-black text-gray-900 dark:text-white mb-2">
                        Dashboard Pelayanan
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Selamat datang, <span
                            class="font-semibold text-green-600 dark:text-green-400">{{ Auth::user()->name }}</span>!
                    </p>
                </div>


                <!-- Services Section -->
                @if ($totalPermohonan == 0)
                <div class="mb-16" id="layanan">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Layanan Tersedia</h2>
                        <p class="text-gray-600 dark:text-gray-400">Pilih layanan yang Anda butuhkan</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($layanan as $item)
                            <div
                                class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 hover:-translate-y-2 flex flex-col h-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                                <!-- Image Section -->
                                <div
                                    class="relative overflow-hidden h-56 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600">
                                    <img src="{{ asset($item['images']) }}" alt="{{ $item['nama'] }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="mb-3 flex items-center gap-2">
                                        @if (str_contains($item['url'], 'sewa-alat'))
                                            <span
                                                class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-full">
                                                <i class="fas fa-tools mr-1"></i> Sewa Alat
                                            </span>
                                        @elseif (str_contains($item['url'], 'pelayanan-jasa'))
                                            <span
                                                class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-semibold rounded-full">
                                                <i class="fas fa-briefcase mr-1"></i> Layanan Jasa
                                            </span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-semibold rounded-full">
                                                <i class="fas fa-users mr-1"></i> Kunjungan
                                            </span>
                                        @endif
                                    </div>

                                    <h3
                                        class="text-2xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 dark:group-hover:text-green-400 transition duration-300">
                                        {{ $item['nama'] }}
                                    </h3>

                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-6 flex-grow leading-relaxed">
                                        {{ $item['deskripsi'] }}
                                    </p>

                                    <a href="{{ $item['url'] }}"
                                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-center">
                                        <i class="fas fa-arrow-right mr-2"></i> {{ $item['cta'] ?? 'Ajukan Permohonan' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- History Section -->
                <div class="mb-16">
                    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Riwayat Permohonan</h2>
                            <p class="text-gray-600 dark:text-gray-400">Pantau status permohonan layanan Anda</p>
                        </div>
                        @if ($totalPermohonan > 0)
                        <button onclick="openServiceSelectionModal()"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Permohonan
                        </button>
                        @endif
                    </div>

                    @if ($totalPermohonan > 0)
                        {{-- Search Bar --}}
                        <div class="mb-6">
                            <div class="flex gap-2 max-w-lg">
                                <div class="relative flex-grow">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="permohonan-search" 
                                        class="block w-full py-3.5 pl-11 pr-10 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 dark:text-white transition-all duration-200 shadow-sm"
                                        placeholder="Cari permohonan..."
                                        value="{{ $search ?? '' }}">
                                    @if (isset($search) && $search)
                                    <a href="{{ route('dashboard-pelayanan') }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                    @endif
                                </div>
                                <button onclick="triggerSearch()" 
                                    class="px-6 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl transition duration-300 shadow-sm hover:shadow-md flex items-center justify-center min-w-[80px]">
                                    <span>Cari</span>
                                </button>
                            </div>
                        </div>

                        @if (count($permohonan) > 0)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            class="bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-700 dark:to-emerald-700">
                                            <th class="px-6 py-4 text-left text-sm font-bold text-white">No</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-white">Jenis Permohonan</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-white">Status</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-white">Tanggal Pengajuan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="permohonan-table-body">
                                        @foreach ($permohonan as $index => $item)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 cursor-pointer"
                                                onclick="openDetailModal(this)"
                                                data-id="{{ $item['id'] }}"
                                                data-jenis="{{ e($item['jenis']) }}"
                                                data-status="{{ e($item['status']) }}"
                                                data-tanggal="{{ $item['tanggal']->format('d M Y, H:i') }}"
                                                data-delete-url="{{ $item['delete_url'] }}"
                                                data-detail='{{ json_encode($item['detail']) }}'>
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $index + 1 }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        @if (str_contains($item['jenis'], 'Sewa'))
                                                            <div
                                                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-tools text-blue-600 dark:text-blue-400"></i>
                                                            </div>
                                                        @elseif (str_contains($item['jenis'], 'Magang'))
                                                            <div
                                                                class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                                                <i
                                                                    class="fas fa-graduation-cap text-purple-600 dark:text-purple-400"></i>
                                                            </div>
                                                        @elseif (str_contains($item['jenis'], 'Asuransi'))
                                                            <div
                                                                class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                                                <i
                                                                    class="fas fa-file-invoice-dollar text-amber-600 dark:text-amber-400"></i>
                                                            </div>
                                                        @elseif (str_contains($item['jenis'], 'Data'))
                                                            <div
                                                                class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-database text-indigo-600 dark:text-indigo-400"></i>
                                                            </div>
                                                        @elseif (str_contains($item['jenis'], 'Survey'))
                                                            <div
                                                                class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-compass text-red-600 dark:text-red-400"></i>
                                                            </div>
                                                        @elseif (str_contains($item['jenis'], 'Konsultasi'))
                                                            <div
                                                                class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-comments text-yellow-600 dark:text-yellow-400"></i>
                                                            </div>
                                                        @elseif (str_contains($item['jenis'], 'Kunjungan'))
                                                            <div
                                                                class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-users text-orange-600 dark:text-orange-400"></i>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="w-10 h-10 bg-gray-100 dark:bg-gray-900/30 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-file text-gray-600 dark:text-gray-400"></i>
                                                            </div>
                                                        @endif
                                                        <span
                                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['jenis'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $status = $item['status'] ?? 'Menunggu';
                                                        $approvedStatuses = ['approved', 'Approved', 'Diterima', 'Disetujui', 'Alat Siap Diambil', 'Alat Dibawa', 'Dikirim', 'Selesai', 'Dikembalikan'];
                                                        $rejectedStatuses = ['rejected', 'Rejected', 'Ditolak'];
                                                        $processingStatuses = ['Diproses'];
                                                    @endphp

                                                    @if (in_array($status, $approvedStatuses))
                                                        <span
                                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full">
                                                            <i class="fas fa-check-circle"></i> {{ $status }}
                                                        </span>
                                                    @elseif (in_array($status, $rejectedStatuses))
                                                        <span
                                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-full">
                                                            <i class="fas fa-times-circle"></i> {{ $status }}
                                                        </span>
                                                    @elseif (in_array($status, $processingStatuses))
                                                        <span
                                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                                            <i class="fas fa-clock"></i> {{ $status }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                                                            <i class="fas fa-hourglass-half"></i> {{ $status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="text-sm text-gray-600 dark:text-gray-400">{{ $item['tanggal']->format('d M Y, H:i') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Load More Button --}}
                        @if ($totalPermohonan > 5)
                            <div class="mt-6 text-center">
                                <button id="load-more-btn"
                                    class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition duration-300 shadow-md hover:shadow-lg"
                                    onclick="loadMorePermohonan()" data-offset="5" data-total="{{ $totalPermohonan }}">
                                    <i class="fas fa-chevron-down"></i> Muat Lebih Banyak
                                </button>
                            </div>
                        @endif
                </div>

                {{-- Shared Detail Modal --}}
                <div id="permohonan-detail-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
                        {{-- Modal Header --}}
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                                        <i id="modal-icon" class="fas fa-file-alt text-2xl text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold opacity-80">Detail Permohonan</p>
                                        <h3 id="modal-jenis" class="text-lg font-bold">-</h3>
                                    </div>
                                </div>
                                <button onclick="closeDetailModal()" class="text-white/70 hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Body --}}
                        <div id="modal-body-detail" class="p-6 space-y-5 max-h-[60vh] overflow-y-auto">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</span>
                                <span id="modal-status-badge" class="px-3 py-1 rounded-full font-semibold text-xs">-</span>
                            </div>
                            <div id="modal-detail-fields" class="space-y-3"></div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Tanggal Pengajuan</p>
                                <p id="modal-tanggal" class="text-sm font-semibold text-gray-900 dark:text-white">-</p>
                            </div>
                        </div>

                        {{-- Delete Confirmation (hidden by default) --}}
                        <div id="modal-body-confirm" class="hidden p-6 text-center">
                            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Hapus Permohonan?</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Yakin ingin menghapus permohonan <strong id="confirm-jenis"></strong>?</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            {{-- Normal footer --}}
                            <div id="modal-footer-normal" class="flex gap-3">
                                <button onclick="closeDetailModal()" class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition text-sm">
                                    <i class="fas fa-times mr-2"></i>Tutup
                                </button>
                                <button onclick="showDeleteConfirm()" class="flex-1 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition text-sm shadow">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </div>
                            {{-- Confirm delete footer --}}
                            <div id="modal-footer-confirm" class="hidden flex gap-3">
                                <button onclick="hideDeleteConfirm()" class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition text-sm">
                                    Batal
                                </button>
                                <button id="confirm-delete-btn" onclick="executeDelete()" class="flex-1 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition text-sm shadow">
                                    <i class="fas fa-trash mr-2"></i>Ya, Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                        @if ($resultCount > 5)
                            <div class="mt-6 text-center">
                                <button id="load-more-btn"
                                    class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition duration-300 shadow-md hover:shadow-lg"
                                    onclick="loadMorePermohonan()" data-offset="5" data-total="{{ $resultCount }}">
                                    <i class="fas fa-chevron-down"></i> Muat Lebih Banyak
                                </button>
                            </div>
                        @endif

                        @elseif (isset($search) && $search)
                            <div
                                class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-12 text-center">
                                <div class="mb-4">
                                    <i class="fas fa-search text-5xl text-gray-300 dark:text-gray-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tidak Ditemukan</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">Tidak ada permohonan yang sesuai dengan kata kunci "<strong>{{ $search }}</strong>".</p>
                                <a href="{{ route('dashboard-pelayanan') }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition duration-300">
                                    Bersihkan Pencarian
                                </a>
                            </div>
                        @endif

                    @else
                        <div
                            class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200 dark:border-blue-700 rounded-2xl p-12 text-center">
                            <div class="mb-4">
                                <i class="fas fa-inbox text-5xl text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-2">Belum Ada Permohonan</h3>
                            <p class="text-blue-800 dark:text-blue-200 mb-6 max-w-md mx-auto">Anda belum membuat permohonan
                                layanan apapun. Mulailah dengan memilih salah satu layanan di atas!</p>
                            <a href="#layanan"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300">
                                <i class="fas fa-arrow-up"></i> Lihat Layanan
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Info Section -->
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-8 border border-green-200 dark:border-green-700">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lightbulb text-lg text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-green-900 dark:text-green-100 mb-2">
                                Informasi Penting
                            </h4>
                            <p class="text-green-800 dark:text-green-200 text-sm leading-relaxed">
                                Semua layanan yang kami sediakan telah disesuaikan dengan peraturan perundang-undangan yang
                                berlaku.
                                Untuk informasi lebih lanjut atau konsultasi, silakan hubungi tim kami melalui fitur kontak
                                yang tersedia.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="container mx-auto mt-10">

                </div>
            </div>

    <!-- Service Selection Modal -->
    <div id="service-selection-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeServiceSelectionModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100 dark:border-gray-700">
                <!-- Modal Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-700 dark:to-emerald-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-plus-circle text-2xl text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Pilih Layanan</h3>
                                <p class="text-green-50 text-sm opacity-90">Pilih jenis permohonan yang ingin Anda ajukan</p>
                            </div>
                        </div>
                        <button onclick="closeServiceSelectionModal()" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-xl">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- 1. Sewa Alat -->
                        <a href="{{ url('/layanan/sewa-alat') }}" class="group p-5 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border-2 border-transparent hover:border-blue-500 transition-all duration-300">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4 group-hover:scale-110 transition">
                                <i class="fas fa-tools text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Sewa Alat</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Penyewaan alat meteorologi & geofisika</p>
                        </a>

                        <!-- 2. Magang -->
                        <a href="{{ url('/layanan/pelayanan-jasa#magang') }}" class="group p-5 bg-purple-50 dark:bg-purple-900/20 rounded-2xl border-2 border-transparent hover:border-purple-500 transition-all duration-300">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/40 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-4 group-hover:scale-110 transition">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Magang</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Program magang & penelitian mahasiswa</p>
                        </a>

                        <!-- 3. Klaim Asuransi -->
                        <a href="{{ url('/layanan/pelayanan-jasa#asuransi') }}" class="group p-5 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border-2 border-transparent hover:border-amber-500 transition-all duration-300">
                            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/40 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400 mb-4 group-hover:scale-110 transition">
                                <i class="fas fa-file-invoice-dollar text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Klaim Asuransi</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Informasi untuk keperluan klaim asuransi</p>
                        </a>

                        <!-- 4. Layanan Data -->
                        <a href="{{ url('/layanan/pelayanan-jasa#data') }}" class="group p-5 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border-2 border-transparent hover:border-indigo-500 transition-all duration-300">
                            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-4 group-hover:scale-110 transition">
                                <i class="fas fa-database text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Layanan Data</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Permintaan data hasil observasi geofisika</p>
                        </a>

                        <!-- 5. Layanan Survey -->
                        <a href="{{ url('/layanan/pelayanan-jasa#survey') }}" class="group p-5 bg-red-50 dark:bg-red-900/20 rounded-2xl border-2 border-transparent hover:border-red-500 transition-all duration-300">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/40 rounded-xl flex items-center justify-center text-red-600 dark:text-red-400 mb-4 group-hover:scale-110 transition">
                                <i class="fas fa-compass text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Layanan Survey</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Jasa survey geofisika di lapangan</p>
                        </a>

                        <!-- 6. Jasa Konsultasi -->
                        <a href="{{ url('/layanan/pelayanan-jasa#konsultasi') }}" class="group p-5 bg-yellow-50 dark:bg-yellow-900/20 rounded-2xl border-2 border-transparent hover:border-yellow-500 transition-all duration-300">
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/40 rounded-xl flex items-center justify-center text-yellow-600 dark:text-yellow-400 mb-4 group-hover:scale-110 transition">
                                <i class="fas fa-comments text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Jasa Konsultasi</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Konsultasi teknis dengan ahli geofisika</p>
                        </a>

                        <!-- 7. Permohonan Kunjungan -->
                        <a href="{{ url('/layanan/permohonan-kunjungan') }}" class="group p-5 bg-orange-50 dark:bg-orange-900/20 rounded-2xl border-2 border-transparent hover:border-orange-500 transition-all duration-300">
                            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/40 rounded-xl flex items-center justify-center text-orange-600 dark:text-orange-400 mb-4 group-hover:scale-110 transition">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">Kunjungan</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Permohonan kunjungan edukasi instansi/umum</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
            let isLoading = false;
            let currentDeleteUrl = null;
            let searchTimer = null;

            // ─── Search logic ─────────────────────────────────────────────
            const searchInput = document.getElementById('permohonan-search');
            if (searchInput) {
                // Focus search input if there is an active search
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('search')) {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val; // Move cursor to end
                }

                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value;
                    
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        performSearch(query);
                    }, 1000); // 1s debounce for typing
                });

                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch(this.value);
                    }
                });
            }

            function triggerSearch() {
                const query = document.getElementById('permohonan-search')?.value;
                performSearch(query);
            }

            function performSearch(query) {
                const url = new URL(window.location.href);
                if (query) {
                    url.searchParams.set('search', query);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }

            function openServiceSelectionModal() {
                document.getElementById('service-selection-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeServiceSelectionModal() {
                document.getElementById('service-selection-modal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // ─── Modal helpers ────────────────────────────────────────────
            function openDetailModal(row) {
                const jenis     = row.dataset.jenis;
                const status    = row.dataset.status;
                const tanggal   = row.dataset.tanggal;
                const deleteUrl = row.dataset.deleteUrl;
                const detail    = JSON.parse(row.dataset.detail || '{}');

                currentDeleteUrl = deleteUrl;

                // Set header
                document.getElementById('modal-jenis').textContent = jenis;
                document.getElementById('confirm-jenis').textContent = jenis;

                // Set icon
                const iconMap = {
                    'Sewa': 'fa-tools', 'Magang': 'fa-graduation-cap',
                    'Asuransi': 'fa-file-invoice-dollar', 'Data': 'fa-database',
                    'Survey': 'fa-compass', 'Konsultasi': 'fa-comments',
                    'Kunjungan': 'fa-users'
                };
                const icon = document.getElementById('modal-icon');
                icon.className = 'fas fa-file-alt text-2xl text-white';
                for (const [key, cls] of Object.entries(iconMap)) {
                    if (jenis.includes(key)) { icon.className = `fas ${cls} text-2xl text-white`; break; }
                }

                // Set status badge
                const badge = document.getElementById('modal-status-badge');
                const approvedStatuses = ['Diterima','Disetujui','Alat Siap Diambil','Alat Dibawa','Dikirim','Selesai','Dikembalikan'];
                const rejectedStatuses = ['Ditolak','rejected'];
                const processingStatuses = ['Diproses'];
                if (approvedStatuses.some(s => status.includes(s))) {
                    badge.className = 'px-3 py-1 rounded-full font-semibold text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300';
                } else if (rejectedStatuses.some(s => status.includes(s))) {
                    badge.className = 'px-3 py-1 rounded-full font-semibold text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300';
                } else if (processingStatuses.some(s => status.includes(s))) {
                    badge.className = 'px-3 py-1 rounded-full font-semibold text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300';
                } else {
                    badge.className = 'px-3 py-1 rounded-full font-semibold text-xs bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300';
                }
                badge.textContent = status;

                // Set detail fields
                const fieldsContainer = document.getElementById('modal-detail-fields');
                fieldsContainer.innerHTML = '';
                const entries = Object.entries(detail);
                // Group in pairs
                for (let i = 0; i < entries.length; i += 2) {
                    const pair = document.createElement('div');
                    pair.className = 'grid grid-cols-2 gap-3';
                    for (let j = i; j < Math.min(i + 2, entries.length); j++) {
                        const [label, value] = entries[j];
                        pair.innerHTML += `<div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">${label}</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">${value}</p>
                        </div>`;
                    }
                    fieldsContainer.appendChild(pair);
                }

                // Set tanggal
                document.getElementById('modal-tanggal').textContent = tanggal;

                // Reset to detail view
                hideDeleteConfirm();

                // Show modal
                document.getElementById('permohonan-detail-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeDetailModal() {
                document.getElementById('permohonan-detail-modal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentDeleteUrl = null;
                hideDeleteConfirm();
            }

            function showDeleteConfirm() {
                document.getElementById('modal-body-detail').classList.add('hidden');
                document.getElementById('modal-body-confirm').classList.remove('hidden');
                document.getElementById('modal-footer-normal').classList.add('hidden');
                document.getElementById('modal-footer-confirm').classList.remove('hidden');
            }

            function hideDeleteConfirm() {
                document.getElementById('modal-body-detail').classList.remove('hidden');
                document.getElementById('modal-body-confirm').classList.add('hidden');
                document.getElementById('modal-footer-normal').classList.remove('hidden');
                document.getElementById('modal-footer-confirm').classList.add('hidden');
            }

            function executeDelete() {
                if (!currentDeleteUrl) return;

                const btn = document.getElementById('confirm-delete-btn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    || document.querySelector('input[name="_token"]')?.value;

                if (!csrfToken) { alert('CSRF token tidak ditemukan'); btn.disabled = false; return; }

                fetch(currentDeleteUrl, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(res => res.json().then(data => ({ ok: res.ok, data })))
                .then(result => {
                    if (result.ok) {
                        closeDetailModal();
                        window.location.reload();
                    } else {
                        alert(result.data?.message || 'Gagal menghapus permohonan');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash mr-2"></i>Ya, Hapus';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error: ' + err.message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash mr-2"></i>Ya, Hapus';
                });
            }

            // Close modal when clicking backdrop
            document.getElementById('permohonan-detail-modal')?.addEventListener('click', function(e) {
                if (e.target === this) closeDetailModal();
            });

            // ─── Load More ────────────────────────────────────────────────
            function loadMorePermohonan() {
                const button = document.getElementById('load-more-btn');
                const offset = parseInt(button.getAttribute('data-offset'));
                const search = document.getElementById('permohonan-search')?.value || '';

                if (isLoading) return;

                isLoading = true;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...';

                fetch(`/dashboard-pelayanan/load-more?offset=${offset}&search=${encodeURIComponent(search)}`)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('permohonan-table-body');
                        let rowNumber = offset + 1;

                        data.permohonan.forEach((item) => {
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 cursor-pointer';
                            row.onclick = function() { openDetailModal(this); };
                            row.dataset.id        = item.id;
                            row.dataset.jenis     = item.jenis;
                            row.dataset.status    = item.status;
                            row.dataset.deleteUrl = item.delete_url;
                            row.dataset.detail    = JSON.stringify(item.detail || {});

                            // Format date
                            const dateObj = new Date(item.tanggal);
                            const options = { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: false };
                            const formattedDate = dateObj.toLocaleDateString('id-ID', options).replace(',', ',');
                            row.dataset.tanggal = formattedDate;

                            let statusBadge = '';
                            const status = item.status || 'Menunggu';
                            const approvedStatuses = ['Diterima','Disetujui','Alat Siap Diambil','Alat Dibawa','Dikirim','Selesai','Dikembalikan'];
                            const rejectedStatuses = ['Ditolak','rejected'];
                            const processingStatuses = ['Diproses'];
                            if (approvedStatuses.some(s => status.includes(s))) {
                                statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full"><i class="fas fa-check-circle"></i> ${status}</span>`;
                            } else if (rejectedStatuses.some(s => status.includes(s))) {
                                statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-full"><i class="fas fa-times-circle"></i> ${status}</span>`;
                            } else if (processingStatuses.some(s => status.includes(s))) {
                                statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full"><i class="fas fa-clock"></i> ${status}</span>`;
                            } else {
                                statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full"><i class="fas fa-hourglass-half"></i> ${status}</span>`;
                            }

                            let iconBg = 'bg-gray-100 dark:bg-gray-900/30';
                            let iconClass = 'fas fa-file text-gray-600 dark:text-gray-400';
                            if (item.jenis.includes('Sewa'))        { iconBg = 'bg-blue-100 dark:bg-blue-900/30';   iconClass = 'fas fa-tools text-blue-600 dark:text-blue-400'; }
                            else if (item.jenis.includes('Magang')) { iconBg = 'bg-purple-100 dark:bg-purple-900/30'; iconClass = 'fas fa-graduation-cap text-purple-600 dark:text-purple-400'; }
                            else if (item.jenis.includes('Asuransi')) { iconBg = 'bg-amber-100 dark:bg-amber-900/30'; iconClass = 'fas fa-file-invoice-dollar text-amber-600 dark:text-amber-400'; }
                            else if (item.jenis.includes('Data'))   { iconBg = 'bg-indigo-100 dark:bg-indigo-900/30'; iconClass = 'fas fa-database text-indigo-600 dark:text-indigo-400'; }
                            else if (item.jenis.includes('Survey')) { iconBg = 'bg-red-100 dark:bg-red-900/30';     iconClass = 'fas fa-compass text-red-600 dark:text-red-400'; }
                            else if (item.jenis.includes('Konsultasi')) { iconBg = 'bg-yellow-100 dark:bg-yellow-900/30'; iconClass = 'fas fa-comments text-yellow-600 dark:text-yellow-400'; }
                            else if (item.jenis.includes('Kunjungan')) { iconBg = 'bg-orange-100 dark:bg-orange-900/30'; iconClass = 'fas fa-users text-orange-600 dark:text-orange-400'; }

                            row.innerHTML = `
                                <td class="px-6 py-4"><span class="text-sm font-semibold text-gray-900 dark:text-white">${rowNumber}</span></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 ${iconBg} rounded-lg flex items-center justify-center"><i class="${iconClass}"></i></div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">${item.jenis}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">${statusBadge}</td>
                                <td class="px-6 py-4"><span class="text-sm text-gray-600 dark:text-gray-400">${formattedDate}</span></td>
                            `;

                            tableBody.appendChild(row);
                            rowNumber++;
                        });

                        const newOffset = offset + data.count;
                        button.setAttribute('data-offset', newOffset);

                        if (!data.hasMore) {
                            button.style.display = 'none';
                        } else {
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-chevron-down mr-2"></i> Muat Lebih Banyak';
                        }

                        isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-chevron-down mr-2"></i> Muat Lebih Banyak';
                        isLoading = false;
                        alert('Terjadi kesalahan saat memuat data');
                    });
            }
        </script>
@endpush
