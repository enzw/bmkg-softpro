@extends('layouts.main')

@section('title', 'Regulasi PTSP')

@section('content')
    <div class="dark:bg-slate-900 dark:text-white">

        {{-- ===== HERO SECTION ===== --}}
        <header class="relative pt-[70px] overflow-hidden bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
            {{-- Background decoration --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-40 -mt-40"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-40 -mb-20"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(ellipse_at_center,rgba(255,255,255,0.03)_0%,transparent_70%)]"></div>
            </div>

            <div class="container px-4 mx-auto py-16 relative z-10 text-center">
                <span class="inline-block px-5 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 rounded-full text-xs font-semibold mb-6 tracking-wide uppercase">
                    <i class="fas fa-gavel mr-2 text-emerald-400"></i>Dasar Hukum & Aturan
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight">
                    Regulasi <span class="text-emerald-400">PTSP</span>
                </h1>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mb-6">Stasiun Geofisika Kelas I Sleman</p>
                <p class="text-base text-white/60 max-w-xl mx-auto leading-relaxed font-medium">
                    Landasan hukum dan kebijakan operasional Pelayanan Terpadu Satu Pintu (PTSP) di lingkungan BMKG.
                </p>
            </div>

        </header>

        {{-- ===== REGULATIONS SECTION ===== --}}
        <section class="py-20 bg-gray-50 dark:bg-slate-900">
            <div class="container px-4 mx-auto max-w-5xl">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @php
                        $regulations = [
                            [
                                'title' => 'Tarif Nol Rupiah (Rp 0,00)',
                                'subtitle' => 'Syarat dan Tata Cara Pengenaan Tarif Rp 0,00 Atas Jenis PNBP Terhadap Kegiatan Tertentu di Lingkungan BMKG',
                                'reference' => 'Sesuai dengan: Perka No. 12 Tahun 2019',
                                'desc' => 'Mengatur persyaratan dan tata cara pengenaan tarif nol rupiah bagi pemohon data dengan kriteria tertentu (pendidikan, penelitian, dll).',
                                'icon' => 'fa-percentage',
                                'color' => 'from-emerald-500 to-teal-600',
                                'accent' => 'emerald'
                            ],
                            [
                                'title' => 'Produk dan Tarif PNBP',
                                'subtitle' => 'Jenis dan Tarif Atas Jenis Penerimaan Negara Bukan Pajak yang Berlaku di BMKG',
                                'reference' => 'Sesuai dengan: PP No. 47 Tahun 2018',
                                'desc' => 'Daftar resmi jenis layanan dan besaran tarif Penerimaan Negara Bukan Pajak (PNBP) yang berlaku untuk seluruh produk jasa BMKG.',
                                'icon' => 'fa-receipt',
                                'color' => 'from-blue-500 to-indigo-600',
                                'accent' => 'blue'
                            ],
                            [
                                'title' => 'Kebijakan SMKI',
                                'subtitle' => 'Kebijakan Sistem Manajemen Keamanan Informasi ISO 27001: 2022',
                                'reference' => 'Sesuai standar internasional ISO/IEC 27001',
                                'desc' => 'Komitmen BMKG dalam menjaga kerahasiaan, keutuhan, dan ketersediaan aset informasi serta data pelayanan informasi geofisika.',
                                'icon' => 'fa-shield-halved',
                                'color' => 'from-violet-500 to-purple-600',
                                'accent' => 'violet'
                            ],
                            [
                                'title' => 'Alur Pelayanan PTSP',
                                'subtitle' => 'Manual Alur Pelayanan Terpadu Satu Pintu (PTSP) BMKG',
                                'reference' => 'Standar Operasional Prosedur (SOP)',
                                'desc' => 'Pedoman langkah-langkah pelayanan mulai dari pengajuan permohonan hingga penyerahan produk informasi kepada pelanggan.',
                                'icon' => 'fa-network-wired',
                                'color' => 'from-amber-500 to-orange-600',
                                'accent' => 'amber'
                            ]
                        ];
                    @endphp

                    @foreach($regulations as $reg)
                        <div class="reg-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-lg dark:shadow-slate-950/20 border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                            <div class="h-2 bg-gradient-to-r {{ $reg['color'] }}"></div>
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $reg['color'] }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas {{ $reg['icon'] }} text-white text-2xl"></i>
                                    </div>
                                    <div class="px-3 py-1 bg-{{ $reg['accent'] }}-50 dark:bg-{{ $reg['accent'] }}-900/30 text-{{ $reg['accent'] }}-700 dark:text-{{ $reg['accent'] }}-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-{{ $reg['accent'] }}-100 dark:border-{{ $reg['accent'] }}-800/50">
                                        Regulasi
                                    </div>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $reg['title'] }}</h3>
                                <p class="text-xs font-bold text-{{ $reg['accent'] }}-600 dark:text-{{ $reg['accent'] }}-400 uppercase tracking-wider mb-4 leading-relaxed">{{ $reg['reference'] }}</p>
                                
                                <div class="p-4 bg-gray-50 dark:bg-slate-900/50 rounded-2xl mb-4 border border-gray-100 dark:border-slate-700">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 italic">{{ $reg['subtitle'] }}</p>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $reg['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- CTA --}}
                <div class="mt-16 reg-card opacity-0 translate-y-6 transition-all duration-700 bg-gradient-to-br from-teal-700 to-emerald-800 rounded-3xl p-8 md:p-12 text-center text-white shadow-xl relative overflow-hidden">
                    {{-- Decorative pattern --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
                    
                    <div class="relative z-10 max-w-3xl mx-auto">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl mb-6 border border-white/30">
                            <i class="fas fa-file-pdf text-2xl text-white"></i>
                        </div>
                        <h2 class="text-3xl font-bold mb-4">Dokumen Digital Tersedia</h2>
                        <p class="text-teal-50 mb-8 text-lg">Pelajari lebih lanjut mengenai dasar hukum operasional PTSP BMKG melalui kanal resmi informasi hukum kami.</p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="https://jdih.bmkg.go.id/" target="_blank" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-teal-700 font-bold rounded-full hover:bg-teal-50 transition duration-200 shadow-lg group">
                                <i class="fas fa-external-link-alt group-hover:rotate-12 transition-transform"></i> Kunjungi JDIH BMKG
                            </a>
                            <a href="{{ route('alur-pelayanan') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-teal-600/40 backdrop-blur-md border border-white/30 text-white font-bold rounded-full hover:bg-teal-600/60 transition duration-200">
                                <i class="fas fa-arrow-right"></i> Lihat Alur Pelayanan
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <script>
        // Scroll entrance animations
        const cards = document.querySelectorAll('.reg-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
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
