<nav class="h-[60px] md:h-[70px] px-4 py-3 fixed w-full top-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md z-10">
    <div class="container flex items-center h-full gap-3 mx-auto">
        <a href="/" class="flex items-center gap-2 font-bold dark:text-white">
            <img src="{{ asset('images/logo-bmkg.png') }}" class="h-[36px]" alt="BMKG">
            BMKG <span class="hidden md:inline">Geofisika Yogyakarta</span>
        </a>
        <ul class="items-center hidden md:flex">
            <li>
                <a href="/tentang-kami"
                    class="hover:bg-green-700 hover:text-white {{ request()->is('tentang-kami*') ? 'bg-green-200 text-green-900 hover:text-white' : '' }} dark:text-white rounded-full visited:text-green-900 dark:visited:text-green-600 dark:visited:hover:text-white px-5 py-3 transition duration-200">Tentang</a>
            </li>
            <li>
                <a href="{{ Auth::user() ? '/layanan' : '/#layanan' }}"
                    class="hover:bg-green-700 hover:text-white {{ request()->is('#layanan') ? 'bg-green-200 text-green-900 hover:text-white' : '' }} dark:text-white rounded-full visited:text-green-900 dark:visited:text-green-600 dark:visited:hover:text-white px-5 py-3 transition duration-200">Layanan</a>
            </li>
            <li>
                <a href="/berita"
                    class="hover:bg-green-700 hover:text-white {{ request()->is('berita*') ? 'bg-green-200 text-green-900 hover:text-white' : '' }} dark:text-white rounded-full visited:text-green-900 dark:visited:text-green-600 dark:visited:hover:text-white px-5 py-3 transition duration-200">Berita</a>
            </li>
            <li>
                <a href="/kontak"
                    class="hover:bg-green-700 hover:text-white {{ request()->is('kontak*') ? 'bg-green-200 text-green-900 hover:text-white' : '' }} dark:text-white rounded-full visited:text-green-900 dark:visited:text-green-600 dark:visited:hover:text-white px-5 py-3 transition duration-200">
                    Hubungi kami
                </a>
            </li>
        </ul>

        @if (Auth::user())
            <div class="hidden sm:flex sm:items-center ms-auto gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="Auth::user()->role === 'admin' ? '/admin/dashboard' : '/dashboard-pelayanan'">
                            Dashboard
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        @else
            <div class="hidden gap-3 ml-auto md:flex">
                <a href="/login"
                    class="px-5 py-3 text-green-700 transition duration-200 rounded-full hover:bg-green-700/20 dark:hover:text-white visited:text-green-900 dark:visited:text-green-600">
                    Login <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </a>
                <a href="/register"
                    class="px-5 py-3 text-white transition duration-200 bg-green-700 border border-green-700 rounded-full hover:bg-green-500 hover:border-green-500">Register</a>
            </div>

            <button type="button"
                class="block px-3 py-1 ml-auto rounded dark:text-white md:hidden focus:ring-1 ring-green-700">
                <i class="fa-solid fa-bars"></i>
            </button>
        @endif
    </div>
</nav>
