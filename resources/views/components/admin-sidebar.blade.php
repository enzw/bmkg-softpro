<aside class="sticky top-20 h-max">
    <!-- Sidebar Container -->
    <div
        class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        <!-- Header -->
        <div
            class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-green-50 to-transparent dark:from-green-900/10 dark:to-transparent">
            <h2 class="text-xs font-bold tracking-widest text-gray-600 dark:text-gray-300 uppercase">Menu Admin</h2>
        </div>

        <!-- Menu Items -->
        <nav class="p-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" @class([
                'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.dashboard'),
                'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border-l-2 border-green-500' => request()->routeIs('admin.dashboard'),
            ])>
                <i class="fa-solid fa-chart-line w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Dashboard</span>
            </a>

            <!-- Sewa Alat -->
            <a href="{{ route('admin.sewa-alat.index') }}" @class([
                'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.sewa-alat.*'),
                'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-l-2 border-blue-500' => request()->routeIs('admin.sewa-alat.*'),
            ])>
                <i class="fa-solid fa-toolbox w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Jasa Sewa Alat MKG</span>
            </a>

            <!-- Pelayanan Jasa -->
            <a href="{{ route('admin.pelayanan-jasa.index') }}" @class([
                'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.pelayanan-jasa.index'),
                'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border-l-2 border-purple-500' => request()->routeIs('admin.pelayanan-jasa.index'),
            ])>
                <i class="fa-solid fa-handshake w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Pelayanan Informasi Geofisika</span>
            </a>

            <!-- Kunjungan -->
            <a href="{{ route('admin.permohonan-kunjungan.index') }}" @class([
                'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.permohonan-kunjungan.index'),
                'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 border-l-2 border-orange-500' => request()->routeIs('admin.permohonan-kunjungan.index'),
            ])>
                <i class="fa-solid fa-users w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Permohonan Kunjungan</span>
            </a>

            <!--Rating -->
            <a href="{{ route('admin.ratings.index') }}" @class([
                'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.ratings.*'),
                'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-l-2 border-amber-500' => request()->routeIs('admin.ratings.*'),
            ])>
                <i class="fa-solid fa-star w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Rating</span>
            </a>

            <!-- Download Area -->
            <a href="{{ route('admin.download-area.index') }}" @class([
                'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('admin.download-area.*'),
                'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border-l-2 border-indigo-500' => request()->routeIs('admin.download-area.*'),
            ])>
                <i class="fa-solid fa-download w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110"></i>
                <span>Download Area</span>
            </a>
        </nav>

        <!-- Footer Actions -->
        <div
            class="px-6 py-5 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/20 space-y-3">
            <!-- Profile Button -->
            <a href="{{ route('profile.edit') }}" 
                class="w-full flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 text-blue-600 dark:text-blue-400 font-semibold text-sm transition-all duration-200 hover:bg-blue-100 dark:hover:bg-blue-900/30 group">
                <i class="fa-solid fa-user w-4 h-4 transition-transform group-hover:scale-110"></i>
                <span>Profil</span>
            </a>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/50 text-red-600 dark:text-red-400 font-semibold text-sm transition-all duration-200 hover:bg-red-100 dark:hover:bg-red-900/30 group">
                    <i class="fa-solid fa-sign-out-alt w-4 h-4 transition-transform group-hover:scale-110"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
    aside {
        @apply transition-all duration-300;
    }
</style>