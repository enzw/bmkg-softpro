@extends('layouts.main')

@section('title', 'Standar Pelayanan Jasa')

@section('content')
<div class="dark:bg-slate-900 dark:text-white">

    {{-- ===== HERO SECTION ===== --}}
    <header class="relative pt-[70px] overflow-hidden bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-40 -mt-40"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-40 -mb-20"></div>
        </div>

        <div class="container px-4 mx-auto py-16 relative z-10 text-center">
            <span class="inline-block px-5 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 rounded-full text-xs font-semibold mb-6 tracking-wide uppercase">
                <i class="fas fa-certificate mr-2 text-emerald-400"></i>Standar Layanan
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                Standar Pelayanan <span class="text-emerald-400">Jasa</span>
            </h1>
            <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mb-6">Stasiun Geofisika Kelas I Sleman</p>
            <p class="text-base text-white/60 max-w-xl mx-auto leading-relaxed font-medium">
                Informasi resmi mengenai dasar hukum, waktu operasional, dan persyaratan pelayanan jasa BMKG.
            </p>

            {{-- Quick info badges --}}
            <div class="flex flex-wrap items-center justify-center gap-3 mt-10">
                @foreach([
                    ['icon' => 'fa-gavel', 'label' => 'Dasar Hukum'],
                    ['icon' => 'fa-clock', 'label' => 'Waktu Pelayanan'],
                    ['icon' => 'fa-building', 'label' => 'Persyaratan Komersial'],
                    ['icon' => 'fa-hand-holding-heart', 'label' => 'Non-Komersial'],
                ] as $badge)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-white text-[10px] font-bold uppercase tracking-wider hover:bg-white/20 transition duration-200">
                        <i class="fas {{ $badge['icon'] }} text-emerald-400"></i>
                        {{ $badge['label'] }}
                    </span>
                @endforeach
            </div>
        </div>

    </header>

    {{-- ===== MAIN CONTENT ===== --}}
    <section class="py-20 bg-gray-50 dark:bg-slate-900">
        <div class="container px-4 mx-auto max-w-5xl">

            <div class="grid grid-cols-1 gap-8">

                {{-- ===== DASAR HUKUM ===== --}}
                <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-700 group">
                    <div class="h-1 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-gavel text-white text-lg"></i>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold uppercase tracking-widest mb-1">Regulasi</span>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dasar Hukum</h2>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach([
                                [
                                    'number' => '01',
                                    'title'  => 'Perka No. 12 Tahun 2019',
                                    'desc'   => 'Persyaratan dan Tata Cara Pengenaan Tarif Nol Rupiah Atas Jenis PNBP'
                                ],
                                [
                                    'number' => '02',
                                    'title'  => 'PP No. 47 Tahun 2018',
                                    'desc'   => 'Tentang Jenis dan Tarif Penerimaan Negara Bukan Pajak yang Berlaku di BMKG'
                                ],
                                [
                                    'number' => '03',
                                    'title'  => 'Perka No. 01 Tahun 2019',
                                    'desc'   => 'Tentang Pelayanan Terpadu Satu Pintu di BMKG'
                                ],
                            ] as $hukum)
                                <div class="flex items-start gap-4 p-4 rounded-xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition duration-200">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 font-black text-sm flex items-center justify-center">
                                        {{ $hukum['number'] }}
                                    </span>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $hukum['title'] }}</p>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-0.5 leading-relaxed">{{ $hukum['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ===== WAKTU PELAYANAN ===== --}}
                <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-700 group" data-delay="100">
                    <div class="h-1 bg-gradient-to-r from-cyan-500 to-teal-500"></div>
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-clock text-white text-lg"></i>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-xs font-bold uppercase tracking-widest mb-1">Jam Operasional</span>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Waktu Pelayanan</h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-center gap-4 p-5 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-100 dark:border-green-800/30">
                                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-calendar-week text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">Senin – Kamis</p>
                                    <p class="text-2xl font-black text-green-600 dark:text-green-400 mt-1">08.00 – 16.00</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">WIB</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-5 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-100 dark:border-emerald-800/30">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-calendar-day text-emerald-600 dark:text-emerald-400"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">Jumat</p>
                                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">08.00 – 16.30</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== PERSYARATAN ===== --}}
                <div class="info-card opacity-0 translate-y-6 transition-all duration-700" data-delay="200">
                    <div class="mb-3 text-center">
                        <span class="inline-block px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full text-sm font-semibold">
                            <i class="fas fa-clipboard-list mr-2"></i>Persyaratan Pelayanan
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Persyaratan Komersial --}}
                        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-lg hover:border-orange-200 dark:hover:border-orange-700 transition-all duration-300 group">
                            <div class="h-1 bg-gradient-to-r from-orange-400 to-amber-500"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center shadow group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">Persyaratan Komersial</h3>
                                        <span class="text-xs font-semibold text-orange-500 dark:text-orange-400 uppercase tracking-wider">(PNBP)</span>
                                    </div>
                                </div>
                                <ul class="space-y-3">
                                    @foreach([
                                        ['icon' => 'fa-file-signature', 'text' => 'Surat Kepermohonan'],
                                        ['icon' => 'fa-id-card',        'text' => 'Photocopy identitas diri'],
                                        ['icon' => 'fa-money-bill-wave','text' => 'Membayar biaya sesuai tarif'],
                                    ] as $item)
                                        <li class="flex items-start gap-3 p-3 rounded-xl bg-orange-50/50 dark:bg-orange-900/10 border border-orange-100 dark:border-orange-800/20">
                                            <span class="w-7 h-7 rounded-lg bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <i class="fas {{ $item['icon'] }} text-orange-500 text-xs"></i>
                                            </span>
                                            <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $item['text'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Persyaratan Non-Komersial --}}
                        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-lg hover:border-teal-200 dark:hover:border-teal-700 transition-all duration-300 group">
                            <div class="h-1 bg-gradient-to-r from-teal-400 to-cyan-500"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center shadow group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-hand-holding-heart text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">Persyaratan Non-Komersial</h3>
                                        <span class="text-xs font-semibold text-teal-500 dark:text-teal-400 uppercase tracking-wider">(Non PNBP)</span>
                                    </div>
                                </div>
                                <ul class="space-y-3">
                                    @foreach([
                                        ['icon' => 'fa-file-alt',   'text' => 'Surat Permohonan tarif nol rupiah (Kop surat dengan instansi pemohon)'],
                                        ['icon' => 'fa-id-card',    'text' => 'Fotocopy identitas'],
                                        ['icon' => 'fa-book',       'text' => 'Proposal'],
                                        ['icon' => 'fa-pen-fancy',  'text' => 'Mengisi Form Surat Pernyataan'],
                                    ] as $item)
                                        <li class="flex items-start gap-3 p-3 rounded-xl bg-teal-50/50 dark:bg-teal-900/10 border border-teal-100 dark:border-teal-800/20">
                                            <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <i class="fas {{ $item['icon'] }} text-teal-500 text-xs"></i>
                                            </span>
                                            <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $item['text'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== PERMOHONAN ONLINE ===== --}}
                <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-lg hover:border-violet-200 dark:hover:border-violet-700 group" data-delay="300">
                    <div class="h-1 bg-gradient-to-r from-violet-500 to-purple-600"></div>
                    <div class="p-8">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-paper-plane text-white text-2xl"></i>
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <span class="inline-block px-3 py-1 bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 rounded-full text-xs font-bold uppercase tracking-widest mb-2">Permohonan Online</span>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Kirim Berkas Secara Online</h2>
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4">
                                    Permohonan pelayanan dapat dilakukan secara online dengan mengirimkan berkas persyaratan melalui email resmi kami.
                                </p>
                                <a href="mailto:stageof.yogya@bmkg.go.id"
                                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white font-semibold rounded-full transition duration-300 shadow-lg hover:shadow-violet-500/40">
                                    <i class="fas fa-envelope"></i>
                                    stageof.yogya@bmkg.go.id
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== KELUHAN DAN SARAN ===== --}}
                <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-lg hover:border-rose-200 dark:hover:border-rose-700 group" data-delay="400">
                    <div class="h-1 bg-gradient-to-r from-rose-400 to-pink-500"></div>
                    <div class="p-8">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-comments text-white text-2xl"></i>
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <span class="inline-block px-3 py-1 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-full text-xs font-bold uppercase tracking-widest mb-2">Feedback</span>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Keluhan dan Saran</h2>
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4">
                                    Kami menghargai masukan Anda. Sampaikan keluhan atau saran melalui nomor berikut.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="tel:089612643202"
                                        class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-semibold rounded-full transition duration-300 shadow-lg hover:shadow-rose-400/40">
                                        <i class="fas fa-phone"></i>
                                        089612643202
                                    </a>
                                    <a href="https://wa.me/6289612643202" target="_blank"
                                        class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-full transition duration-300 shadow-lg hover:shadow-green-500/40">
                                        <i class="fab fa-whatsapp"></i>
                                        WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== QUICK ACTION ===== --}}
                <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-gradient-to-br from-blue-600 via-blue-700 to-teal-800 rounded-2xl p-8 text-center text-white shadow-xl relative overflow-hidden" data-delay="500">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative z-10">
                        <i class="fas fa-concierge-bell text-3xl text-cyan-200 mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3">Siap Mengajukan Permohonan?</h3>
                        <p class="text-white/80 mb-6 max-w-xl mx-auto text-sm">Ikuti alur pelayanan data dan informasi BMKG dari awal hingga selesai.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('alur-pelayanan') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-blue-700 font-semibold rounded-full hover:bg-blue-50 transition duration-200 shadow-lg">
                                <i class="fas fa-list-ol"></i> Lihat Alur Pelayanan
                            </a>
                            <a href="/"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/40 text-white font-semibold rounded-full hover:bg-white/30 transition duration-200">
                                <i class="fas fa-home"></i> Kembali ke Beranda
                            </a>
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
                    entry.target.classList.remove('opacity-0', 'translate-y-6');
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                }, delay);
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    infoCards.forEach(card => cardObserver.observe(card));
</script>
@endsection
