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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Sewa Alat</h3>
                <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $sewa_alat->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pelayanan Jasa</h3>
                <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $magang->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Permohonan Kunjungan</h3>
                <div class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ $asuransi->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">permohonan</p>
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