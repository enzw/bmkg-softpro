<nav class="h-[60px] md:h-[70px] px-4 py-3 fixed w-full top-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md z-10">
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

            <li>
                <a href="/#layanan"
                    class="dark:text-white rounded-full px-5 py-3 transition duration-200 hover:bg-green-700 hover:text-white">
                    Layanan
                </a>
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
            <li><a href="/#layanan" class="px-5 py-3 rounded-full dark:text-white">Layanan</a></li>

            @if (!Auth::check() || Auth::user()->role !== 'admin')
                <li><a href="{{ route('rating.create') }}"
                        class="px-5 py-3 rounded-full dark:text-white">Saran</a></li>
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

    @if (Auth::user())
        document.getElementById("mobileDropdownBtn").addEventListener("click", function () {
            document.getElementById("mobileDropdownMenu").classList.toggle("hidden");
        });
    @endif
</script>