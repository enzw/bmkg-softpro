@extends('layouts.main')

@section('title', 'Profil Stasiun Geofisika Sleman')

@section('content')
<div class="dark:bg-slate-900 dark:text-white">

    {{-- ===== HERO SECTION ===== --}}
    <header class="relative pt-[70px] overflow-hidden bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-40 -mt-40"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-40 -mb-20"></div>
        </div>

        <div class="container px-4 mx-auto py-20 relative z-10 text-center">
            <span class="inline-block px-5 py-2 bg-white/15 backdrop-blur-sm border border-white/25 text-white/90 rounded-full text-sm font-semibold mb-6 tracking-wide">
                <i class="fas fa-info-circle mr-2 text-emerald-400"></i>Tentang Kami
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                Profil <span class="text-emerald-400">Lembaga</span>
            </h1>
            <p class="text-base font-semibold text-white/60 uppercase tracking-widest mb-6">Stasiun Geofisika Kelas I Sleman</p>
            <p class="text-lg text-white/70 max-w-2xl mx-auto leading-relaxed">
                Pelayanan informasi Geofisika secara luas, cepat, tepat, akurat, dan mudah dipahami di wilayah DIY.
            </p>
        </div>

    </header>

    {{-- ===== MAIN CONTENT ===== --}}
    <section class="py-20 bg-gray-50 dark:bg-slate-900">
        <div class="container px-4 mx-auto max-w-6xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                {{-- Image Column --}}
                <div class="info-card opacity-0 -translate-x-6 transition-all duration-700">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-gradient-to-br from-green-600 to-emerald-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition duration-500"></div>
                        <img src="{{ asset('/images/slides/0.jpeg') }}" alt="Stasiun Geofisika Sleman"
                            class="relative w-full rounded-2xl shadow-2xl object-cover aspect-[4/3] border border-white/20 dark:border-slate-700">
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md p-3 px-5 rounded-2xl border border-white/20 shadow-xl inline-block">
                                <p class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-1">Kantor Stasiun Geofisika</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Jl. Wates No.KM. 8, Sleman, DIY</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content Column --}}
                <div class="space-y-8">
                    {{-- Visi --}}
                    <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden border-t-4 border-t-emerald-500 hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-md">
                                    <i class="fas fa-eye text-white text-sm"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Visi</h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm">
                                Mewujudkan UPT – BMKG yang handal, tanggap dan mampu dalam rangka mendukung keselamatan
                                masyarakat serta keberhasilan pembangunan di wilayah DIY pada khususnya dan Pembangunan Nasional
                                pada umumnya, serta dapat berperan aktif sebagai ujung tombak BMKG di wilayah DIY.
                            </p>
                        </div>
                    </div>

                    {{-- Misi --}}
                    <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden border-t-4 border-t-emerald-500 hover:shadow-lg transition-shadow duration-300" data-delay="100">
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md">
                                    <i class="fas fa-bullseye text-white text-sm"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Misi</h3>
                            </div>
                            <div class="space-y-3">
                                @foreach([
                                    'Pengamatan dan Memahami Fenomena Geofisika di DIY dan Sekitarnya.',
                                    'Menyediakan Data, Informasi dan Pelayanan Geofisika di Wilayah DIY dan Sekitarnya.',
                                    'Mengkoordinasikan dan memfasilitasi kegiatan di bidang meteorologi, klimatologi, kualitas udara, dan geofisika.',
                                    'Berperan Aktif dalam Mendukung Kegiatan Pemerintah Daerah.'
                                ] as $index => $misi)
                                    <div class="flex items-start gap-3 p-3 rounded-2xl bg-teal-50/50 dark:bg-teal-900/10 border border-teal-100 dark:border-teal-800/30 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition duration-200">
                                        <span class="flex-shrink-0 w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-900/50 text-teal-600 dark:text-teal-400 font-black text-[10px] flex items-center justify-center">
                                            {{ $index + 1 }}
                                        </span>
                                        <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">{{ $misi }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

<script>
    const infoCards = document.querySelectorAll('.info-card');
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = parseInt(entry.target.dataset.delay || 0);
                setTimeout(() => {
                    entry.target.classList.remove('opacity-0', 'translate-y-6', '-translate-x-6');
                    entry.target.classList.add('opacity-100', 'translate-y-0', 'translate-x-0');
                }, delay);
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    infoCards.forEach(card => cardObserver.observe(card));
</script>
@endsection
