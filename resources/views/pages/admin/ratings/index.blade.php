@extends('layouts.admin')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8 min-h-screen">
        <!-- Main Column (Left/Center) -->
        <div class="flex-1 space-y-8">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">Halo, {{ Auth::user()->name }}!</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Monitor performa layanan dan feedback hari ini.</p>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-green-600 dark:text-emerald-400 uppercase tracking-widest">Update
                        Terakhir</p>
                    <p class="text-sm text-gray-400">{{ date('H:i') }}, {{ date('d M Y') }}</p>
                </div>
            </div>

            <!-- Stats Rows -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Rating Average Card -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="px-3 py-1 bg-green-50 dark:bg-emerald-900/20 text-green-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-widest rounded-lg">Rata-rata</span>
                        <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $averageRating }}</h3>
                        <span class="text-xs text-gray-400">/ 5.0</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ ($averageRating / 5) * 100 }}%">
                        </div>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-400 font-semibold uppercase tracking-tighter">Kepuasan:
                        {{ round(($averageRating / 5) * 100) }}%
                    </p>
                </div>

                <!-- Total Reviews Card -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest rounded-lg">Total Ulasan</span>
                        <div
                            class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-users text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $totalRatings }}</h3>
                        <span class="text-xs text-gray-400">Ulasan</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $monthlyTargetProgress }}%"></div>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-400 font-semibold uppercase tracking-tighter">{{ $monthlyTargetProgress }}% dari target bulan
                        ini
                    </p>
                </div>

                <!-- Net Promoter / Sim Scale -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="px-3 py-1 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 text-[10px] font-bold uppercase tracking-widest rounded-lg">Net
                            Promoter</span>
                        <div
                            class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center text-purple-500">
                            <i class="fa-solid fa-bolt text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $netPromoterScore }}%</h3>
                        <span class="text-xs text-gray-400">di {{ date('M') }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: {{ $netPromoterScore }}%"></div>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-400 font-semibold uppercase tracking-tighter">{{ $netPromoterScore >= 80 ? 'Feedback sangat positif' : ($netPromoterScore >= 50 ? 'Feedback positif' : 'Perlu peningkatan') }}
                    </p>
                </div>
            </div>

            <!-- Main Chart Area -->
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white leading-none mb-1">Tren Kepuasan User
                        </h2>
                        <p class="text-xs text-gray-400">Rata-rata ulasan harian dalam 30 hari terakhir</p>
                    </div>
                    <select id="daysFilter" onchange="updateChart(this.value)"
                        class="text-xs font-bold bg-slate-50 dark:bg-slate-700 border-none rounded-xl px-4 py-2 text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-green-500 transition-all">
                        <option value="7">7 Hari</option>
                        <option value="30" selected>30 Hari</option>
                        <option value="90">90 Hari</option>
                    </select>
                </div>
                <div class="h-[300px] relative">
                    <canvas id="ratingChart"></canvas>
                    <div id="loader"
                        class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-800/50 hidden">
                        <div class="w-8 h-8 border-4 border-green-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution Phases -->
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm mb-8">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6">Distribusi
                    Rating</h2>
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                    @foreach([5, 4, 3, 2, 1] as $star)
                        @php $pct = $totalRatings > 0 ? ($distribution[$star] / $totalRatings) * 100 : 0; @endphp
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-tighter">
                                <span
                                    class="{{ $star >= 4 ? 'text-green-500' : ($star == 3 ? 'text-amber-500' : 'text-red-500') }}">{{ $star }}
                                    Bintang</span>
                                <span class="text-gray-400">{{ $distribution[$star] }}</span>
                            </div>
                            <div class="w-full h-2 bg-gray-50 dark:bg-gray-700 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full {{ $star >= 4 ? 'bg-green-500' : ($star == 3 ? 'bg-amber-500' : 'bg-red-500') }} rounded-full"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[9px] text-gray-400 font-medium">{{ round($pct) }}%</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Column (Right) -->
        <div class="lg:w-[380px] space-y-8">
            <div
                class="bg-white dark:bg-gray-800 h-full p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Review Terkini</h2>
                    <a href="{{ route('admin.download-area.index') }}"
                        class="inline-flex items-center gap-2 text-[10px] font-bold bg-green-50 dark:bg-emerald-900/20 text-green-600 dark:text-emerald-400 px-3 py-1.5 rounded-lg border border-green-100 dark:border-emerald-800 transition-all hover:scale-105 active:scale-95 uppercase">
                        <i class="fas fa-download text-xs"></i>
                        Download
                    </a>
                </div>

                <div class="flex-1 space-y-6 overflow-y-auto max-h-[800px] pr-2 custom-scrollbar">
                    @forelse($ratings as $rating)
                        <div
                            class="group relative flex gap-4 p-4 rounded-3xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-300">
                            <!-- Time Indicator Line -->
                            <div
                                class="absolute left-[-1rem] top-8 bottom-[-1.5rem] w-px bg-gray-100 dark:bg-gray-700 group-last:hidden">
                            </div>

                            <!-- Avatar/Icon -->
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 font-bold shadow-inner">
                                {{ substr($rating->user->name ?? 'U', 0, 1) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate pr-2">
                                        {{ $rating->user->name ?? 'Anonymous' }}
                                    </h4>
                                    <div class="flex text-amber-400 text-[8px] gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $rating->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p
                                    class="text-[10px] font-semibold text-green-600 dark:text-emerald-400 uppercase tracking-widest mb-2">
                                    {{ str_replace(['App\\Models\\', 'Permohonan'], '', $rating->rateable_type) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic line-clamp-3 leading-relaxed">
                                    "{{ $rating->review ?: 'Tanpa ulasan tertulis.' }}"
                                </p>
                                <p class="mt-3 text-[9px] text-gray-300 dark:text-gray-500 uppercase font-bold">
                                    {{ $rating->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <i class="fa-solid fa-face-smile text-4xl text-gray-100 mb-4 opacity-20"></i>
                            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Belum ada review baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let myChart;
        const ctx = document.getElementById('ratingChart').getContext('2d');
        const loader = document.getElementById('loader');

        function initChart(labels, values) {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.03)';
            const textColor = isDark ? '#94a3b8' : '#64748b';

            if (myChart) myChart.destroy();

            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, isDark ? 'rgba(16, 185, 129, 0.2)' : 'rgba(16, 185, 129, 0.1)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        borderColor: '#10b981',
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: isDark ? '#1e293b' : '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradient,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1e293b' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#0f172a',
                            bodyColor: isDark ? '#94a3b8' : '#64748b',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: (context) => `Rating: ${context.parsed.y}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: '600' }, color: textColor }
                        },
                        y: {
                            min: 0,
                            max: 5,
                            ticks: { stepSize: 1, font: { size: 10, weight: '600' }, color: textColor },
                            grid: { color: gridColor, drawBorder: false }
                        }
                    }
                }
            });
        }

        async function updateChart(days) {
            loader.classList.remove('hidden');
            try {
                const response = await fetch(`{{ route('admin.ratings.chart-data') }}?days=${days}`);
                const data = await response.json();

                const labels = data.labels.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                });

                initChart(labels, data.values);
            } catch (error) {
                console.error('Error loading chart:', error);
            } finally {
                loader.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => updateChart(30));
    </script>
@endsection