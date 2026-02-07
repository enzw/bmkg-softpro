@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <div class="container px-4 mx-auto py-10">
        <!-- Header Section -->
        <div class="mt-12 mb-10">
            <div class="mb-8">
                <h1 class="text-5xl font-black text-gray-900 dark:text-white mb-2">
                    Dashboard Pelayanan
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Selamat datang, <span class="font-semibold text-green-600 dark:text-green-400">{{ Auth::user()->name }}</span>! 
                </p>
            </div>


        <!-- Services Section -->
        <div class="mb-16" id="layanan">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Layanan Tersedia</h2>
                <p class="text-gray-600 dark:text-gray-400">Pilih layanan yang ingin Anda gunakan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($layanan as $item)
                <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 hover:-translate-y-2 flex flex-col h-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden h-56 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600">
                        <img 
                            src="{{ asset($item['images']) }}" 
                            alt="{{ $item['nama'] }}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="mb-3 flex items-center gap-2">
                            @if (str_contains($item['url'], 'sewa-alat'))
                                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-full">
                                    <i class="fas fa-tools mr-1"></i> Sewa Alat
                                </span>
                            @elseif (str_contains($item['url'], 'pelayanan-jasa'))
                                <span class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-semibold rounded-full">
                                    <i class="fas fa-briefcase mr-1"></i> Layanan Jasa
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-semibold rounded-full">
                                    <i class="fas fa-users mr-1"></i> Kunjungan
                                </span>
                            @endif
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 dark:group-hover:text-green-400 transition duration-300">
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

        <!-- History Section -->
        <div class="mb-16">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Riwayat Permohonan</h2>
                <p class="text-gray-600 dark:text-gray-400">Pantau status permohonan layanan Anda</p>
            </div>
            
            @if (count($permohonan) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-700 dark:to-emerald-700">
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">No</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Jenis Permohonan</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-white">Tanggal Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="permohonan-table-body">
                                @foreach ($permohonan as $index => $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if (str_contains($item['jenis'], 'Sewa'))
                                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-tools text-blue-600 dark:text-blue-400"></i>
                                                    </div>
                                                @elseif (str_contains($item['jenis'], 'Magang'))
                                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-graduation-cap text-purple-600 dark:text-purple-400"></i>
                                                    </div>
                                                @elseif (str_contains($item['jenis'], 'Asuransi'))
                                                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-file-invoice-dollar text-amber-600 dark:text-amber-400"></i>
                                                    </div>
                                                @elseif (str_contains($item['jenis'], 'Data'))
                                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-database text-indigo-600 dark:text-indigo-400"></i>
                                                    </div>
                                                @elseif (str_contains($item['jenis'], 'Survey'))
                                                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-compass text-red-600 dark:text-red-400"></i>
                                                    </div>
                                                @elseif (str_contains($item['jenis'], 'Konsultasi'))
                                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-comments text-yellow-600 dark:text-yellow-400"></i>
                                                    </div>
                                                @elseif (str_contains($item['jenis'], 'Kunjungan'))
                                                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-users text-orange-600 dark:text-orange-400"></i>
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-900/30 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-file text-gray-600 dark:text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['jenis'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $status = $item['status'] ?? 'Menunggu';
                                                $approvedStatuses = ['approved', 'Approved', 'Diterima', 'Disetujui', 'Alat Siap Diambil', 'Alat Dibawa', 'Dikirim', 'Selesai'];
                                                $rejectedStatuses = ['rejected', 'Rejected', 'Ditolak'];
                                                $processingStatuses = ['Diproses'];
                                            @endphp
                                            
                                            @if (in_array($status, $approvedStatuses))
                                                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full">
                                                    <i class="fas fa-check-circle"></i> {{ $status }}
                                                </span>
                                            @elseif (in_array($status, $rejectedStatuses))
                                                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-full">
                                                    <i class="fas fa-times-circle"></i> {{ $status }}
                                                </span>
                                            @elseif (in_array($status, $processingStatuses))
                                                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                                    <i class="fas fa-clock"></i> {{ $status }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                                                    <i class="fas fa-hourglass-half"></i> {{ $status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $item['tanggal']->format('d M Y, H:i') }}</span>
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
                            onclick="loadMorePermohonan()"
                            data-offset="5"
                            data-total="{{ $totalPermohonan }}">
                            <i class="fas fa-chevron-down"></i> Muat Lebih Banyak
                        </button>
                    </div>
                @endif
            @else
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200 dark:border-blue-700 rounded-2xl p-12 text-center">
                    <div class="mb-4">
                        <i class="fas fa-inbox text-5xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-2">Belum Ada Permohonan</h3>
                    <p class="text-blue-800 dark:text-blue-200 mb-6 max-w-md mx-auto">Anda belum membuat permohonan layanan apapun. Mulailah dengan memilih salah satu layanan di atas!</p>
                    <a href="#layanan" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300">
                        <i class="fas fa-arrow-up"></i> Lihat Layanan
                    </a>
                </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-8 border border-green-200 dark:border-green-700">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lightbulb text-lg text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-green-900 dark:text-green-100 mb-2">
                        Informasi Penting
                    </h4>
                    <p class="text-green-800 dark:text-green-200 text-sm leading-relaxed">
                        Semua layanan yang kami sediakan telah disesuaikan dengan peraturan perundang-undangan yang berlaku. 
                        Untuk informasi lebih lanjut atau konsultasi, silakan hubungi tim kami melalui fitur kontak yang tersedia.
                    </p>
                </div>
            </div>
        </div>

        <div class="container mx-auto mt-10">

    </div>
</div>
@endsection
<script>
    let isLoading = false;

    function loadMorePermohonan() {
        const button = document.getElementById('load-more-btn');
        const offset = parseInt(button.getAttribute('data-offset'));
        const total = parseInt(button.getAttribute('data-total'));

        if (isLoading) return;

        isLoading = true;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...';

        fetch(`/dashboard-pelayanan/load-more?offset=${offset}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('permohonan-table-body');
                let rowNumber = offset + 1; // Start row numbering from offset
                
                // Add new rows
                data.permohonan.forEach((item) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150';

                    let statusBadge = '';
                    const status = item.status || 'Menunggu';
                    const approvedStatuses = ['approved', 'Approved', 'Diterima', 'Disetujui', 'Alat Siap Diambil', 'Alat Dibawa', 'Dikirim', 'Selesai'];
                    const rejectedStatuses = ['rejected', 'Rejected', 'Ditolak'];
                    const processingStatuses = ['Diproses'];

                    if (approvedStatuses.includes(status)) {
                        statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <i class="fas fa-check-circle"></i> ${status}
                        </span>`;
                    } else if (rejectedStatuses.includes(status)) {
                        statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <i class="fas fa-times-circle"></i> ${status}
                        </span>`;
                    } else if (processingStatuses.includes(status)) {
                        statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                            <i class="fas fa-clock"></i> ${status}
                        </span>`;
                    } else {
                        statusBadge = `<span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                            <i class="fas fa-hourglass-half"></i> ${status}
                        </span>`;
                    }

                    let iconBg = '';
                    let iconClass = '';
                    
                    if (item.jenis.includes('Sewa')) {
                        iconBg = 'bg-blue-100 dark:bg-blue-900/30';
                        iconClass = 'fas fa-tools text-blue-600 dark:text-blue-400';
                    } else if (item.jenis.includes('Magang')) {
                        iconBg = 'bg-purple-100 dark:bg-purple-900/30';
                        iconClass = 'fas fa-graduation-cap text-purple-600 dark:text-purple-400';
                    } else if (item.jenis.includes('Asuransi')) {
                        iconBg = 'bg-amber-100 dark:bg-amber-900/30';
                        iconClass = 'fas fa-file-invoice-dollar text-amber-600 dark:text-amber-400';
                    } else if (item.jenis.includes('Data')) {
                        iconBg = 'bg-indigo-100 dark:bg-indigo-900/30';
                        iconClass = 'fas fa-database text-indigo-600 dark:text-indigo-400';
                    } else if (item.jenis.includes('Survey')) {
                        iconBg = 'bg-red-100 dark:bg-red-900/30';
                        iconClass = 'fas fa-compass text-red-600 dark:text-red-400';
                    } else if (item.jenis.includes('Konsultasi')) {
                        iconBg = 'bg-yellow-100 dark:bg-yellow-900/30';
                        iconClass = 'fas fa-comments text-yellow-600 dark:text-yellow-400';
                    } else if (item.jenis.includes('Kunjungan')) {
                        iconBg = 'bg-orange-100 dark:bg-orange-900/30';
                        iconClass = 'fas fa-users text-orange-600 dark:text-orange-400';
                    } else {
                        iconBg = 'bg-gray-100 dark:bg-gray-900/30';
                        iconClass = 'fas fa-file text-gray-600 dark:text-gray-400';
                    }

                    // Format date to match initial rows format: "07 Feb 2026, 20:25"
                    const dateObj = new Date(item.tanggal);
                    const options = { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: false };
                    const formattedDate = dateObj.toLocaleDateString('id-ID', options).replace(',', ',');

                    row.innerHTML = `
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">${rowNumber}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 ${iconBg} rounded-lg flex items-center justify-center">
                                    <i class="${iconClass}"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${item.jenis}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">${statusBadge}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">${formattedDate}</span>
                        </td>
                    `;

                    tableBody.appendChild(row);
                    rowNumber++;
                });

                // Update offset
                const newOffset = offset + data.count;
                button.setAttribute('data-offset', newOffset);

                // Check if there are more items
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