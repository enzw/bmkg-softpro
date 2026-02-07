<aside class="sticky top-20 h-max">
    <!-- Sidebar Container -->
    <div class="bg-white dark:bg-gray-800/50 rounded-2xl shadow-sm dark:shadow-lg border border-gray-100 dark:border-gray-700/50 overflow-hidden backdrop-blur-sm">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700/50 bg-gradient-to-r from-green-50 to-transparent dark:from-green-900/10 dark:to-transparent">
            <h2 class="text-sm font-semibold tracking-wide text-gray-700 dark:text-gray-200 uppercase">Menu Admin</h2>
        </div>
        
        <!-- Menu Items -->
        <nav class="p-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                @class([
                    'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.dashboard'),
                    'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border-l-2 border-green-500' => request()->routeIs('admin.dashboard'),
                ])>
                <i class="fa-solid fa-chart-line w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Dashboard</span>
            </a>

            <!-- Sewa Alat -->
            <a href="{{ route('admin.sewa-alat.index') }}"
                @class([
                    'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.sewa-alat.*'),
                    'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-l-2 border-blue-500' => request()->routeIs('admin.sewa-alat.*'),
                ])>
                <i class="fa-solid fa-toolbox w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Jasa Sewa Alat MKG</span>
            </a>

            <!-- Pelayanan Jasa -->
            <a href="{{ route('admin.pelayanan-jasa.index') }}"
                @class([
                    'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.pelayanan-jasa.index'),
                    'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border-l-2 border-purple-500' => request()->routeIs('admin.pelayanan-jasa.index'),
                ])>
                <i class="fa-solid fa-handshake w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Pelayanan Informasi Geofisika</span>
            </a>

            <!-- Kunjungan -->
            <a href="{{ route('admin.permohonan-kunjungan.index') }}"
                @class([
                    'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.permohonan-kunjungan.index'),
                    'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 border-l-2 border-orange-500' => request()->routeIs('admin.permohonan-kunjungan.index'),
                ])>
                <i class="fa-solid fa-users w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Permohonan Kunjungan</span>
            </a>

            <!-- Download Area -->
            <a href="{{ route('admin.download-area.index') }}"
                @class([
                    'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.download-area.*'),
                    'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border-l-2 border-indigo-500' => request()->routeIs('admin.download-area.*'),
                ])>
                <i class="fa-solid fa-download w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Download Area</span>
            </a>
        </nav>

        <!-- Footer Stats -->
        <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50 dark:bg-gray-900/30 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 dark:text-gray-400">Status</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Online
                </span>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-200 dark:border-gray-700/30">
                <p class="font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</p>
                <p class="text-gray-500 dark:text-gray-500 mt-0.5">{{ Auth::user()->role ?? 'Administrator' }}</p>
            </div>
        </div>
    </div>
</aside>

<style>
    aside {
        @apply transition-all duration-300;
    }
</style>