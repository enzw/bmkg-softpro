@extends('layouts.main')

@section('content')
    <div class="landing-page dark:bg-gray-800 dark:text-white">
        <header
            class="h-[calc(70vh+200px)] lg:h-[calc(100vh+200px)] bg-[linear-gradient(rgba(0,0,0,0.4),rgba(10,30,0,0.6)),url('/public/images/slides/0.jpeg')] bg-cover bg-no-repeat bg-fixed bg-center">
            <div class="container grid h-full px-4 mx-auto text-center text-white lg:h-screen place-content-center">
                {{-- Eyebrow --}}
                <a href="https://apps.bmkg.go.id/" target="__blank"
                    class="px-5 py-2 mx-auto text-sm transition duration-300 rounded-full bg-white/30 hover:bg-white hover:text-gray-700 hover:-translate-y-1 backdrop-blur max-w-max mb-7">
                    <i class="mr-2 text-green-500 fa-solid fa-bullhorn"></i> Download aplikasi BMKG sekarang
                    <i class="fa-solid fa-angle-right"></i>
                </a>

                <h2 class="max-w-5xl text-3xl font-black md:text-6xl">Pelayanan informasi Geofisika secara luas,
                    cepat, tepat, akurat dan mudah dipahami</h2>
            </div>
        </header>

        <section id="layanan" class="layanan -mt-[240px] pt-[100px]">
            <div class="container px-4 mx-auto">
                <h3 class="mb-3 text-3xl font-bold text-white">Layanan kami</h3>
                <div
                    class="grid grid-cols-1 p-5 rounded-lg md:grid-cols-2 lg:grid-cols-3 gap-x-3 gap-y-5 bg-gray-100/50 dark:bg-gray-700/50 backdrop-blur-lg">
                    {{-- @dd($layanan[0]['images']) --}}
                    @foreach ($layanan as $item)
                        <div
                            class="overflow-hidden flex flex-col transition duration-200 bg-white rounded-md shadow-lg dark:bg-gray-700 hover:-translate-y-2 hover:shadow-xl">
                            <img src="{{ asset($item['images']) }}" alt="{{ $item['nama'] }}" height="200"
                                class="w-full h-48 object-cover">
                            <div class="px-4 py-3 text flex flex-col flex-grow">
                                <h4 class="text-xl font-bold">
                                    {{ $item['nama'] }}
                                </h4>
                                <p class="hidden md:inline-block dark:text-gray-400 text-sm flex-grow">
                                    {{ $item['deskripsi'] }}</p>
                                <a href="{{ $item['url'] }}"
                                    class="mt-4 inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 text-center">
                                    Lihat Layanan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- <section id="latestNews" class="mt-10 latest-news">
            <div class="container px-4 mx-auto">
                <h3 class="mb-3 text-3xl font-bold">Berita Terkini</h3>

                <div id="news-loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-5">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="overflow-hidden bg-white rounded-md shadow dark:bg-gray-700 animate-pulse">
                            <div class="w-full h-[200px] bg-gray-300 dark:bg-gray-600"></div>
                            <div class="p-4 space-y-3">
                                <div class="w-3/4 h-4 bg-gray-300 rounded dark:bg-gray-600"></div>
                                <div class="w-1/2 h-3 bg-gray-200 rounded dark:bg-gray-500"></div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div id="news-real"
                    class="hidden grid grid-cols-1 p-5 rounded-lg md:grid-cols-2 lg:grid-cols-4 gap-x-3 gap-y-5 bg-gray-100/50 dark:bg-gray-700/50 backdrop-blur-lg">
                    @foreach ($berita as $artikel)
                        <div
                            class="overflow-hidden transition bg-white rounded-md shadow-lg dark:bg-gray-700 hover:shadow-xl">

                            <a href="{{ $artikel['url'] }}" target="_blank" class="block overflow-hidden">
                                <img src="{{ $artikel['image'] ?: asset('images/placeholder.jpg') }}"
                                    class="w-full h-[200px] object-cover transition duration-300 hover:scale-105"
                                    alt="{{ $artikel['title'] }}"
                                    onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            </a>

                            <div class="px-4 py-3">
                                <h4 class="text-lg font-bold leading-tight line-clamp-2">
                                    <a href="{{ $artikel['url'] }}" target="_blank"
                                        class="block transition hover:text-green-700">
                                        {{ $artikel['title'] }}
                                    </a>
                                </h4>

                                @if (!empty($artikel['date']))
                                    <small class="block mt-1 text-sm text-gray-400">
                                        {{ $artikel['date'] }}
                                    </small>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

                <a href="/berita"
                    class="block px-10 py-3 mx-auto mt-5 text-green-700 transition border border-green-700 rounded-full max-w-max hover:bg-green-700 hover:text-white">
                    Berita lainnya
                </a>
            </div>
        </section> --}}

        <section id="tutorial" class="mt-16 py-10">
            <div class="container px-4 mx-auto">
                <h3 class="mb-8 text-3xl font-bold text-white text-center">Cara Mengajukan Permohonan Layanan</h3>
                
                <div class="max-w-3xl mx-auto space-y-4">
                    @foreach ($layanan as $item)
                        <div class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 shadow-md">
                            <button onclick="toggleAccordion(this)" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-200 group">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-3">
                                    <span class="text-green-600 text-2xl">
                                        <i class="fas fa-chevron-right group-hover:translate-x-1 transition-transform duration-200 accordion-icon"></i>
                                    </span>
                                    {{ $item['nama'] }}
                                </h4>
                            </button>
                            <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300">
                                <div class="px-6 pb-6 pt-2 border-t border-gray-200 dark:border-gray-600">
                                    @guest
                                        <div class="space-y-4">
                                            <p class="text-gray-700 dark:text-gray-300 mb-4">Untuk mengajukan permohonan layanan ini, Anda perlu memiliki akun terlebih dahulu.</p>
                                            
                                            <div class="flex flex-col sm:flex-row gap-3">
                                                <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300 text-center">
                                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                                </a>
                                                <a href="{{ route('register') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 text-center">
                                                    <i class="fas fa-user-plus mr-2"></i> Daftar Akun
                                                </a>
                                            </div>
                                        </div>
                                    @endguest

                                    @auth
                                        <div class="space-y-4">
                                            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-4">
                                                <p class="text-green-800 dark:text-green-200 text-sm">
                                                    <i class="fas fa-check-circle mr-2"></i> Anda sudah login, siap untuk mengajukan permohonan!
                                                </p>
                                            </div>

                                            <p class="text-gray-700 dark:text-gray-300 font-semibold mb-3">Informasi yang perlu Anda siapkan:</p>
                                            
                                            <ul class="space-y-2 text-gray-700 dark:text-gray-300 mb-6">
                                                @if (str_contains($item['url'], 'sewa-alat'))
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Data pribadi lengkap (nama, email, nomor telepon)</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Pilih jenis dan spesifikasi alat yang ingin disewa</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Tentukan tanggal dan durasi peminjaman</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Pernyataan kesanggupan merawat alat dengan baik</span>
                                                    </li>
                                                @elseif (str_contains($item['url'], 'pelayanan-jasa'))
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Data pribadi atau instansi lengkap</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Jenis informasi geofisika yang dibutuhkan (gempa bumi, tsunami, listrik udara)</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Periode data yang diinginkan</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Lokasi atau area yang dimaksud</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Tujuan penggunaan data (penelitian, klaim asuransi, dll)</span>
                                                    </li>
                                                @else
                                                    {{-- Permohonan Kunjungan --}}
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Data institusi atau sekolah (nama, alamat, kontak)</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Tingkat pendidikan peserta kunjungan</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Tanggal dan waktu yang diinginkan untuk kunjungan</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Jumlah peserta yang akan berkunjung</span>
                                                    </li>
                                                    <li class="flex items-start gap-3">
                                                        <span class="text-green-600 font-bold">•</span>
                                                        <span>Nama dan kontak person yang bertanggung jawab</span>
                                                    </li>
                                                @endif
                                            </ul>

                                            <a href="{{ $item['url'] }}" class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300">
                                                <i class="fas fa-arrow-right mr-2"></i> Isi Formulir Permohonan
                                            </a>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="container mx-auto mt-10">
            <hr class="dark:border-white/20">
        </div>
    </div>
@endsection

<script>
    function toggleAccordion(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.accordion-icon');
        const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';
        
        document.querySelectorAll('.accordion-content').forEach(item => {
            if (item !== content) {
                item.style.maxHeight = '0px';
                item.previousElementSibling.querySelector('.accordion-icon').style.transform = 'rotate(0deg)';
            }
        });
        
        if (isOpen) {
            content.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.style.transform = 'rotate(90deg)';
        }
    }

    window.addEventListener('load', () => {
        const loading = document.getElementById('news-loading');
        const real = document.getElementById('news-real');

        if (loading && real) {
            loading.classList.add('hidden');
            real.classList.remove('hidden');
        }
    });
</script>
