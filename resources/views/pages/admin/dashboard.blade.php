@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Dashboard Admin
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Selamat datang, {{ Auth::user()->name }}! Kelola semua layanan dari sini.
                    </p>
                </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Sewa Alat</h3>
                <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $sewa_alat->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Magang</h3>
                <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $magang->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Permohonan Kunjungan</h3>
                <div class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ $asuransi->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Jasa Konsultasi</h3>
                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{ $jasa_konsultasi->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Peta Sebaran</h3>
                <div class="text-4xl font-bold text-red-600 dark:text-red-400">{{ $pemetaan->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Survey</h3>
                <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ $survey->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Layanan Data</h3>
                <div class="text-4xl font-bold text-cyan-600 dark:text-cyan-400">{{ $layanan_data->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Peta Sebaran</h3>
                <div class="text-4xl font-bold text-pink-600 dark:text-pink-400">{{ $peta_sebaran->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>
        </div>

        <!-- Statistik Permohonan Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Overall Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Total Permohonan</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 dark:text-gray-300">Total Semua Layanan</span>
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $statistik['total'] }}</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-yellow-600 dark:text-yellow-400 font-semibold">⏳ Menunggu</span>
                            <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $statistik['total_menunggu'] }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-blue-600 dark:text-blue-400 font-semibold">⚙ Diproses</span>
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistik['total_diproses'] }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-green-600 dark:text-green-400 font-semibold">✓ Selesai</span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $statistik['total_selesai'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-red-600 dark:text-red-400 font-semibold">✗ Ditolak</span>
                            <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $statistik['total_ditolak'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Persentase Status</h3>
                <div class="space-y-4">
                    @php
                        $menunggu_pct = $statistik['total'] > 0 ? round(($statistik['total_menunggu'] / $statistik['total']) * 100, 1) : 0;
                        $diproses_pct = $statistik['total'] > 0 ? round(($statistik['total_diproses'] / $statistik['total']) * 100, 1) : 0;
                        $selesai_pct = $statistik['total'] > 0 ? round(($statistik['total_selesai'] / $statistik['total']) * 100, 1) : 0;
                        $ditolak_pct = $statistik['total'] > 0 ? round(($statistik['total_ditolak'] / $statistik['total']) * 100, 1) : 0;
                    @endphp
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-yellow-600 dark:text-yellow-400 font-semibold">Menunggu</span>
                            <span class="text-yellow-600 dark:text-yellow-400 font-bold">{{ $menunggu_pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-yellow-500 h-3 rounded-full" style="width: {{ $menunggu_pct }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-blue-600 dark:text-blue-400 font-semibold">Diproses</span>
                            <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $diproses_pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $diproses_pct }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-green-600 dark:text-green-400 font-semibold">Selesai</span>
                            <span class="text-green-600 dark:text-green-400 font-bold">{{ $selesai_pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-green-500 h-3 rounded-full" style="width: {{ $selesai_pct }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-red-600 dark:text-red-400 font-semibold">Ditolak</span>
                            <span class="text-red-600 dark:text-red-400 font-bold">{{ $ditolak_pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-red-500 h-3 rounded-full" style="width: {{ $ditolak_pct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Per Layanan -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Detail Permohonan Per Layanan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Jenis Layanan</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">Total</th>
                            <th class="px-4 py-3 text-center font-semibold text-yellow-600 dark:text-yellow-400">⏳ Menunggu</th>
                            <th class="px-4 py-3 text-center font-semibold text-blue-600 dark:text-blue-400">⚙ Diproses</th>
                            <th class="px-4 py-3 text-center font-semibold text-green-600 dark:text-green-400">✓ Selesai</th>
                            <th class="px-4 py-3 text-center font-semibold text-red-600 dark:text-red-400">✗ Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Sewa Alat</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['sewa_alat']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['sewa_alat']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['sewa_alat']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['sewa_alat']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['sewa_alat']['ditolak'] }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Magang</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['magang']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['magang']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['magang']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['magang']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['magang']['ditolak'] }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Permohonan Kunjungan</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['asuransi']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['asuransi']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['asuransi']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['asuransi']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['asuransi']['ditolak'] }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Jasa Konsultasi</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['jasa_konsultasi']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['jasa_konsultasi']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['jasa_konsultasi']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['jasa_konsultasi']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['jasa_konsultasi']['ditolak'] }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Peta Sebaran</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['peta_sebaran']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['peta_sebaran']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['peta_sebaran']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['peta_sebaran']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['peta_sebaran']['ditolak'] }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Survey</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['survey']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['survey']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['survey']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['survey']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['survey']['ditolak'] }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Layanan Data</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['layanan_data']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['layanan_data']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['layanan_data']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['layanan_data']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['layanan_data']['ditolak'] }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-semibold">Peta Sebaran</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $statistik['peta_sebaran']['total'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">{{ $statistik['peta_sebaran']['menunggu'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">{{ $statistik['peta_sebaran']['diproses'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">{{ $statistik['peta_sebaran']['selesai'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 dark:text-red-400">{{ $statistik['peta_sebaran']['ditolak'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Statistik Permohonan</h3>
            <div class="custom-select mb-6">
                <select id="monthFilter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <!-- Dynamic options will be populated here -->
                </select>
            </div>
            <div class="chart-container-wrapper">
                <div class="y-labels" id="yLabels"></div>
                <div class="chart-container" id="chartContainer"></div>
            </div>
            <div class="x-labels" id="xLabels"></div>
        </div>

        <!-- Rating Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Rating Megabot</h3>
            <div class="space-y-4">
                @foreach ($rating as $star)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $star->value }} 
                            <span class="text-yellow-400">
                                @for($i = 0; $i < $star->value; $i++)
                                    ★
                                @endfor
                            </span>
                        </span>
                    </div>
                    <span class="text-gray-600 dark:text-gray-400">({{ $star->total }} ulasan)</span>
                </div>
                @endforeach
            </div>
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    @foreach ($bintang as $student)
                        Rata-rata: <span class="text-yellow-500">{{ $student->percentage }}/5</span> dari {{ $student->user }} reviewer
                    @endforeach
                </p>
            </div>
        </div>

        <div class="mt-12 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">
                <i class="fas fa-info-circle mr-2"></i> Informasi Penting
            </h4>
            <p class="text-blue-800 dark:text-blue-200 text-sm">
                Dashboard ini menampilkan ringkasan semua permohonan yang masuk. Anda dapat mengelola setiap permohonan melalui menu yang tersedia di sebelah kiri.
            </p>
        </div>
    </div>
</div>

<script>
    // Render chart and Y-axis labels
    function renderChart(data, maxValue) {
        const chartContainer = document.getElementById('chartContainer');
        const xLabels = document.getElementById('xLabels');
        
        chartContainer.innerHTML = ''; // Clear existing bars
        xLabels.innerHTML = ''; // Clear existing X-axis labels

        // Render Y-axis labels dynamically
        let maxValuelabels = Math.ceil(maxValue + (maxValue * 0.2));
        renderYLabels(maxValuelabels);

        // Render bars and labels
        data.forEach(item => {
            // Create a bar
            const bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.height = `${(item.value / maxValuelabels) * 100}%`;

            // Add value inside the bar
            const value = document.createElement('span');
            value.textContent = item.value;
            bar.appendChild(value);

            // Append the bar to the chart container
            chartContainer.appendChild(bar);

            // Create a label for the X-axis
            const label = document.createElement('div');
            label.className = 'x-label';
            label.textContent = item.label;

            // Append the label to the X-axis container
            xLabels.appendChild(label);
        });
    }

    // Render Y-axis labels
    function renderYLabels(maxValue) {
        const yLabels = document.getElementById('yLabels');
        yLabels.innerHTML = ''; // Clear existing labels

        const numberOfLabels = 2; // Number of Y-axis labels
        const step = Math.ceil(maxValue / numberOfLabels);

        for (let i = 0; i <= numberOfLabels; i++) {
            const labelValue = step * i;
            const label = document.createElement('div');
            label.className = 'y-label';
            label.textContent = labelValue;
            yLabels.appendChild(label);
        }
    }

    // Populate the dropdown with the last 6 months
    function populateLast6Months() {
        const monthFilter = document.getElementById('monthFilter');
        const now = new Date();
        const currentMonth = now.getMonth() + 1; // 1-based
        const currentYear = now.getFullYear();

        const months = [];
        for (let i = 0; i < 6; i++) {
            const date = new Date(currentYear, currentMonth - i - 1, 1);
            const month = date.getMonth() + 1; // 1-based
            const year = date.getFullYear();
            const monthName = date.toLocaleString('default', {
                month: 'long'
            });
            months.push({
                month,
                year,
                label: `${monthName} ${year}`
            });
        }

        monthFilter.innerHTML = '';
        months.forEach(({
            month,
            year,
            label
        }) => {
            const option = document.createElement('option');
            option.value = `${month}-${year}`; // Store month and year in value
            option.textContent = label;
            if (month === now.getMonth() + 1 && year === now.getFullYear()) {
                option.selected = true; // Default to the current month
            }
            monthFilter.appendChild(option);
        });
    }

    document.getElementById('monthFilter').addEventListener('change', async (event) => {
        const [month, year] = event.target.value.split('-'); // Split the value into month and year
        const {
            data,
            maxValue
        } = await fetchChartData(month, year); // Fetch data using month and year
        renderChart(data, maxValue);
    });

    async function fetchChartData(month, year) {
        const response = await fetch(`/admin/api/chart-data?month=${month}&year=${year}`);
        return response.json();
    }

    document.addEventListener('DOMContentLoaded', async () => {
        populateLast6Months();

        // Fetch and render data for the default month and year
        const monthFilter = document.getElementById('monthFilter');
        const [month, year] = monthFilter.value.split('-');
        const {
            data,
            maxValue
        } = await fetchChartData(month, year);
        renderChart(data, maxValue);
    });
</script>
@endsection