@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Pratinjau Data: {{ $serviceName }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Total: <strong>{{ $count }}</strong> data
                        @if($startDate || $endDate)
                            | Filter: 
                            @if($startDate && $endDate)
                                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                            @elseif($startDate)
                                Dari {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                            @elseif($endDate)
                                Hingga {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                            @endif
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.download-area.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 text-white bg-red-600 rounded-lg hover:bg-red-700 transition font-semibold">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm dark:shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700/50">
            @if($count > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Pengguna</th>
                                @if($service === 'sewa-alat')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Pemesan</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Alat</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Unit</th>
                                @elseif($service === 'kunjungan')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Jenis Kunjungan</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Instansi</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap</th>
                                @elseif($service === 'magang')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Universitas</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Program Studi</th>
                                @elseif($service === 'asuransi')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Lokasi</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Perusahaan</th>
                                @elseif($service === 'layanan-data')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">No. WhatsApp</th>
                                @elseif($service === 'survey')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">No. WhatsApp</th>
                                @elseif($service === 'jasa-konsultasi')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">No. WhatsApp</th>
                                @endif
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($data as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        #{{ $item->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->user->name ?? 'N/A' }}</span>
                                    </td>
                                    @if($service === 'sewa-alat')
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->alat->nama ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->banyak_unit ?? 'N/A' }}</td>
                                    @elseif($service === 'kunjungan')
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->jenis_kunjungan ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_instansi ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_lengkap ?? 'N/A' }}</td>
                                    @elseif($service === 'magang')
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_lengkap ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->universitas ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->prodi ?? 'N/A' }}</td>
                                    @elseif($service === 'asuransi')
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_user ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->lokasi ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->perusahaan ?? 'N/A' }}</td>
                                    @elseif($service === 'layanan-data')
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_lengkap ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->no_whatsapp ?? 'N/A' }}</td>
                                    @elseif($service === 'survey')
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_lengkap ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->no_whatsapp ?? 'N/A' }}</td>
                                    @elseif($service === 'jasa-konsultasi')
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_lengkap ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->no_whatsapp ?? 'N/A' }}</td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                            @if($item->status === 'pending' || $item->status === 'Menunggu')
                                                bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200
                                            @elseif($item->status === 'approved' || $item->status === 'Diproses')
                                                bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200
                                            @elseif($item->status === 'completed' || $item->status === 'Selesai')
                                                bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200
                                            @elseif($item->status === 'rejected')
                                                bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200
                                            @else
                                                bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                            @endif
                                        ">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->created_at?->format('d M Y H:i') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Download Button -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex gap-4">
                    <form action="{{ route('admin.download-area.download') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="service" value="{{ $service }}">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                            <i class="fas fa-download"></i>
                            Download Excel
                        </button>
                    </form>
                    <a href="{{ route('admin.download-area.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition font-semibold">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">Tidak ada data ditemukan</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                        Tidak ada data untuk layanan dan rentang tanggal yang dipilih
                    </p>
                    <a href="{{ route('admin.download-area.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
