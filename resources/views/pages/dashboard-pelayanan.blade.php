@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900">
    <div class="container px-4 mx-auto py-10">
        <div class="mt-12 mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Dashboard Pelayanan
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Selamat datang, {{ Auth::user()->name }}! Pilih layanan yang ingin Anda gunakan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($layanan as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg overflow-hidden hover:shadow-lg dark:hover:shadow-xl transition duration-300 hover:-translate-y-2 flex flex-col border border-gray-200 dark:border-gray-700">
                <!-- Image Section -->
                <div class="overflow-hidden h-48 bg-gray-100 dark:bg-gray-700">
                    <img 
                        src="{{ asset($item['images']) }}" 
                        alt="{{ $item['nama'] }}" 
                        class="w-full h-full object-cover hover:scale-110 transition duration-300"
                    >
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                        {{ $item['nama'] }}
                    </h3>

                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-6 flex-grow">
                        {{ $item['deskripsi'] }}
                    </p>

                    <a href="{{ $item['url'] }}" 
                        class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 transform hover:scale-105 text-center">
                        {{ $item['cta'] }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Tabel Permohonan --}}
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Riwayat Permohonan Anda</h2>
            
            @if (count($permohonan) > 0)
                <div class="overflow-x-auto shadow-lg rounded-lg">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-green-600 text-white">
                                <th class="px-6 py-4 text-left font-semibold">No</th>
                                <th class="px-6 py-4 text-left font-semibold">Jenis Permohonan</th>
                                <th class="px-6 py-4 text-left font-semibold">Status</th>
                                <th class="px-6 py-4 text-left font-semibold">Tanggal Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($permohonan as $index => $item)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                    <td class="px-6 py-4 text-gray-900 dark:text-white font-semibold">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-white">
                                        <span class="inline-flex items-center gap-2">
                                            @if (str_contains($item['jenis'], 'Sewa'))
                                                <i class="fas fa-tools text-blue-600"></i>
                                            @elseif (str_contains($item['jenis'], 'Informasi'))
                                                <i class="fas fa-file-alt text-purple-600"></i>
                                            @else
                                                <i class="fas fa-users text-orange-600"></i>
                                            @endif
                                            {{ $item['jenis'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $status = $item['status'] ?? 'Menunggu';
                                            // Approved/Accepted statuses
                                            $approvedStatuses = ['approved', 'Approved', 'Diterima', 'Disetujui', 'Alat Siap Diambil', 'Alat Dibawa', 'Dikirim'];
                                            // Rejected statuses
                                            $rejectedStatuses = ['rejected', 'Rejected', 'Ditolak'];
                                            // Processing statuses
                                            $processingStatuses = ['Diproses', 'Selesai'];
                                        @endphp
                                        
                                        @if (in_array($status, $approvedStatuses))
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 dark:bg-green-900/30 dark:text-green-300 rounded-full">
                                                <i class="fas fa-check-circle mr-2"></i> {{ $status }}
                                            </span>
                                        @elseif (in_array($status, $rejectedStatuses))
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold text-red-800 bg-red-100 dark:bg-red-900/30 dark:text-red-300 rounded-full">
                                                <i class="fas fa-times-circle mr-2"></i> {{ $status }}
                                            </span>
                                        @elseif (in_array($status, $processingStatuses))
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 rounded-full">
                                                <i class="fas fa-spinner mr-2"></i> {{ $status }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold text-yellow-800 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-full">
                                                <i class="fas fa-clock mr-2"></i> {{ $status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-white text-sm">
                                        {{ $item['tanggal']->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center">
                    <i class="fas fa-inbox text-4xl text-blue-600 dark:text-blue-400 mb-4 inline-block"></i>
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Belum Ada Permohonan</h3>
                    <p class="text-blue-800 dark:text-blue-200 mb-4">Anda belum membuat permohonan layanan apapun. Mulailah dengan memilih salah satu layanan di atas!</p>
                    <a href="#" class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300">
                        Buat Permohonan
                    </a>
                </div>
            @endif
        </div>

        <div class="mt-12 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i> Informasi Penting
            </h4>
            <p class="text-blue-800 dark:text-blue-200 text-sm">
                Semua layanan yang kami sediakan telah disesuaikan dengan peraturan perundang-undangan yang berlaku. 
                Untuk informasi lebih lanjut atau konsultasi, silakan hubungi tim kami melalui fitur kontak yang tersedia.
            </p>
        </div>

        <div class="container mx-auto mt-10">

    </div>
</div>
@endsection
