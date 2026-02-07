@extends('layouts.admin')

@section('content')
    <!-- Header -->
    <div class="mb-10 flex items-start justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Halo, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 dark:text-gray-400">Monitor aktivitas layanan Anda. Anda hampir mencapai target!</p>
        </div>
        <p class="text-sm text-gray-400 dark:text-gray-500">{{ date('d M Y') }}</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Total Permohonan</p>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $statistik['total'] }}</p>
                </div>
                <p class="text-sm {{ $statistik['total_change'] > 0 ? 'text-green-500' : 'text-gray-400' }}">
                    <i class="fas fa-arrow-{{ $statistik['total_change'] > 0 ? 'up' : 'right' }} mr-1"></i>
                    {{ $statistik['total_change'] }}
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Dalam Proses</p>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $statistik['total_diproses'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 dark:text-gray-500">dari {{ $statistik['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Tingkat Penyelesaian</p>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-4xl font-bold text-green-600 dark:text-green-400">
                        {{ $statistik['total'] > 0 ? round(($statistik['total_selesai'] / $statistik['total']) * 100) : 0 }}%
                    </p>
                </div>
                <p class="text-sm {{ $statistik['completion_rate_change'] > 0 ? 'text-green-500' : 'text-gray-400' }}">
                    <i class="fas fa-arrow-{{ $statistik['completion_rate_change'] > 0 ? 'up' : 'right' }} mr-1"></i>{{ $statistik['completion_rate_change'] }}%
                </p>
            </div>
        </div>
    </div>


    <!-- Performance Chart -->
    <div class="mb-10 bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Permohonan</h2>
            <select id="dateRange" class="text-sm px-3 py-1 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                <option value="7d">7 hari terakhir</option>
                <option value="30d">30 hari terakhir</option>
                <option value="90d">90 hari terakhir</option>
            </select>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Distribution -->
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
            <!-- Service Distribution -->
            <div class="h-64">
                <canvas id="serviceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Permohonan Terbaru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $statistik['total'] }} total</p>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @php
                // Collect all requests and sort by date (most recent first)
                $allRequests = collect();
                foreach (['sewa_alat' => 'Sewa Alat', 'magang' => 'Magang', 'kunjungan' => 'Kunjungan', 'jasa_konsultasi' => 'Konsultasi', 'asuransi' => 'Asuransi', 'survey' => 'Survey', 'layanan_data' => 'Layanan Data'] as $key => $name) {
                    $collection = $$key;
                    foreach ($collection as $item) {
                        $allRequests->push([
                            'id' => $item->id,
                            'service' => $name,
                            'status' => $item->status ?? 'menunggu',
                            'created_at' => $item->created_at ?? now(),
                            'type' => $key
                        ]);
                    }
                }
                $recentRequests = $allRequests->sortByDesc('created_at')->take(10);
            @endphp

            @forelse($recentRequests as $request)
                <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition flex items-center justify-between">
                    <div class="flex items-center gap-4 flex-1">
                        <div @class([
                            'w-3 h-3 rounded-full',
                            'bg-amber-400' => $request['status'] === 'menunggu',
                            'bg-blue-400' => $request['status'] === 'diproses',
                            'bg-green-400' => $request['status'] === 'selesai',
                            'bg-red-400' => $request['status'] === 'ditolak',
                        ])></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $request['service'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">ID: #{{ $request['id'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium mb-1" @class([
                            'text-amber-600 dark:text-amber-400' => $request['status'] === 'menunggu',
                            'text-blue-600 dark:text-blue-400' => $request['status'] === 'diproses',
                            'text-green-600 dark:text-green-400' => $request['status'] === 'selesai',
                            'text-red-600 dark:text-red-400' => $request['status'] === 'ditolak',
                        ])>
                            @php
                                $statusLabels = ['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
                                echo $statusLabels[$request['status']] ?? 'Menunggu';
                            @endphp
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            @php
                                echo $request['created_at']->diffForHumans();
                            @endphp
                        </p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    <p>Belum ada permohonan</p>
                </div>
            @endforelse
        </div>
    </div>


    <!-- Chart JS Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#d1d5db' : '#6b7280';
            const gridColor = isDark ? 'rgba(75, 85, 99, 0.1)' : 'rgba(209, 213, 219, 0.3)';

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'],
                    datasets: [{
                        data: [
                            {{ $statistik['total_menunggu'] }},
                            {{ $statistik['total_diproses'] }},
                            {{ $statistik['total_selesai'] }},
                            {{ $statistik['total_ditolak'] }}
                        ],
                        backgroundColor: [
                            '#f59e0b',
                            '#3b82f6',
                            '#10b981',
                            '#ef4444'
                        ],
                        borderColor: isDark ? '#1f2937' : '#ffffff',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor, font: { size: 13, weight: '500' }, padding: 20 }
                        }
                    }
                }
            });

            // Service Chart
            const serviceCtx = document.getElementById('serviceChart').getContext('2d');
            new Chart(serviceCtx, {
                type: 'bar',
                data: {
                    labels: ['Sewa Alat', 'Magang', 'Kunjungan', 'Konsultasi', 'Asuransi', 'Survey', 'Data'],
                    datasets: [{
                        label: 'Total Permohonan',
                        data: [
                            {{ $statistik['sewa_alat']['total'] }},
                            {{ $statistik['magang']['total'] }},
                            {{ $statistik['kunjungan']['total'] }},
                            {{ $statistik['jasa_konsultasi']['total'] }},
                            {{ $statistik['asuransi']['total'] }},
                            {{ $statistik['survey']['total'] }},
                            {{ $statistik['layanan_data']['total'] }}
                        ],
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            labels: { color: textColor, font: { size: 13, weight: '500' } } 
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 12 } }
                        },
                        y: {
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: textColor, font: { size: 12 } }
                        }
                    }
                }
            });
        });
    </script>
@endsection
