@extends('layouts.main')

@section('content')
<div class="w-full min-h-screen bg-white dark:bg-gray-900 px-6 pt-40 pb-20">

    <div class="max-w-6xl mx-auto">

        <div class="mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Stasiun Geofisika Yogyakarta
            </h1>

            <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 leading-relaxed max-w-3xl font-medium">
                Pelayanan informasi Geofisika secara luas, cepat, tepat, akurat, dan mudah dipahami
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <img src="{{ asset('/images/slides/0.jpeg') }}"
                alt="Stasiun Geofisika Yogyakarta"
                class="w-full max-w-md mx-auto lg:mx-0 rounded-xl shadow-lg object-cover aspect-square">

            <div class="text-gray-700 dark:text-gray-300 leading-relaxed space-y-8">

                <div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Visi</h3>
                    <p class="text-base text-gray-700 dark:text-gray-400">
                        Mewujudkan BMKG yang handal, tanggap, dan mampu dalam rangka mendukung keselamatan masyarakat
                        serta keberhasilan pembangunan nasional dan berperan aktif di tingkat internasional.
                    </p>
                </div>

                <div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Misi</h3>
                    <div class="space-y-3 text-base text-gray-700 dark:text-gray-400">
                        <p>1. Mengamati dan memahami fenomena meteorologi, klimatologi, kualitas udara, dan geofisika.</p>
                        <p>2. Menyediakan data, informasi, dan jasa meteorologi, klimatologi, kualitas udara, dan geofisika yang handal dan terpercaya.</p>
                        <p>3. Mengkoordinasikan dan memfasilitasi kegiatan di bidang meteorologi, klimatologi, kualitas udara, dan geofisika.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
