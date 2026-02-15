@extends('layouts.admin')

@section('content')
    <!-- Header -->
    <div class="mb-10 flex items-start justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Rating</h1>
            <p class="text-gray-500 dark:text-gray-400">Monitor feedback dan tingkat kepuasan pengguna layanan Anda.</p>
        </div>
        <p class="text-sm text-gray-400 dark:text-gray-500">{{ date('d M Y') }}</p>
    </div>

    <!-- Stats Summary Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-10">
        <!-- Quick Stats -->
        <div class="lg:col-span-4 space-y-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Rata-rata Rating</p>
                <div class="flex items-end justify-between">
                    <div class="flex items-center gap-3">
                        <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $averageRating }}</p>
                        <div class="flex text-amber-400 text-sm">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= round($averageRating) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Total Rating</p>
                <div class="flex items-end justify-between">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $totalRatings }}</p>
                </div>
            </div>

            <!-- Distribution Table style/similar to dashboard -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Distribusi Rating</p>
                <div class="space-y-3">
                    @foreach([5, 4, 3, 2, 1] as $star)
                        @php $pct = $totalRatings > 0 ? ($distribution[$star] / $totalRatings) * 100 : 0; @endphp
                        <div class="flex items-center gap-3 text-sm">
                            <span class="w-12 text-gray-600 dark:text-gray-400 font-medium">{{ $star }} Bintang</span>
                            <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400" style="width: {{ $pct }}%"></div>
                            </div>
                            <span
                                class="w-8 text-right text-gray-500 dark:text-gray-400 text-xs font-bold">{{ $distribution[$star] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Trends Graph -->
        <div class="lg:col-span-8">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition h-full flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tren Kepuasan</h2>
                    <div class="flex border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        @foreach([7, 30, 90] as $d)
                            <button onclick="updateChart({{ $d }})" id="btn-{{ $d }}"
                                class="chart-filter-btn px-3 py-1 text-xs transition-colors {{ $d == 30 ? 'bg-green-600 text-white' : 'bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                                {{ $d }} Hari
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="flex-1 min-h-[350px] relative">
                    <canvas id="ratingLineChart"></canvas>
                    <div id="chartLoader"
                        class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-800/50 hidden">
                        <i class="fa-solid fa-circle-notch fa-spin text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div
            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-900/20">
            <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-tight">Review Terkini</h2>
            <div
                class="px-2 py-0.5 rounded bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-[10px] font-bold uppercase tracking-widest">
                {{ $ratings->count() }} TOTAL
            </div>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($ratings as $rating)
                <div class="px-6 py-8 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all duration-200">
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div
                                class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 font-bold text-lg shadow-inner">
                                {{ substr($rating->user->name ?? 'U', 0, 1) }}
                            </div>
                            <!-- User & Context -->
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $rating->user->name ?? 'Anonymous' }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span
                                        class="text-[9px] font-black text-green-600 dark:text-green-400 uppercase tracking-widest border border-green-200 dark:border-green-800 px-1.5 py-0.5 rounded">
                                        {{ str_replace(['App\\Models\\', 'Permohonan'], '', $rating->rateable_type) }}
                                    </span>
                                    <span class="text-gray-300 dark:text-gray-600 font-thin">|</span>
                                    <span class="text-[10px] font-medium text-gray-400 uppercase tracking-tighter">
                                        {{ $rating->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Rating Stars -->
                        <div class="flex flex-col items-end gap-1.5">
                            <div class="flex text-amber-400 text-[10px] gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= $rating->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                            <span
                                class="text-[10px] font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 px-2 py-0.5 rounded-full border border-gray-200 dark:border-gray-700">
                                {{ $rating->rating }}.0 / 5
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mt-5 ml-16 relative">
                        <i
                            class="fa-solid fa-quote-left absolute -left-8 top-0 text-gray-100 dark:text-gray-700/50 text-3xl -z-10"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-normal italic">
                            "{{ $rating->review ?: 'Pengguna ini tidak meninggalkan pesan ulasan.' }}"
                        </p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center text-gray-500 dark:text-gray-400 bg-gray-50/20 dark:bg-transparent">
                    <i class="fa-solid fa-feather-pointed text-4xl mb-4 opacity-10"></i>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Belum ada feedback untuk ditampilkan
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chart Configuration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let ratingChart;
        const ctx = document.getElementById('ratingLineChart').getContext('2d');
        const loader = document.getElementById('chartLoader');

        function initChart(labels, data) {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#d1d5db' : '#6b7280';
            const gridColor = isDark ? 'rgba(75, 85, 99, 0.1)' : 'rgba(209, 213, 219, 0.3)';

            if (ratingChart) ratingChart.destroy();

            ratingChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rata-rata Rating',
                        data: data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: isDark ? '#1f2937' : '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#4b5563',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            min: 0,
                            max: 5,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        async function updateChart(days) {
            document.querySelectorAll('.chart-filter-btn').forEach(btn => {
                btn.className = "chart-filter-btn px-3 py-1 text-xs transition-colors bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600";
                if (btn.id === `btn-${days}`) {
                    btn.className = "chart-filter-btn px-3 py-1 text-xs transition-colors bg-green-600 text-white";
                }
            });

            loader.classList.remove('hidden');
            try {
                const response = await fetch(`{{ route('admin.ratings.chart-data') }}?days=${days}`);
                const data = await response.json();
                const labels = data.labels.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                });
                initChart(labels, data.values);
            } catch (e) {
                console.error('Error fetching chart data:', e);
            } finally {
                loader.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => updateChart(30));
    </script>
@endsection