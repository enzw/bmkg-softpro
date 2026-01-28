<footer class="pt-16 pb-8 dark:bg-gray-800 dark:text-white">
    <div class="container grid grid-cols-2 grid-rows-2 gap-5 px-4 mx-auto md:grid-cols-6 md:grid-rows-1">
        <div class="flex col-span-2 gap-5 md:col-span-3 lg:col-span-3">
            <img src="{{ asset('/images/logo-bmkg.png') }}" alt="BMKG Geofisika Yogyakarta" class="h-[100px]">

            <div>
                <h4 class="mb-2 text-xl font-bold">Alamat</h4>
                <div class="flex flex-col gap-5 md:flex-row">
                    <p class="text-gray-400">
                        Stasiun Geofisika Sleman <br>
                        Jl. Wates KM 7 Jitengan, Balecatur, Gamping Sleman 55294</p>

                    <p class="text-gray-400">
                        (0274) 6498383 <br>
                        089612643203 <br>
                        stageof.yogya@bmkg.go.id</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:col-start-5">
            <h4 class="mb-2 text-xl font-bold">Media Sosial</h4>
            <a href="https://instagram.com" class="hover:text-black/50 visited:text-green-700"><i
                    class="fa-brands fa-instagram"></i> Instagram</a>
            <a href="https://twitter.com" class="hover:text-black/50 visited:text-green-700"><i
                    class="fa-brands fa-twitter"></i> Twitter/X</a>
        </div>
        <div class="flex flex-col">
            <h4 class="mb-2 text-xl font-bold">Sitemap</h4>
            <a href="/" class="hover:text-black/50 visited:text-green-700">Beranda</a>
            <a href="/tentang-kami" class="hover:text-black/50 visited:text-green-700">Tentang kami</a>
            <a href="/#layanan" class="hover:text-black/50 visited:text-green-700">Layanan</a>
            <a href="/berita" class="hover:text-black/50 visited:text-green-700">Berita</a>
        </div>
    </div>

    <div class="py-8 mt-10 text-sm text-center text-white bg-gray-800 dark:bg-gray-900">
        <div class="container max-w-lg px-4 mx-auto">
            <p class="mt-2 text-gray-400">
                Copyright &copy; <?php echo date('Y'); ?> - All Rights Reserved
            </p>
        </div>
    </div>
</footer>