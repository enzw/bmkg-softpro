@extends('layouts.main')

@section('title', 'Alur Pelayanan Data dan Informasi')

@section('content')
    <div class="dark:bg-slate-900 dark:text-white">

        {{-- ===== HERO SECTION ===== --}}
        <header class="relative pt-[70px] overflow-hidden bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
            {{-- Background decoration --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-40 -mt-40"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-40 -mb-20"></div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(ellipse_at_center,rgba(255,255,255,0.03)_0%,transparent_70%)]">
                </div>
            </div>

            <div class="container px-4 mx-auto py-16 relative z-10 text-center">
                <span
                    class="inline-block px-5 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 rounded-full text-xs font-semibold mb-6 tracking-wide uppercase">
                    <i class="fas fa-list-ol mr-2 text-emerald-400"></i>Panduan Layanan
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight">
                    Alur Pelayanan <span class="text-emerald-400">Data & Informasi</span>
                </h1>
                <p class="text-base text-white/60 max-w-xl mx-auto leading-relaxed font-medium">
                    Pelajari proses pelayanan data dan informasi geofisika BMKG dari pengajuan hingga penyerahan.
                </p>

                {{-- Step count badges --}}
                <div class="flex flex-wrap items-center justify-center gap-3 mt-10">
                    @foreach(['Permohonan', 'Verifikasi', 'Cek Data', 'Tarif', 'Pembayaran', 'Kuisioner', 'Penyerahan', 'Arsip'] as $i => $label)
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-white text-[10px] font-bold hover:bg-white/20 transition duration-200 uppercase tracking-wider">
                            <span
                                class="w-5 h-5 bg-emerald-400 text-green-950 rounded-lg flex items-center justify-center text-[9px] font-black">{{ $i + 1 }}</span>
                            {{ $label }}
                        </span>
                    @endforeach
                </div>
            </div>

        </header>

        {{-- ===== STEPS SECTION ===== --}}
        <section class="py-20 bg-gray-50 dark:bg-slate-900">
            <div class="container px-4 mx-auto max-w-4xl">

                @php
                    $steps = [
                        [
                            'number' => 1,
                            'role' => 'Pemohon',
                            'icon' => 'fa-file-alt',
                            'color' => 'from-blue-500 to-blue-600',
                            'accent' => 'blue',
                            'title' => 'Pengajuan Permohonan',
                            'desc' => 'Pemohon menyampaikan permohonan data secara langsung atau melalui email.',
                            'items' => [
                                '<i class="fas fa-envelope mr-2 text-blue-500"></i>Email: <a href="mailto:stageof.yogyakarta@bmkg.go.id" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">stageof.yogyakarta@bmkg.go.id</a>',
                                '<i class="fas fa-file-signature mr-2 text-blue-500"></i>Surat permohonan',
                                '<i class="fas fa-file-alt mr-2 text-blue-500"></i>Surat kuasa / surat tugas',
                                '<i class="fas fa-id-card mr-2 text-blue-500"></i>Fotocopy kartu identitas',
                            ],
                        ],
                        [
                            'number' => 2,
                            'role' => 'Petugas',
                            'icon' => 'fa-clipboard-check',
                            'color' => 'from-green-500 to-green-600',
                            'accent' => 'green',
                            'title' => 'Pemeriksaan Kelengkapan Persyaratan',
                            'desc' => 'Petugas pelayanan memeriksa kelengkapan persyaratan permohonan data yang diajukan oleh pemohon.',
                            'items' => [
                                '<i class="fas fa-check-circle mr-2 text-green-500"></i>Verifikasi surat permohonan',
                                '<i class="fas fa-check-circle mr-2 text-green-500"></i>Verifikasi identitas pemohon',
                                '<i class="fas fa-check-circle mr-2 text-green-500"></i>Verifikasi kelengkapan dokumen pendukung',
                            ],
                        ],
                        [
                            'number' => 3,
                            'role' => 'Petugas',
                            'icon' => 'fa-database',
                            'color' => 'from-emerald-500 to-teal-600',
                            'accent' => 'emerald',
                            'title' => 'Pemeriksaan Ketersediaan Data',
                            'desc' => 'Petugas pelayanan memeriksa ketersediaan data pada database pelayanan data BMKG.',
                            'items' => [
                                '<i class="fas fa-search mr-2 text-emerald-500"></i>Pencarian data pada database internal',
                                '<i class="fas fa-chart-bar mr-2 text-emerald-500"></i>Validasi kesesuaian parameter data',
                                '<i class="fas fa-clock mr-2 text-emerald-500"></i>Pengecekan rentang waktu data yang tersedia',
                            ],
                        ],
                        [
                            'number' => 4,
                            'role' => 'Petugas',
                            'icon' => 'fa-receipt',
                            'color' => 'from-amber-500 to-orange-500',
                            'accent' => 'amber',
                            'title' => 'Penyampaian Tarif dan E-Billing',
                            'desc' => 'Petugas pelayanan menyampaikan tarif dan e-billing kepada pemohon secara langsung atau melalui email/WA.',
                            'items' => [
                                '<i class="fas fa-money-bill-wave mr-2 text-amber-500"></i>Perhitungan tarif sesuai jenis data',
                                '<i class="fas fa-file-invoice-dollar mr-2 text-amber-500"></i>Pembuatan kode e-billing',
                                '<i class="fas fa-share-alt mr-2 text-amber-500"></i>Pengiriman via langsung / email / WhatsApp',
                            ],
                        ],
                        [
                            'number' => 5,
                            'role' => 'Pemohon',
                            'icon' => 'fa-credit-card',
                            'color' => 'from-violet-500 to-purple-600',
                            'accent' => 'violet',
                            'title' => 'Pembayaran E-Billing',
                            'desc' => 'Pemohon data melakukan pembayaran e-billing sesuai tarif yang telah ditetapkan melalui bank atau ATM.',
                            'items' => [
                                '<i class="fas fa-university mr-2 text-violet-500"></i>Pembayaran melalui bank / ATM',
                                '<i class="fas fa-mobile-alt mr-2 text-violet-500"></i>Mobile banking / internet banking',
                                '<i class="fas fa-receipt mr-2 text-violet-500"></i>Simpan bukti pembayaran e-billing',
                            ],
                        ],
                        [
                            'number' => 6,
                            'role' => 'Pemohon',
                            'icon' => 'fa-poll',
                            'color' => 'from-pink-500 to-rose-500',
                            'accent' => 'pink',
                            'title' => 'Pengisian Kuisioner',
                            'desc' => 'Pemohon mengisi kuisioner Kepuasan Pelanggan dan Persepsi Korupsi secara langsung atau melalui link online.',
                            'items' => [
                                '<i class="fas fa-smile mr-2 text-pink-500"></i>Kuisioner Kepuasan Pelanggan',
                                '<i class="fas fa-shield-alt mr-2 text-pink-500"></i>Kuisioner Persepsi Korupsi',
                                '<i class="fas fa-link mr-2 text-pink-500"></i><a href="http://bit.ly/QuestionerSKM-Stageof-Sleman" target="_blank" class="text-pink-600 dark:text-pink-400 font-semibold hover:underline">bit.ly/QuestionerSKM-Stageof-Sleman</a>',
                            ],
                        ],
                        [
                            'number' => 7,
                            'role' => 'Petugas',
                            'icon' => 'fa-handshake',
                            'color' => 'from-cyan-500 to-sky-600',
                            'accent' => 'cyan',
                            'title' => 'Penyerahan Data',
                            'desc' => 'Petugas Pelayanan menyerahkan data kepada Pemohon secara langsung atau melalui WhatsApp/email.',
                            'items' => [
                                '<i class="fas fa-hand-paper mr-2 text-cyan-500"></i>Penyerahan data secara langsung di kantor',
                                '<i class="fab fa-whatsapp mr-2 text-cyan-500"></i>Pengiriman via WhatsApp',
                                '<i class="fas fa-envelope mr-2 text-cyan-500"></i>Pengiriman via email',
                            ],
                        ],
                        [
                            'number' => 8,
                            'role' => 'Petugas',
                            'icon' => 'fa-archive',
                            'color' => 'from-slate-500 to-gray-600',
                            'accent' => 'slate',
                            'title' => 'Pengarsipan dan Pelaporan',
                            'desc' => 'Petugas Pelayanan menggandakan, mengarsipkan, dan menyerahkan informasi kepada Bendahara penerimaan dan Tata Usaha.',
                            'items' => [
                                '<i class="fas fa-copy mr-2 text-slate-500"></i>Penggandaan dokumen permohonan',
                                '<i class="fas fa-folder-open mr-2 text-slate-500"></i>Pengarsipan data pelayanan',
                                '<i class="fas fa-hand-holding-usd mr-2 text-slate-500"></i>Penyerahan ke Bendahara penerimaan',
                                '<i class="fas fa-building mr-2 text-slate-500"></i>Penyerahan ke Tata Usaha',
                            ],
                        ],
                    ];
                @endphp

                <div class="relative">
                    {{-- Vertical connector line --}}
                    <div class="absolute left-8 top-8 bottom-8 w-0.5 bg-gradient-to-b from-blue-200 via-green-200 to-slate-200 dark:from-blue-800 dark:via-green-800 dark:to-slate-700 hidden md:block"
                        style="left: 2.75rem;"></div>

                    <div class="space-y-6">
                        @foreach($steps as $step)
                                        @php
                                            $isEven = $step['number'] % 2 === 0;
                                        @endphp
                                        <div class="group relative flex items-start gap-6 step-card opacity-0 translate-y-6 transition-all duration-700"
                                            data-index="{{ $step['number'] - 1 }}">
                                            {{-- Step number circle --}}
                                            <div class="relative flex-shrink-0 z-10">
                                                <div
                                                    class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $step['color'] }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas {{ $step['icon'] }} text-white text-xl"></i>
                                                </div>
                                                <span
                                                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white dark:bg-slate-800 border-2 border-current text-{{ $step['accent'] }}-600 dark:text-{{ $step['accent'] }}-400 flex items-center justify-center text-[10px] font-black shadow">
                                                    {{ $step['number'] }}
                                                </span>
                                            </div>

                                            {{-- Content card --}}
                                            <div
                                                class="flex-1 bg-white dark:bg-slate-800 rounded-2xl shadow-md dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 group-hover:border-{{ $step['accent'] }}-200 dark:group-hover:border-{{ $step['accent'] }}-700 group-hover:shadow-xl dark:group-hover:shadow-{{ $step['accent'] }}-900/20 group-hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                                                {{-- Card top accent bar --}}
                                                <div class="h-1 bg-gradient-to-r {{ $step['color'] }}"></div>

                                                <div class="p-6">
                                                    <div class="flex items-start justify-between gap-4 mb-3">
                                                        <div>
                                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-2
                                                                                    {{ $step['role'] === 'Pemohon'
                            ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
                            : 'bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400' }}">
                                                                <i
                                                                    class="fas {{ $step['role'] === 'Pemohon' ? 'fa-user' : 'fa-user-tie' }} mr-1"></i>
                                                                {{ $step['role'] }}
                                                            </span>
                                                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $step['title'] }}
                                                            </h2>
                                                        </div>
                                                        <span
                                                            class="flex-shrink-0 text-3xl font-black text-gray-100 dark:text-slate-700 select-none">{{ str_pad($step['number'], 2, '0', STR_PAD_LEFT) }}</span>
                                                    </div>

                                                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4">
                                                        {{ $step['desc'] }}
                                                    </p>

                                                    <ul class="space-y-2">
                                                        @foreach($step['items'] as $item)
                                                            <li class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                                <span>{!! $item !!}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Arrow connector between steps (not after last) --}}
                                        @if (!$loop->last)
                                            <div class="flex items-center justify-start pl-[1.75rem] my-1 hidden md:flex" aria-hidden="true">
                                                <i class="fas fa-chevron-down text-gray-300 dark:text-slate-600 text-lg"></i>
                                            </div>
                                        @endif
                        @endforeach
                    </div>
                </div>

                {{-- CTA bottom --}}
                <div
                    class="mt-16 bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl p-8 text-center text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative z-10">
                        <i class="fas fa-info-circle text-3xl text-green-200 mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3">Butuh Bantuan?</h3>
                        <p class="text-white/80 mb-6 max-w-xl mx-auto">Hubungi kami untuk informasi lebih lanjut mengenai
                            pelayanan data dan informasi geofisika BMKG.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="mailto:stageof.yogyakarta@bmkg.go.id"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-green-700 font-semibold rounded-full hover:bg-green-50 transition duration-200 shadow-lg">
                                <i class="fas fa-envelope"></i> Kirim Email
                            </a>
                            <a href="https://wa.me/6289612643202" target="_blank"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/40 text-white font-semibold rounded-full hover:bg-white/30 transition duration-200">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a href="/"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/40 text-white font-semibold rounded-full hover:bg-white/30 transition duration-200">
                                <i class="fas fa-home"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <script>
        // Scroll-triggered entrance animation for step cards
        const cards = document.querySelectorAll('.step-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const index = parseInt(entry.target.dataset.index);
                    setTimeout(() => {
                        entry.target.classList.remove('opacity-0', 'translate-y-6');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                    }, index * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(card => observer.observe(card));
    </script>
@endsection