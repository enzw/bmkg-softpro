@extends('layouts.main')

@php
$layanan = [
[
'images' => '/images/alat.png',
'nama' => 'Sewa Alat',
'url' => '/layanan/sewa-alat',
'deskripsi' => 'Sewa alat di Stasiun Geofisika Sleman memungkinkan pengguna atau pelanggan untuk
memanfaatkan berbagai peralatan canggih yang tersedia sesuai kebutuhan mereka. Proses peminjaman
alat dilakukan dengan mudah dan efisien, sesuai dengan tarif yang telah ditetapkan oleh pihak stasiun. ',
],
[
'images' => '/images/layanan.jpg',
'nama' => 'Pelayanan Jasa',
'url' => '/layanan/pelayanan-jasa',
'deskripsi' => 'Stasiun Geofisika juga menyediakan layanan informasi terkait gempa bumi, tsunami, dan listrik udara.
Informasi ini dapat dimanfaatkan untuk berbagai keperluan, termasuk klaim asuransi. Semua tarif layanan telah disesuaikan
dengan peraturan perundang-undangan yang berlaku, memastikan transparansi dan kepatuhan terhadap hukum.',
],
[
'images' => '/images/kunjungan.jpg',
'nama' => 'Permohonan Kunjungan',
'url' => '/layanan/permohonan-kunjungan',
'deskripsi' => 'Stasiun Geofisika Sleman menerima kunjungan edukatif dari berbagai tingkat pendidikan, mulai dari Taman Kanak-Kanak (TK) hingga perguruan tinggi.
Selain itu, Stasiun Geofisika Sleman juga menyediakan layanan BGTS (BMKG Goes to School). ',
],
];
@endphp

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
                <div class="overflow-hidden flex flex-col transition duration-200 bg-white rounded-md shadow-lg dark:bg-gray-700 hover:-translate-y-2 hover:shadow-xl">
                    <img src="{{ asset($item['images']) }}" alt="{{ $item['nama'] }}" height="200" class="w-full h-48 object-cover">
                    <div class="px-4 py-3 text flex flex-col flex-grow">
                        <h4 class="text-xl font-bold">
                            {{ $item['nama'] }}
                        </h4>
                        <p class="hidden md:inline-block dark:text-gray-400 text-sm flex-grow">{{ $item['deskripsi'] }}</p>
                        <a href="{{ $item['url'] }}" class="mt-4 inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 text-center">
                            Lihat Layanan
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="latestNews" class="mt-10 latest-news">
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

        <div id="news-real" class="hidden grid grid-cols-1 p-5 rounded-lg md:grid-cols-2 lg:grid-cols-4 gap-x-3 gap-y-5 bg-gray-100/50 dark:bg-gray-700/50 backdrop-blur-lg">
            @foreach ($berita as $artikel)
            <div class="overflow-hidden transition bg-white rounded-md shadow-lg dark:bg-gray-700 hover:shadow-xl">

                <a href="{{ $artikel['url'] }}" target="_blank" class="block overflow-hidden">
                    <img
                        src="{{ $artikel['image'] ?: asset('images/placeholder.jpg') }}"
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

                    @if(!empty($artikel['date']))
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
</section>


<div class="container mx-auto mt-10">
    <hr class="dark:border-white/20">
</div>
</div>
@endsection

<script>
    window.addEventListener('load', () => {
        const loading = document.getElementById('news-loading');
        const real = document.getElementById('news-real');

        if (loading && real) {
            loading.classList.add('hidden');
            real.classList.remove('hidden');
        }
    });
</script>