<nav class="h-[60px] md:h-[70px] px-4 py-3 fixed w-full top-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md z-40">
    <div class="container relative flex items-center h-full mx-auto">

        <!-- Logo kiri -->
        <div class="flex flex-1">
            <a href="/" class="flex items-center gap-2 font-bold dark:text-white">
                <img src="{{ asset('images/logo-bmkg.png') }}" class="h-[36px]" alt="BMKG">
                BMKG <span class="hidden md:inline">Geofisika Yogyakarta</span>
            </a>
        </div>

        <ul class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center">

            <li>
                <a href="/profil"
                    class="dark:text-white rounded-full px-5 py-3 transition duration-200 {{ request()->is('profil*') ? 'text-green-700 font-semibold pointer-events-none' : 'hover:bg-green-700 hover:text-white' }}">
                    Profil
                </a>
            </li>

            <li class="relative group/layanan">
                <button
                    class="dark:text-white rounded-full px-5 py-3 transition duration-200 hover:bg-green-700 hover:text-white inline-flex items-center gap-1 focus:outline-none"
                    aria-haspopup="true">
                    Layanan
                    <svg class="w-3 h-3 transition-transform duration-200 group-hover/layanan:rotate-180" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                {{-- Dropdown --}}
                <div
                    class="absolute left-1/2 -translate-x-1/2 top-full pt-2 w-60 opacity-0 invisible group-hover/layanan:opacity-100 group-hover/layanan:visible transition-all duration-200 z-50">
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl dark:shadow-slate-900/70 border border-gray-100 dark:border-slate-700 overflow-hidden py-1.5">
                        <a href="/#layanan"
                            class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 dark:hover:text-green-400 transition duration-150">
                            <span
                                class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-th-large text-green-600 dark:text-green-400 text-xs"></i>
                            </span>
                            <div>
                                <div class="font-semibold">Daftar Layanan</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">Semua jenis layanan BMKG</div>
                            </div>
                        </a>
                        <a href="{{ route('alur-pelayanan') }}"
                            class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 dark:hover:text-green-400 transition duration-150 {{ request()->is('alur-pelayanan*') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 font-semibold' : '' }}">
                            <span
                                class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-list-ol text-green-600 dark:text-green-400 text-xs"></i>
                            </span>
                            <div>
                                <div class="font-semibold">Alur Pelayanan Data</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">8 tahap proses pelayanan</div>
                            </div>
                        </a>
                        <a href="{{ route('tarif-layanan') }}"
                            class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 dark:hover:text-green-400 transition duration-150 {{ request()->is('tarif-layanan*') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 font-semibold' : '' }}">
                            <span
                                class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-tags text-emerald-600 dark:text-emerald-400 text-xs"></i>
                            </span>
                            <div>
                                <div class="font-semibold">Jenis Layanan & Tarif</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">Daftar produk & biaya PNBP</div>
                            </div>
                        </a>
                        <a href="{{ route('standar-pelayanan-jasa') }}"
                            class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 dark:hover:text-green-400 transition duration-150 {{ request()->is('standar-pelayanan-jasa*') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 font-semibold' : '' }}">
                            <span
                                class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-certificate text-emerald-600 dark:text-emerald-400 text-xs"></i>
                            </span>
                            <div>
                                <div class="font-semibold">Standar Pelayanan Jasa</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">Persyaratan & waktu layanan</div>
                            </div>
                        </a>
                        <a href="{{ route('pembayaran-pnbp') }}"
                            class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 dark:hover:text-green-400 transition duration-150 {{ request()->is('pembayaran-pnbp*') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 font-semibold' : '' }}">
                            <span
                                class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-credit-card text-green-600 dark:text-green-400 text-xs"></i>
                            </span>
                            <div>
                                <div class="font-semibold">Cara Pembayaran PNBP</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">ATM, m-banking, e-commerce</div>
                            </div>
                        </a>
                        <a href="{{ route('regulasi-ptsp') }}"
                            class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 dark:hover:text-green-400 transition duration-150 {{ request()->is('regulasi-ptsp*') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 font-semibold' : '' }}">
                            <span
                                class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-gavel text-emerald-600 dark:text-emerald-400 text-xs"></i>
                            </span>
                            <div>
                                <div class="font-semibold">Regulasi PTSP</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">Perka, PP, & Kebijakan SMKI</div>
                            </div>
                        </a>
                    </div>
                </div>
            </li>

            @if (!Auth::check() || Auth::user()->role !== 'admin')
                <li>
                    <a href="{{ route('rating.create') }}"
                        class="dark:text-white rounded-full px-5 py-3 transition duration-200 {{ request()->is('rating*') ? 'text-green-700 font-semibold pointer-events-none' : 'hover:bg-green-700 hover:text-white' }}">
                        Saran
                    </a>
                </li>
            @endif

            @if (Auth::check())
                <li>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : '/dashboard-pelayanan' }}"
                        class="dark:text-white rounded-full px-5 py-3 transition duration-200 {{ request()->is('admin*') || request()->is('dashboard-pelayanan*') ? 'text-green-700 font-semibold pointer-events-none' : 'hover:bg-green-700 hover:text-white' }}">
                        Dashboard
                    </a>
                </li>
            @endif

            {{-- <li>
                <a href="/berita"
                    class="dark:text-white rounded-full px-5 py-3 transition duration-200 {{ request()->is('berita*') ? 'text-green-700 font-semibold pointer-events-none' : 'hover:bg-green-700 hover:text-white' }}">
                    Berita
                </a>
            </li> --}}

            {{-- <li>
                <a href="/kontak"
                    class="dark:text-white rounded-full px-5 py-3 transition duration-200 {{ request()->is('kontak*') ? 'text-green-700 font-semibold pointer-events-none' : 'hover:bg-green-700 hover:text-white' }}">
                    Hubungi kami
                </a>
            </li> --}}

        </ul>


        <!-- Area kanan -->
        <div class="flex flex-1 justify-end items-center gap-3">

            @if (Auth::user())
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 dark:bg-green-700 rounded-full hover:bg-green-700 dark:hover:bg-green-800 transition duration-200">
                            {{ Auth::user()->name }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <x-dropdown-link :href="Auth::user()->role === 'admin' ? '/admin/dashboard' : '/dashboard-pelayanan'">
                            Dashboard
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <div class="hidden md:flex gap-3">
                    <a href="/login"
                        class="px-5 py-3 text-green-700 dark:text-green-400 rounded-full hover:bg-green-700/20 dark:hover:bg-green-700/30 transition duration-200 font-medium">
                        Login
                    </a>
                    <a href="/register"
                        class="px-5 py-3 text-white bg-green-600 dark:bg-green-700 border border-green-600 dark:border-green-700 rounded-full hover:bg-green-700 dark:hover:bg-green-800 transition duration-200 font-medium">
                        Register
                    </a>
                </div>
            @endif

            <button id="burgerBtn" type="button"
                class="block px-3 py-1 rounded dark:text-white md:hidden focus:ring-1 ring-green-700">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white dark:bg-gray-900 w-full px-4 pb-6">
        <ul class="flex flex-col gap-3 mt-4">
            <li><a href="/profil" class="px-5 py-3 rounded-full dark:text-white">Profil</a></li>
            <li>
                <button id="mobileLayananBtn"
                    class="w-full text-left px-5 py-3 rounded-full dark:text-white flex justify-between items-center font-medium">
                    Layanan
                    <i class="fa-solid fa-chevron-down text-sm transition-transform duration-200"
                        id="mobileLayananChevron"></i>
                </button>
                <div id="mobileLayananMenu" class="hidden flex flex-col mt-1 gap-1 pl-4">
                    <a href="/#layanan"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl dark:text-gray-300 text-gray-700 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 text-sm">
                        <i class="fas fa-th-large text-green-500 w-4 text-center"></i> Daftar Layanan
                    </a>
                    <a href="{{ route('alur-pelayanan') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl dark:text-gray-300 text-gray-700 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 text-sm {{ request()->is('alur-pelayanan*') ? 'text-green-700 font-semibold' : '' }}">
                        <i class="fas fa-list-ol text-green-500 w-4 text-center"></i> Alur Pelayanan Data
                    </a>
                    <a href="{{ route('tarif-layanan') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl dark:text-gray-300 text-gray-700 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 text-sm {{ request()->is('tarif-layanan*') ? 'text-green-700 font-semibold' : '' }}">
                        <i class="fas fa-tags text-emerald-500 w-4 text-center"></i> Jenis Layanan & Tarif
                    </a>
                    <a href="{{ route('standar-pelayanan-jasa') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl dark:text-gray-300 text-gray-700 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 text-sm {{ request()->is('standar-pelayanan-jasa*') ? 'text-green-700 font-semibold' : '' }}">
                        <i class="fas fa-certificate text-emerald-500 w-4 text-center"></i> Standar Pelayanan Jasa
                    </a>
                    <a href="{{ route('pembayaran-pnbp') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl dark:text-gray-300 text-gray-700 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 text-sm {{ request()->is('pembayaran-pnbp*') ? 'text-green-700 font-semibold' : '' }}">
                        <i class="fas fa-credit-card text-green-500 w-4 text-center"></i> Cara Pembayaran PNBP
                    </a>
                    <a href="{{ route('regulasi-ptsp') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl dark:text-gray-300 text-gray-700 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-700 text-sm {{ request()->is('regulasi-ptsp*') ? 'text-green-700 font-semibold' : '' }}">
                        <i class="fas fa-gavel text-emerald-500 w-4 text-center"></i> Regulasi PTSP
                    </a>
                </div>
            </li>

            @if (!Auth::check() || Auth::user()->role !== 'admin')
                <li><a href="{{ route('rating.create') }}" class="px-5 py-3 rounded-full dark:text-white">Saran</a></li>
            @endif
            @if (Auth::check())
                <li><a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : '/dashboard-pelayanan' }}"
                        class="px-5 py-3 rounded-full dark:text-white">Dashboard</a></li>
            @endif

            {{-- <li><a href="/berita" class="px-5 py-3 rounded-full dark:text-white">Berita</a></li>
            <li><a href="/kontak" class="px-5 py-3 rounded-full dark:text-white">Hubungi kami</a></li> --}}

            @if (Auth::user())
                <li class="border-t border-gray-700 mt-4 pt-4">
                    <button id="mobileDropdownBtn"
                        class="w-full text-left px-5 py-3 rounded-full dark:text-white flex justify-between items-center">
                        {{ Auth::user()->name }}
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </button>

                    <div id="mobileDropdownMenu" class="hidden flex flex-col mt-2 gap-2">
                        <a href="{{ route('profile.edit') }}" class="px-5 py-2 rounded dark:text-white">Profile</a>
                        <a href="{{ Auth::user()->role === 'admin' ? '/admin/dashboard' : '/dashboard-pelayanan' }}"
                            class="px-5 py-2 rounded dark:text-white">
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button onclick="event.preventDefault(); this.closest('form').submit();"
                                class="px-5 py-2 text-left rounded dark:text-white">
                                Log Out
                            </button>
                        </form>
                    </div>
                </li>
            @else
                <li><a href="/login" class="px-5 py-3 rounded-full dark:text-white">Login</a></li>
                <li><a href="/register"
                        class="px-5 py-3 rounded-full text-white bg-green-700 border border-green-700">Register</a></li>
            @endif
        </ul>
    </div>
</nav>

<script>
    document.getElementById("burgerBtn").addEventListener("click", function () {
        document.getElementById("mobileMenu").classList.toggle("hidden");
    });

    document.getElementById("mobileLayananBtn").addEventListener("click", function () {
        const menu = document.getElementById("mobileLayananMenu");
        const chevron = document.getElementById("mobileLayananChevron");
        menu.classList.toggle("hidden");
        chevron.style.transform = menu.classList.contains("hidden") ? "rotate(0deg)" : "rotate(180deg)";
    });

    @if (Auth::user())
        document.getElementById("mobileDropdownBtn").addEventListener("click", function () {
            document.getElementById("mobileDropdownMenu").classList.toggle("hidden");
        });
    @endif
</script>