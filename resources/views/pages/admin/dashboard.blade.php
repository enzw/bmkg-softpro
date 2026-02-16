@extends('layouts.admin')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8 min-h-screen">
        <!-- Main Column (Left/Center) -->
        <div class="flex-1 space-y-8">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">Halo, {{ Auth::user()->name }}!</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Monitor aktivitas layanan dan statistik hari ini.</p>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-green-600 dark:text-emerald-400 uppercase tracking-widest">Update Realtime</p>
                    <p class="text-sm text-gray-400">{{ date('H:i') }}, {{ date('d M Y') }}</p>
                </div>
            </div>

            <!-- KPI Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Permohonan -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-green-50 dark:bg-emerald-900/20 text-green-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-widest rounded-lg">Permohonan</span>
                        <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice text-green-500 text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $statistik['total'] }}</h3>
                        <span @class(['text-[10px] font-bold uppercase', $statistik['total_change'] > 0 ? 'text-green-500' : 'text-gray-400'])>
                            <i class="fas fa-arrow-{{ $statistik['total_change'] > 0 ? 'up' : 'right' }} mr-1"></i>{{ $statistik['total_change'] }}
                        </span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: 100%"></div>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-400 font-semibold uppercase tracking-tighter">Volume layanan aktif</p>
                </div>

                <!-- Dalam Proses -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest rounded-lg">Diproses</span>
                        <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-spinner fa-spin text-blue-500 text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $statistik['total_diproses'] }}</h3>
                        <span class="text-xs text-gray-400">Permohonan</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        @php $progressPct = $statistik['total'] > 0 ? ($statistik['total_diproses'] / $statistik['total']) * 100 : 0; @endphp
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $progressPct }}%"></div>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-400 font-semibold uppercase tracking-tighter">{{ round($progressPct) }}% dari total antrean</p>
                </div>

                <!-- Tingkat Penyelesaian -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-widest rounded-lg">Selesai</span>
                        <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-check-double text-amber-500 text-xs"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">
                            {{ $statistik['total'] > 0 ? round(($statistik['total_selesai'] / $statistik['total']) * 100) : 0 }}%
                        </h3>
                        <span @class(['text-[10px] font-bold uppercase', $statistik['completion_rate_change'] > 0 ? 'text-green-500' : 'text-gray-400'])>
                            <i class="fas fa-arrow-{{ $statistik['completion_rate_change'] > 0 ? 'up' : 'right' }} mr-1"></i>{{ $statistik['completion_rate_change'] }}%
                        </span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        @php $compPct = $statistik['total'] > 0 ? ($statistik['total_selesai'] / $statistik['total']) * 100 : 0; @endphp
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $compPct }}%"></div>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-400 font-semibold uppercase tracking-tighter">Efisiensi penyelesaian</p>
                </div>
            </div>

            <!-- Distribution Charts Content -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Visualisasi Data Layanan</h2>
                        <p class="text-xs text-gray-400 text-balance">Proporsi status dan distribusi volume per kategori layanan.</p>
                    </div>
                    <a href="{{ route('admin.download-area.index') }}"
                        class="inline-flex items-center gap-2 text-[10px] font-bold bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl border border-indigo-100 dark:border-indigo-800 transition-all hover:scale-105 active:scale-95">
                        <i class="fas fa-download"></i>
                        Download Area
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Status Doughnut -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest text-center">Distribusi Status</h3>
                        <div class="h-64 relative">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    <!-- Service Bar -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest text-center">Volume Per Layanan</h3>
                        <div class="h-64 relative">
                            <canvas id="serviceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Summary List (Mini Cards) -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm mb-8">
                <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-6">Ringkasan Layanan</h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach(['Sewa Alat', 'Magang', 'Kunjungan', 'Konsultasi', 'Asuransi', 'Survey', 'Layanan Data'] as $svc)
                        @php 
                            $key = strtolower(str_replace(' ', '_', $svc));
                            if ($key == 'konsultasi')
                                $key = 'jasa_konsultasi';
                            $count = $statistik[$key]['total'] ?? 0;
                            
                            // Icon config for each service
                            $serviceIcons = [
                                'sewa_alat' => ['icon' => 'fa-tools', 'bg' => 'bg-green-100 dark:bg-emerald-900/30', 'text' => 'text-green-600 dark:text-emerald-400', 'border' => 'hover:border-green-200 dark:hover:border-emerald-800'],
                                'magang' => ['icon' => 'fa-graduation-cap', 'bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'hover:border-blue-200 dark:hover:border-blue-800'],
                                'kunjungan' => ['icon' => 'fa-building-user', 'bg' => 'bg-amber-100 dark:bg-amber-900/30', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'hover:border-amber-200 dark:hover:border-amber-800'],
                                'jasa_konsultasi' => ['icon' => 'fa-comments', 'bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-600 dark:text-purple-400', 'border' => 'hover:border-purple-200 dark:hover:border-purple-800'],
                                'asuransi' => ['icon' => 'fa-file-invoice-dollar', 'bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-600 dark:text-orange-400', 'border' => 'hover:border-orange-200 dark:hover:border-orange-800'],
                                'survey' => ['icon' => 'fa-compass', 'bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-600 dark:text-red-400', 'border' => 'hover:border-red-200 dark:hover:border-red-800'],
                                'layanan_data' => ['icon' => 'fa-database', 'bg' => 'bg-cyan-100 dark:bg-cyan-900/30', 'text' => 'text-cyan-600 dark:text-cyan-400', 'border' => 'hover:border-cyan-200 dark:hover:border-cyan-800'],
                            ];
                            $config = $serviceIcons[$key] ?? $serviceIcons['sewa_alat'];
                        @endphp
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-transparent {{ $config['border'] }} transition-all group hover:shadow-md">
                            <div class="flex items-start justify-between mb-3">
                                <p class="text-[9px] font-bold text-gray-400 uppercase leading-none truncate flex-1">{{ $svc }}</p>
                                <div class="w-8 h-8 rounded-lg {{ $config['bg'] }} flex items-center justify-center {{ $config['text'] }} flex-shrink-0 ml-2 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid {{ $config['icon'] }} text-xs"></i>
                                </div>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 dark:text-white leading-none">{{ $count }}</h4>
                            <p class="mt-2 text-[8px] {{ $config['text'] }} font-bold uppercase tracking-tight">Permohonan</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Column (Right) -->
        <div class="lg:w-[380px] space-y-8">
            <div class="bg-white dark:bg-gray-800 h-full p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Permohonan Baru</h2>
                        <span class="text-[10px] text-gray-400 font-semibold tracking-widest uppercase">10 Terbaru</span>
                    </div>
                    <button onclick="toggleQuickCreate()" class="w-10 h-10 rounded-2xl bg-green-50 dark:bg-emerald-900/20 flex items-center justify-center text-green-600 dark:text-emerald-400 hover:bg-green-100 transition-colors shadow-sm">
                        <i class="fa-solid fa-plus text-sm"></i>
                    </button>
                </div>

                <div class="flex-1 space-y-6 overflow-y-auto max-h-[850px] pr-2 custom-scrollbar">
                    @php
                        $allRequests = collect();
                        foreach (['sewa_alat' => 'Sewa Alat', 'magang' => 'Magang', 'kunjungan' => 'Kunjungan', 'jasa_konsultasi' => 'Konsultasi', 'asuransi' => 'Asuransi', 'survey' => 'Survey', 'layanan_data' => 'Layanan Data'] as $key => $name) {
                            $collection = $$key;
                            foreach ($collection as $item) {
                                $statusObj = $item->status;
                                $rawStatus = '';
                                $displayStatus = 'Menunggu';

                                if ($statusObj instanceof \App\Enums\SewaStatus) {
                                    $rawStatus = strtolower($statusObj->value);
                                    $displayStatus = $statusObj->value;
                                } elseif ($statusObj instanceof \App\Enums\Status) {
                                    $rawStatus = strtolower($statusObj->value);
                                    $displayStatus = $statusObj->label();
                                } else {
                                    $rawStatus = strtolower(trim($item->status ?? 'menunggu'));
                                    $displayStatus = trim($item->status ?? 'Menunggu');
                                }

                                $mappedStatus = 'menunggu'; // default

                                // Mapping logic
                                if (in_array($rawStatus, ['selesai', 'dikembalikan', 'completed'])) {
                                    $mappedStatus = 'selesai';
                                } elseif (in_array($rawStatus, ['diproses', 'approved', 'siap diambil', 'dibawa'])) {
                                    $mappedStatus = 'diproses';
                                } elseif (in_array($rawStatus, ['ditolak', 'rejected'])) {
                                    $mappedStatus = 'ditolak';
                                } elseif (in_array($rawStatus, ['menunggu', 'pending', 'belum lunas'])) {
                                    $mappedStatus = 'menunggu';
                                }
                                $allRequests->push([
                                    'id' => $item->id,
                                    'service' => $name,
                                    'status' => $mappedStatus,
                                    'display_status' => $displayStatus,
                                    'created_at' => $item->created_at ?? now(),
                                    'user' => $item->user->name ?? 'User',
                                    'type' => $key,
                                    'item' => $item // Full model instance for modal
                                ]);
                            }
                        }
                        $recentRequests = $allRequests->sortByDesc('created_at')->take(10);
                    @endphp

                    @forelse($recentRequests as $request)
                        <div onclick="openDetailModal('modal-detail-recent-{{ $loop->index }}')" class="group relative flex gap-4 p-4 rounded-3xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-300 border border-transparent hover:border-gray-100 dark:hover:border-gray-700 cursor-pointer">
                            <!-- Time Indicator Line -->
                            <div class="absolute left-[-1rem] top-8 bottom-[-1.5rem] w-px bg-gray-100 dark:bg-gray-700 group-last:hidden"></div>

                            <!-- User Initials Avatar -->
                             <div @class([
                                'flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center shadow-inner font-bold text-lg transition-colors',
                                'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' => $request['status'] === 'menunggu',
                                'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' => $request['status'] === 'diproses',
                                'bg-green-50 text-green-600 dark:bg-emerald-900/30 dark:text-emerald-400' => $request['status'] === 'selesai',
                                'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' => $request['status'] === 'ditolak',
                            ])>
                                {{ substr($request['user'], 0, 1) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                     <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate pr-2">
                                         {{ $request['service'] }}
                                     </h4>
                                     <span class="text-[8px] font-black text-gray-300 dark:text-gray-600">#{{ substr($request['id'], 0, 5) }}</span>
                                 </div>
                                <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                                    {{ $request['user'] }}
                                </p>
                                <div class="flex items-center gap-2 mt-2">
                                     <span @class([
                                        'text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded transition-colors',
                                        'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' => $request['status'] === 'menunggu',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' => $request['status'] === 'diproses',
                                        'bg-green-100 text-green-700 dark:bg-emerald-900/50 dark:text-emerald-300' => $request['status'] === 'selesai',
                                        'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' => $request['status'] === 'ditolak',
                                    ])>
                                         {{ $request['display_status'] }}
                                     </span>
                                     <span class="text-[9px] text-gray-300 dark:text-gray-500 font-bold uppercase">
                                         {{ $request['created_at']->diffForHumans() }}
                                     </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <i class="fa-solid fa-inbox text-4xl text-gray-100 mb-4 opacity-20"></i>
                            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Belum ada permohonan baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Create Modal -->
    <div id="quickCreateModal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="toggleQuickCreate()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm p-4 animate-in fade-in zoom-in duration-200">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8 text-center border-b border-gray-50 dark:border-gray-700">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-1">Buat Baru</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pilih Jenis Permohonan</p>
                </div>
                <div class="p-4 grid gap-2">
                    <a href="{{ route('admin.sewa-alat.create') }}" class="flex items-center gap-4 p-4 rounded-3xl hover:bg-green-50 dark:hover:bg-emerald-900/20 group transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-tools"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-black text-gray-900 dark:text-white uppercase leading-none mb-1">Sewa Alat</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Peminatan peralatan teknis</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.pelayanan-jasa.create') }}" class="flex items-center gap-4 p-4 rounded-3xl hover:bg-blue-50 dark:hover:bg-blue-900/20 group transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-black text-gray-900 dark:text-white uppercase leading-none mb-1">Pelayanan Jasa</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Magang & PKL Mahasiswa</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.permohonan-kunjungan.create') }}" class="flex items-center gap-4 p-4 rounded-3xl hover:bg-amber-50 dark:hover:bg-amber-900/20 group transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-building-user"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-black text-gray-900 dark:text-white uppercase leading-none mb-1">Kunjungan</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Studi Tiru & Kunjungan Teknis</p>
                        </div>
                    </a>
                </div>
                <button onclick="toggleQuickCreate()" class="w-full py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 dark:bg-gray-900/50 hover:text-gray-600 transition-colors">
                    Batalkan
                </button>
            </div>
        </div>
    </div>

    <!-- Detail Modals for Recent Requests -->
    @foreach($recentRequests as $request)
        @php
            $item = $request['item'];
            $type = $request['type'];
            $index = $loop->index;
            $modalId = "modal-detail-recent-$index";
            
            $routeMap = [
                'sewa_alat' => 'sewa-alat',
                'magang' => 'pelayanan-jasa',
                'kunjungan' => 'permohonan-kunjungan',
                'asuransi' => 'klaim-asuransi',
                'layanan_data' => 'layanan-data',
                'survey' => 'survey',
                'jasa_konsultasi' => 'jasa-konsultasi'
            ];
            $routeName = $routeMap[$type] ?? str_replace('_', '-', $type);
        @endphp

        <div id="{{ $modalId }}" class="hidden fixed inset-0 z-[70] overflow-auto bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] max-w-2xl w-full shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden animate-in fade-in zoom-in duration-300">
                <!-- Header -->
                <div class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                    <div class="flex items-center gap-4">
                        @php
                            $iconConfig = [
                                'sewa_alat' => ['icon' => 'fa-tools', 'bg' => 'bg-green-100 dark:bg-emerald-900/40', 'text' => 'text-green-600 dark:text-emerald-400'],
                                'magang' => ['icon' => 'fa-graduation-cap', 'bg' => 'bg-blue-100 dark:bg-blue-900/40', 'text' => 'text-blue-600 dark:text-blue-400'],
                                'kunjungan' => ['icon' => 'fa-building-user', 'bg' => 'bg-amber-100 dark:bg-amber-900/40', 'text' => 'text-amber-600 dark:text-amber-400'],
                                'asuransi' => ['icon' => 'fa-file-invoice-dollar', 'bg' => 'bg-orange-100 dark:bg-orange-900/40', 'text' => 'text-orange-600 dark:text-orange-400'],
                                'layanan_data' => ['icon' => 'fa-database', 'bg' => 'bg-cyan-100 dark:bg-cyan-900/40', 'text' => 'text-cyan-600 dark:text-cyan-400'],
                                'survey' => ['icon' => 'fa-compass', 'bg' => 'bg-red-100 dark:bg-red-900/40', 'text' => 'text-red-600 dark:text-red-400'],
                                'jasa_konsultasi' => ['icon' => 'fa-comments', 'bg' => 'bg-purple-100 dark:bg-purple-900/40', 'text' => 'text-purple-600 dark:text-purple-400'],
                            ];
                            $config = $iconConfig[$type] ?? $iconConfig['sewa_alat'];
                        @endphp
                        <div class="w-12 h-12 rounded-2xl {{ $config['bg'] }} flex items-center justify-center {{ $config['text'] }}">
                            <i class="fa-solid {{ $config['icon'] }} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Detail Permohonan</h3>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">Type: {{ $request['service'] }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeDetailModal('{{ $modalId }}')" class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors shadow-sm border border-gray-100 dark:border-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-8 space-y-8 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <!-- Basic Information -->
                    <div class="relative pl-6 border-l-2 border-green-500/30">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Informasi Pemohon
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Nama</p>
                                <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                    @if($type === 'sewa_alat') {{ $item->nama }}
                                    @elseif($type === 'kunjungan') {{ $item->nama_lengkap }}
                                    @elseif($type === 'asuransi') {{ $item->nama_user }}
                                    @else {{ $item->nama_lengkap ?? $item->nama ?? '-' }}
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Email</p>
                                <p class="text-xs font-bold text-gray-900 dark:text-white lowercase tracking-tight">
                                    {{ $item->email ?? $item->user->email ?? '-' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">No WhatsApp</p>
                                <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->no_whatsapp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Specific Info -->
                    <div class="relative pl-6 border-l-2 border-blue-500/30">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Detail Layanan
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @if($type === 'sewa_alat')
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Alat yang Disewa</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->alat->nama }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Unit & Harga</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->banyak_unit }} UNIT | Rp{{ number_format($item->alat->harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Total Biaya</p>
                                    @php
                                        $d1 = new DateTime($item->sewa_mulai);
                                        $d2 = new DateTime($item->sewa_berakhir);
                                        $days = $d1->diff($d2)->days ?: 1;
                                        $total = $item->alat->harga * $days * $item->banyak_unit;
                                    @endphp
                                    <p class="text-xs font-bold text-green-600 dark:text-emerald-400 uppercase tracking-tight">Rp{{ number_format($total, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Mulai</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ \Carbon\Carbon::parse($item->sewa_mulai)->format('d M Y') }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Berakhir</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ \Carbon\Carbon::parse($item->sewa_berakhir)->format('d M Y') }}</p>
                                </div>
                                @if($item->keterangan)
                                    <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1 text-left">Keterangan / Keperluan</p>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed text-left w-full">{{ $item->keterangan }}</p>
                                    </div>
                                @endif
                            @elseif($type === 'magang')
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Universitas / Sekolah</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->universitas }}</p>
                                </div>
                                @if($item->prodi)
                                    <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Program Studi</p>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->prodi }}</p>
                                    </div>
                                @endif
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Mulai</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Berakhir</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</p>
                                </div>
                                @if($item->keterangan)
                                    <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1 text-left">Keterangan / Keperluan</p>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed text-left w-full">{{ $item->keterangan }}</p>
                                    </div>
                                @endif
                            @elseif($type === 'kunjungan')
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Lembaga / Sekolah</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->nama_instansi }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Lokasi</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->lokasi ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Peserta</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->jumlah_rombongan }} Orang</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Waktu</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1 text-left">Rencana Kunjungan</p>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed text-left w-full">{{ $item->rencana_kunjungan }}</p>
                                </div>
                            @elseif($type === 'asuransi')
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Perusahaan</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->perusahaan }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Tanggal Kejadian</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Lokasi</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->lokasi ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Latitude</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->latitude ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1">Longitude</p>
                                    <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $item->longitude ?? '-' }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 col-span-2">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mb-1 text-left">Keterangan / Keperluan</p>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed text-left w-full">{{ $item->keterangan ?? $item->keperluan ?? '-' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="relative pl-6 border-l-2 border-purple-500/30">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                            Berkas Pendukung
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            @if($item->surat_permohonan)
                                <a href="{{ route('admin.' . $routeName . '.download-file', ['id' => $item->id, 'fileName' => basename($item->surat_permohonan)]) }}" class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-emerald-900/20 transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">Surat Permohonan</p>
                                        <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download Berkas</p>
                                    </div>
                                </a>
                            @endif

                            @if(isset($item->ktp) && $item->ktp)
                                <a href="{{ route('admin.' . $routeName . '.download-file', ['id' => $item->id, 'fileName' => basename($item->ktp)]) }}" class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">Kartu Identitas</p>
                                        <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download Berkas</p>
                                    </div>
                                </a>
                            @endif

                            @if(isset($item->kartu_mahasiswa) && $item->kartu_mahasiswa)
                                <a href="{{ route('admin.' . $routeName . '.download-file', ['id' => $item->id, 'fileName' => basename($item->kartu_mahasiswa)]) }}" class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-address-card"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-0.5">Kartu Mahasiswa</p>
                                        <p class="text-[8px] text-gray-400 font-semibold uppercase tracking-widest">Download Berkas</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-8 bg-gray-50/50 dark:bg-gray-900/20 border-t border-gray-50 dark:border-gray-700 flex flex-wrap gap-3">
                    <button type="button" onclick="closeDetailModal('{{ $modalId }}')" class="flex-1 px-8 py-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-sm">
                        Tutup
                    </button>
                    @php
                        $editRoute = 'admin.' . ($routeMap[$type] ?? str_replace('_', '-', $type)) . '.edit';
                    @endphp
                    <a href="{{ route($editRoute, $item->id) }}" class="flex-1 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-blue-200 dark:shadow-none text-center">
                        Edit Data
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    function openDetailModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDetailModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function toggleQuickCreate() {
            const modal = document.getElementById('quickCreateModal');
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.03)';

            // Status Doughnut
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
                        backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                        borderColor: isDark ? '#1f2937' : '#ffffff',
                        borderWidth: 5,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                font: { size: 10, weight: '700' },
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });

            // Service Bar Chart
            const serviceCtx = document.getElementById('serviceChart').getContext('2d');
            new Chart(serviceCtx, {
                type: 'bar',
                data: {
                    labels: ['Sewa', 'Magang', 'Kunjungan', 'Konsul', 'Asuransi', 'Survey', 'Data'],
                    datasets: [{
                        data: [
                            {{ $statistik['sewa_alat']['total'] }},
                            {{ $statistik['magang']['total'] }},
                            {{ $statistik['kunjungan']['total'] }},
                            {{ $statistik['jasa_konsultasi']['total'] }},
                            {{ $statistik['asuransi']['total'] }},
                            {{ $statistik['survey']['total'] }},
                            {{ $statistik['layanan_data']['total'] }}
                        ],
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                        maxBarThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10, weight: '700' } }
                        },
                        y: {
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: textColor, font: { size: 10, weight: '700' }, stepSize: 1 }
                        }
                    }
                }
            });
        });
    </script>
@endsection
