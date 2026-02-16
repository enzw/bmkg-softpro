@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-2">Pratinjau Export
                </h1>
                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest shadow-sm text-shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                        Preview Mode
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">Layanan:
                        {{ $serviceName }}</span>
                </div>
            </div>
            <a href="{{ route('admin.download-area.index') }}"
                class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-2xl transition-all duration-300 shadow-sm border border-gray-100 dark:border-gray-700 font-bold text-xs uppercase tracking-widest group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Kembali
            </a>
        </div>

        <!-- Meta Information -->
        <div
            class="bg-gray-50/50 dark:bg-gray-900/20 rounded-[2.5rem] p-6 border border-gray-100 dark:border-gray-700/50 flex flex-wrap items-center gap-8">
            <div>
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Total Data</p>
                <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $count }} Entitas
                    Ditemukan</p>
            </div>
            @if($startDate || $endDate)
                <div class="w-px h-8 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
                <div>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Periode Filter</p>
                    <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-tight">
                        @if($startDate && $endDate)
                            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        @elseif($startDate)
                            Mulai {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                        @elseif($endDate)
                            Hingga {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Data Table Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            @if($count > 0)
                <div
                    class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600">
                            <i class="fas fa-table text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Pratinjau Data
                            </h2>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest text-shadow-sm">Menampilkan
                                {{ min($count, 100) }} data teratas</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    ID</th>
                                <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    User Account</th>
                                @if($service === 'sewa-alat')
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Pemesan</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Alat</th>
                                @elseif($service === 'kunjungan')
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Jenis</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Instansi</th>
                                @elseif($service === 'magang')
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Nama</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Universitas</th>
                                @elseif($service === 'asuransi')
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Nama</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Lokasi</th>
                                @elseif($service === 'layanan-data' || $service === 'survey' || $service === 'jasa-konsultasi')
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Nama</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Contact</th>
                                @elseif($service === 'rating')
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Rating</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Label</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Tipe Layanan</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Ulasan</th>
                                @endif
                                <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @foreach($data as $item)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-all duration-300">
                                    <td
                                        class="px-8 py-5 whitespace-nowrap text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        #{{ substr($item->id, 0, 8) }}
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $item->user->name ?? 'N/A' }}</p>
                                    </td>
                                    @if($service === 'sewa-alat')
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400">
                                            {{ $item->nama ?? 'N/A' }}</td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->alat->nama ?? 'N/A' }}</td>
                                    @elseif($service === 'kunjungan')
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->jenis_kunjungan ?? 'N/A' }}</td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->nama_instansi ?? 'N/A' }}</td>
                                    @elseif($service === 'magang')
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->nama_lengkap ?? 'N/A' }}</td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->universitas ?? 'N/A' }}</td>
                                    @elseif($service === 'asuransi')
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->nama_user ?? 'N/A' }}</td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->lokasi ?? 'N/A' }}</td>
                                    @elseif($service === 'layanan-data' || $service === 'survey' || $service === 'jasa-konsultasi')
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ $item->nama_lengkap ?? 'N/A' }}</td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400">
                                            {{ $item->no_whatsapp ?? $item->email ?? 'N/A' }}</td>
                                    @elseif($service === 'rating')
                                        @php
                                            $ratingLabel = match($item->rating) {
                                                5 => 'Sangat Puas',
                                                4 => 'Puas',
                                                3 => 'Cukup Puas',
                                                2 => 'Kurang Puas',
                                                1 => 'Sangat Tidak Puas',
                                                default => 'N/A',
                                            };
                                            $ratingColor = match($item->rating) {
                                                5 => 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300',
                                                4 => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300',
                                                3 => 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300',
                                                2 => 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300',
                                                1 => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300',
                                                default => 'bg-gray-100 dark:bg-gray-900/40 text-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $ratingColor }} font-bold text-xs">
                                                {{ $item->rating }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400">
                                            {{ $ratingLabel }}</td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                            {{ class_basename($item->rateable_type) ?? 'N/A' }}</td>
                                        <td class="px-8 py-5 text-xs font-medium text-gray-600 dark:text-gray-400 line-clamp-2">
                                            {{ $item->review ?? '-' }}</td>
                                    @endif
                                    <td
                                        class="px-8 py-5 whitespace-nowrap text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        {{ $item->created_at?->format('d/m/Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Action -->
                <div
                    class="p-8 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700 flex flex-wrap gap-4">
                    <form action="{{ route('admin.download-area.download') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="service" value="{{ $service }}">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-10 py-5 bg-green-500 hover:bg-green-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-2xl transition-all duration-300 shadow-lg shadow-green-200 dark:shadow-none font-bold text-xs uppercase tracking-widest group">
                            <i class="fas fa-file-excel group-hover:scale-110 transition-transform"></i>
                            Download Document Excel
                        </button>
                    </form>
                    <a href="{{ route('admin.download-area.index') }}"
                        class="px-10 py-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all shadow-sm flex items-center justify-center">
                        Batal
                    </a>
                </div>
            @else
                <div class="p-20 text-center">
                    <div
                        class="w-24 h-24 rounded-[2rem] bg-gray-50 dark:bg-gray-900/40 flex items-center justify-center text-gray-300 dark:text-gray-600 mx-auto mb-6 border-2 border-dashed border-gray-100 dark:border-gray-700">
                        <i class="fas fa-inbox text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-2">Data Tidak
                        Ditemukan</h3>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-8">Tidak ada permohonan yang
                        sesuai dengan filter filter Anda.</p>
                    <a href="{{ route('admin.download-area.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-green-500 hover:bg-green-600 text-white rounded-2xl transition-all duration-300 shadow-lg shadow-green-200 dark:shadow-none font-bold text-xs uppercase tracking-widest">
                        <i class="fas fa-arrow-left"></i>
                        Kembali Ke Filter
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection