<footer class="pt-16 pb-8 bg-gray-900 text-gray-300">
    <div class="container px-6 mx-auto max-w-6xl">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-start md:justify-items-center">

            <div class="flex items-start gap-5 max-w-sm">
                <img src="{{ asset('/images/logo-bmkg.png') }}" alt="Logo BMKG Geofisika Yogyakarta"
                    class="h-20 w-auto shrink-0">

                <div class="space-y-3">
                    <h4 class="text-white font-semibold text-lg">
                        Stasiun Geofisika Sleman
                    </h4>

                    <p class="text-sm leading-relaxed text-gray-400">
                        Jl. Wates KM 7 Jitengan, Balecatur <br>
                        Gamping, Sleman 55294
                    </p>

                    <div class="text-sm text-gray-400 space-y-1">
                        <p><a class="hover:text-white transition">📞 (0274) 6498383</a></p>
                        <p><a href="https://wa.me/6289612643202" class="hover:text-white transition">📱
                                0896-1264-3202</a></p>
                        <p><a href="mailto:stageof.yogya@bmkg.go.id" class="hover:text-white transition">✉️
                                stageof.yogya@bmkg.go.id</a></p>
                    </div>
                </div>
            </div>

            <div class="text-center md:text-left">
                <h4 class="text-white font-semibold text-lg mb-4">Media Sosial</h4>
                <div class="flex flex-col space-y-3 text-gray-400">
                    <a href="https://www.instagram.com/stageofsleman/" target="_blank" rel="noopener noreferrer"
                        class="flex items-center gap-2 hover:text-white transition" aria-label="Instagram BMKG">
                        <i class="fa-brands fa-instagram"></i> Instagram
                    </a>

                    <a href="https://x.com/bmkgjogja" target="_blank" rel="noopener noreferrer"
                        class="flex items-center gap-2 hover:text-white transition" aria-label="Twitter BMKG">
                        <i class="fa-brands fa-twitter"></i> Twitter / X
                    </a>
                </div>
            </div>

            <div class="text-center md:text-left">
                <h4 class="text-white font-semibold text-lg mb-4">Sitemap</h4>
                <nav class="flex flex-col space-y-2.5 text-gray-400">
                    <a href="/" class="block hover:text-white transition">Beranda</a>
                    <a href="/profil" class="block hover:text-white transition">Profil</a>
                    <div class="pt-2 border-t border-gray-800 flex flex-col space-y-2.5">
                        <a href="/#layanan"
                            class="block hover:text-white transition text-xs uppercase tracking-widest font-bold text-gray-500 mb-1">Layanan</a>
                        <a href="{{ route('alur-pelayanan') }}" class="block hover:text-white transition text-sm">Alur
                            Pelayanan</a>
                        <a href="{{ route('tarif-layanan') }}" class="block hover:text-white transition text-sm">Jenis
                            Layanan & Tarif</a>
                        <a href="{{ route('standar-pelayanan-jasa') }}"
                            class="block hover:text-white transition text-sm">Standar Pelayanan</a>
                        <a href="{{ route('pembayaran-pnbp') }}" class="block hover:text-white transition text-sm">Cara
                            Pembayaran</a>
                        <a href="{{ route('regulasi-ptsp') }}"
                            class="block hover:text-white transition text-sm">Regulasi PTSP</a>
                    </div>
                    @if(Auth::check() && Auth::user()->role !== 'admin')
                        <a href="{{ route('rating.create') }}"
                            class="block hover:text-white transition pt-2 border-t border-gray-800">Saran</a>
                    @endif
                </nav>
            </div>

        </div>

        <div class="border-t border-gray-700 mt-14 pt-6 text-center text-sm text-gray-500">
            © {{ date('Y') }} BMKG Geofisika Yogyakarta. Seluruh hak cipta dilindungi.
        </div>

    </div>
</footer>