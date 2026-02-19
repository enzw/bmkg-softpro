@extends('layouts.main')

@section('content')
    <div class="landing-page dark:bg-slate-900 dark:text-white">
        {{-- ===== HERO SECTION ===== --}}
        <header
            class="relative pt-[70px] h-[85vh] lg:h-[95vh] overflow-hidden bg-[linear-gradient(135deg,rgba(20,83,45,0.85),rgba(6,78,59,0.9)),url('/public/images/slides/0.jpeg')] bg-cover bg-no-repeat bg-fixed bg-center">
            <!-- Animated background elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl -mr-48 -mt-48"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-green-500/10 rounded-full blur-3xl -ml-48 -mb-48"></div>
            </div>

            <div class="container grid h-full px-4 mx-auto text-center text-white place-content-center relative z-10">
                {{-- Eyebrow --}}
                <a href="https://apps.bmkg.go.id/" target="__blank"
                    class="px-6 py-2 mx-auto text-xs font-bold transition duration-300 rounded-full bg-white/10 border border-white/20 hover:bg-white/20 backdrop-blur-md max-w-max mb-8 tracking-widest uppercase group">
                    <i class="mr-2 text-emerald-400 fa-solid fa-bullhorn"></i> Download aplikasi BMKG
                    <i class="fa-solid fa-angle-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>

                <h1 class="max-w-5xl text-4xl sm:text-6xl lg:text-7xl font-extrabold leading-tight mb-6">
                    Pelayanan Informasi <span class="text-emerald-400">Geofisika</span>
                </h1>
                <p class="text-lg sm:text-xl text-white/60 max-w-2xl mx-auto leading-relaxed font-medium">
                    Akses informasi geofisika langsung dari BMKG dengan cepat, akurat, dan transparan.
                </p>

                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="#layanan"
                        class="px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-xl shadow-green-500/25">
                        Lihat Layanan
                    </a>
                    <!-- <a href="{{ route('login') }}"
                        class="px-8 py-4 bg-white/10 border border-white/20 text-white font-bold uppercase tracking-widest rounded-xl hover:bg-white/20 transition-all backdrop-blur-md">
                        Mulai Sekarang
                    </a> -->
                </div>
            </div>
        </header>

        <section id="layanan" class="layanan py-24 relative z-0 bg-gray-50 dark:bg-slate-900">
            <div class="container px-4 mx-auto">
                <div class="mb-12 text-center">
                    <span
                        class="inline-block px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-sm font-semibold mb-4">Layanan
                        Tersedia</span>
                    <h3 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">Layanan Kami</h3>
                    <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Berbagai layanan geofisika komprehensif
                        untuk memenuhi kebutuhan Anda</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- @dd($layanan[0]['images']) --}}
                    @foreach ($layanan as $item)
                        <div
                            class="group overflow-hidden flex flex-col transition duration-500 bg-white dark:bg-slate-800 rounded-xl shadow-lg dark:shadow-slate-900/50 hover:-translate-y-3 hover:shadow-2xl dark:hover:shadow-green-900/30 border border-gray-100 dark:border-slate-700 hover:border-green-200 dark:hover:border-green-800">
                            <div class="relative overflow-hidden h-52 bg-gray-200 dark:bg-slate-700">
                                <img src="{{ asset($item['images']) }}" alt="{{ $item['nama'] }}" height="200"
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                                </div>
                            </div>
                            <div class="px-6 py-5 flex flex-col flex-grow">
                                <h4
                                    class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition duration-300">
                                    {{ $item['nama'] }}
                                </h4>
                                <p class="hidden md:block text-gray-600 dark:text-gray-400 text-sm flex-grow leading-relaxed">
                                    {{ $item['deskripsi'] }}
                                </p>
                                <a href="{{ $item['url'] }}"
                                    class="mt-5 inline-block px-5 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition duration-300 text-center shadow-lg hover:shadow-green-600/50 group/btn">
                                    <i
                                        class="fa-solid fa-arrow-right mr-2 group-hover/btn:translate-x-1 transition-transform inline-block"></i>Lihat
                                    Layanan
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
                    @for ($i = 0; $i < 4; $i++) <div
                        class="overflow-hidden bg-white rounded-md shadow dark:bg-gray-700 animate-pulse">
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
                <div class="overflow-hidden transition bg-white rounded-md shadow-lg dark:bg-gray-700 hover:shadow-xl">

                    <a href="{{ $artikel['url'] }}" target="_blank" class="block overflow-hidden">
                        <img src="{{ $artikel['image'] ?: asset('images/placeholder.jpg') }}"
                            class="w-full h-[200px] object-cover transition duration-300 hover:scale-105"
                            alt="{{ $artikel['title'] }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                    </a>

                    <div class="px-4 py-3">
                        <h4 class="text-lg font-bold leading-tight line-clamp-2">
                            <a href="{{ $artikel['url'] }}" target="_blank" class="block transition hover:text-green-700">
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

    <section id="tutorial"
        class="py-20 bg-gradient-to-b from-green-50/30 to-white dark:from-slate-800 dark:to-slate-900 relative z-0 overflow-hidden">
        <div class="container px-4 mx-auto">
            <div class="mb-12 text-center">
                <span
                    class="inline-block px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-semibold mb-4">Panduan
                    Permohonan</span>
                <h3 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">Cara Mengajukan Permohonan
                </h3>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Ikuti langkah-langkah sederhana untuk
                    mengajukan permohonan layanan</p>
            </div>

            <div class="max-w-4xl mx-auto space-y-3">
                @foreach ($layanan as $item)
                    <div
                        class="group overflow-hidden flex flex-col transition duration-500 bg-white dark:bg-slate-800 rounded-xl shadow-lg dark:shadow-slate-900/50 hover:-translate-y-3 hover:shadow-2xl dark:hover:shadow-green-900/30 border border-gray-100 dark:border-slate-700 hover:border-green-200 dark:hover:border-green-800">
                        <button onclick="toggleAccordion(this)"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 dark:hover:from-slate-700 dark:hover:to-slate-700 transition duration-200 group">
                            <h4
                                class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-3 group-hover:text-green-600 dark:group-hover:text-green-400 transition duration-300">
                                <span
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:bg-green-600 dark:group-hover:bg-green-600 group-hover:text-white dark:group-hover:text-white transition duration-300 flex-shrink-0">
                                    <i
                                        class="fas fa-chevron-right text-sm group-hover:translate-x-1 transition-transform duration-200 accordion-icon"></i>
                                </span>
                                {{ $item['nama'] }}
                            </h4>
                        </button>
                        <div
                            class="accordion-content max-h-0 overflow-hidden transition-all duration-300 border-t border-gray-200 dark:border-slate-700">
                            <div class="px-6 py-6 bg-gradient-to-b from-gray-50 to-white dark:from-slate-700 dark:to-slate-800">
                                @guest
                                    <div class="space-y-4">
                                        <p class="text-gray-700 dark:text-gray-300 mb-4">Untuk mengajukan permohonan layanan ini,
                                            Anda perlu memiliki akun terlebih dahulu.</p>

                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <a href="{{ route('login') }}"
                                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300 text-center">
                                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                                            </a>
                                            <a href="{{ route('register') }}"
                                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 text-center">
                                                <i class="fas fa-user-plus mr-2"></i> Daftar Akun
                                            </a>
                                        </div>
                                    </div>
                                @endguest

                                @auth
                                    <div class="space-y-4">
                                        <div
                                            class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-4">
                                            <p class="text-green-800 dark:text-green-200 text-sm">
                                                <i class="fas fa-check-circle mr-2"></i> Anda sudah login, siap untuk mengajukan
                                                permohonan!
                                            </p>
                                        </div>

                                        <p class="text-gray-700 dark:text-gray-300 font-semibold mb-3">Informasi yang perlu Anda
                                            siapkan:</p>

                                        <ul class="space-y-2 text-gray-700 dark:text-gray-300 mb-6">
                                            @if (str_contains($item['url'], 'sewa-alat'))
                                                {{-- Jasa Sewa Alat MKG --}}
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Pilih jenis alat yang ingin disewa</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Tentukan jumlah unit yang dibutuhkan</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Tentukan tanggal mulai dan tanggal berakhir sewa</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Berikan keterangan/deskripsi kebutuhan Anda</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Upload surat permohonan (opsional)</span>
                                                </li>
                                            @elseif (str_contains($item['url'], 'pelayanan-jasa'))
                                                {{-- Pelayanan Informasi Geofisika --}}
                                                <div
                                                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded p-3 mb-4">
                                                    <p class="text-sm text-blue-800 dark:text-blue-200 font-semibold mb-2">Ada 6 jenis
                                                        layanan yang tersedia:</p>
                                                    <ul class="space-y-1 text-sm text-blue-700 dark:text-blue-300">
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-blue-600 font-bold">1.</span>
                                                            <span><strong>Magang</strong> - Program pelatihan di BMKG</span>
                                                        </li>
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-blue-600 font-bold">2.</span>
                                                            <span><strong>Layanan Klaim Asuransi</strong> - Informasi untuk klaim
                                                                asuransi bencana alam</span>
                                                        </li>
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-blue-600 font-bold">3.</span>
                                                            <span><strong>Layanan Data</strong> - Permintaan data geofisika</span>
                                                        </li>
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-blue-600 font-bold">4.</span>
                                                            <span><strong>Layanan Peta Sebaran</strong> - Jasa peta sebaran
                                                                geofisika</span>
                                                        </li>
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-blue-600 font-bold">5.</span>
                                                            <span><strong>Layanan Survey</strong> - Survey geofisika lapangan</span>
                                                        </li>
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-blue-600 font-bold">6.</span>
                                                            <span><strong>Layanan Konsultasi</strong> - Konsultasi teknis
                                                                geofisika</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Informasi umum
                                                    yang diperlukan untuk semua layanan:</p>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Pilih jenis layanan yang Anda butuhkan</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Data pribadi atau instansi lengkap (nama, email, telepon)</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Deskripsi detail tentang kebutuhan spesifik Anda</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Upload surat permohonan (opsional)</span>
                                                </li>
                                            @elseif (str_contains($item['url'], 'permohonan-kunjungan'))
                                                {{-- Permohonan Kunjungan --}}
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Pilih jenis kunjungan (Go To School atau Go To BMKG)</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Nama instansi/sekolah yang akan berkunjung</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Nama lengkap penanggung jawab</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Nomor WhatsApp yang dapat dihubungi</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Tanggal rencana kunjungan</span>
                                                </li>
                                                <li class="flex items-start gap-3">
                                                    <span class="text-green-600 font-bold">•</span>
                                                    <span>Jumlah peserta rombongan</span>
                                                </li>
                                            @endif
                                        </ul>

                                        <a href="{{ $item['url'] }}"
                                            class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300">
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



    <section id="hubungi-kami"
        class="py-20 bg-gradient-to-br from-white via-gray-50 to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 relative overflow-hidden z-0">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-500/10 dark:bg-green-500/10 rounded-full blur-3xl">
            </div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500/10 dark:bg-blue-500/10 rounded-full blur-3xl">
            </div>
        </div>

        <div class="container px-4 mx-auto relative z-10">
            <div class="mb-16 text-center">
                <span
                    class="inline-block px-4 py-2 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 rounded-full text-sm font-semibold mb-4 border border-green-200 dark:border-green-500/30">Kontak</span>
                <h2 class="mb-4 text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white">Hubungi Kami</h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Kami siap membantu menjawab pertanyaan dan
                    kebutuhan Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <!-- Alamat -->
                <div
                    class="group bg-white dark:bg-white/10 backdrop-blur-lg rounded-2xl shadow-lg dark:shadow-xl p-8 text-center hover:bg-gray-50 dark:hover:bg-white/20 transition duration-300 border border-gray-200 dark:border-white/20 hover:border-green-300 dark:hover:border-white/40 hover:-translate-y-2">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full mb-6 shadow-lg group-hover:scale-110 transition duration-300">
                        <i class="text-2xl text-white fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Alamat</h3>
                    <p class="text-gray-700 dark:text-gray-100 leading-relaxed">
                        Jl. Wates KM 7 Jitengan<br>
                        Balecatur, Gamping,
                        Sleman 55294<br>
                        Yogyakarta
                    </p>
                </div>

                <!-- Telepon -->
                <div
                    class="group bg-white dark:bg-white/10 backdrop-blur-lg rounded-2xl shadow-lg dark:shadow-xl p-8 text-center hover:bg-gray-50 dark:hover:bg-white/20 transition duration-300 border border-gray-200 dark:border-white/20 hover:border-blue-300 dark:hover:border-white/40 hover:-translate-y-2">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mb-6 shadow-lg group-hover:scale-110 transition duration-300">
                        <i class="text-2xl text-white fas fa-phone"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Telepon</h3>
                    <p class="text-gray-700 dark:text-gray-100 space-y-3">
                    <div>
                        <a
                            class="inline-block hover:text-green-600 dark:hover:text-green-300 transition duration-300 font-semibold">
                            (0274) 6498383
                        </a>
                    </div>
                    <div>
                        <a href="https://wa.me/6289612643202"
                            class="inline-flex items-center justify-center gap-2 hover:text-green-600 dark:hover:text-green-300 transition duration-300 font-semibold">
                            <i class="fab fa-whatsapp"></i> 0896-1264-3202
                        </a>
                    </div>
                    </p>
                </div>

                <!-- Email -->
                <div
                    class="group bg-white dark:bg-white/10 backdrop-blur-lg rounded-2xl shadow-lg dark:shadow-xl p-8 text-center hover:bg-gray-50 dark:hover:bg-white/20 transition duration-300 border border-gray-200 dark:border-white/20 hover:border-purple-300 dark:hover:border-white/40 hover:-translate-y-2">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-600 rounded-full mb-6 shadow-lg group-hover:scale-110 transition duration-300">
                        <i class="text-2xl text-white fas fa-envelope"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Email</h3>
                    <p class="text-gray-700 dark:text-gray-100">
                        <a href="mailto:stageof.yogya@bmkg.go.id"
                            class="hover:text-green-600 dark:hover:text-green-300 transition duration-300 font-semibold break-all">
                            stageof.yogya@bmkg.go.id
                        </a>
                    </p>
                </div>
            </div>

            <div
                class="rounded-2xl overflow-hidden shadow-lg dark:shadow-2xl border border-gray-200 dark:border-white/20 backdrop-blur-lg hover:shadow-xl dark:hover:shadow-green-900/50 transition duration-300">
                <iframe class="w-full h-[400px] md:h-[500px]"
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15811.000139468904!2d110.2945792!3d-7.8162625!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7af830003ce1ab%3A0xc798f492aac6a387!2sStasiun%20Geofisika%20Yogyakarta!5e0!3m2!1sen!2sid!4v1705299927452!5m2!1sen!2sid"
                    style="border:0;" allowfullscreen loading="lazy">
                </iframe>
            </div>
        </div>
    </section>
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