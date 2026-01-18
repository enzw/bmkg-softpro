@extends('layouts.main')

@section('content')
<div class="w-full min-h-screen bg-white dark:bg-gray-900 pt-32 pb-20 px-6">

    <div class="max-w-6xl mx-auto">

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-12">
            Hubungi Kami
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

            <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700">
                <iframe class="w-full h-[350px]"
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15811.000139468904!2d110.2945792!3d-7.8162625!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7af830003ce1ab%3A0xc798f492aac6a387!2sStasiun%20Geofisika%20Yogyakarta!5e0!3m2!1sen!2sid!4v1705299927452!5m2!1sen!2sid"
                    style="border:0;" allowfullscreen loading="lazy">
                </iframe>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 space-y-8 border border-gray-200 dark:border-gray-700">
                <div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Alamat</h4>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Stasiun Geofisika Sleman <br>
                        Jl. Wates KM 7 Jitengan, Balecatur, Gamping Sleman 55294
                    </p>
                </div>

                <div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Kontak</h4>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        (0274) 6498383 <br>
                        0896-1264-3203 <br>
                        stageof.yogya@bmkg.go.id
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
