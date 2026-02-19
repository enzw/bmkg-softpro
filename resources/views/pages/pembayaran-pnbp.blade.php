@extends('layouts.main')

@section('title', 'Cara Pembayaran PNBP')

@section('content')
    <div class="dark:bg-slate-900 dark:text-white">

        {{-- ===== HERO SECTION ===== --}}
        <header class="relative pt-[70px] overflow-hidden bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-40 -mt-40"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-40 -mb-20"></div>
            </div>

            <div class="container px-4 mx-auto py-16 relative z-10 text-center">
                <span
                    class="inline-block px-5 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 rounded-full text-xs font-semibold mb-6 tracking-wide uppercase">
                    <i class="fas fa-credit-card mr-2 text-emerald-400"></i>Panduan Pembayaran
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                    Cara Pembayaran <span class="text-emerald-400">PNBP</span>
                </h1>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mb-6">Stasiun Geofisika Kelas I
                    Sleman</p>
                <p class="text-base text-white/60 max-w-xl mx-auto leading-relaxed font-medium">
                    Panduan resmi tata cara pemenuhan kewajiban pembayaran Penerimaan Negara Bukan Pajak (PNBP) di BMKG.
                </p>

                {{-- Source link --}}
                <div class="mt-8">
                    <a href="https://www.ptsp.bmkg.go.id" target="_blank"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 rounded-full text-xs font-semibold hover:bg-white/20 transition duration-200">
                        <i class="fas fa-external-link-alt text-emerald-400"></i>
                        www.ptsp.bmkg.go.id
                    </a>
                </div>
            </div>

            {{-- Wave bottom --}}
        </header>

        <section class="py-20 bg-gray-50 dark:bg-slate-900">
            <div class="container px-4 mx-auto max-w-5xl">

                {{-- ===== 4 CARA PEMBAYARAN (OVERVIEW STEPS) ===== --}}
                <div class="mb-16 info-card opacity-0 translate-y-6 transition-all duration-700">
                    <div class="text-center mb-10">
                        <span
                            class="inline-block px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-semibold mb-3">
                            <i class="fas fa-route mr-2"></i>Pilih Cara Pembayaran
                        </span>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">4 Cara Pembayaran PNBP</h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Pilih metode yang paling sesuai untuk Anda</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @php
                            $methods = [
                                [
                                    'num' => '01',
                                    'icon' => 'fa-university',
                                    'title' => 'Langsung di Kasir',
                                    'sub' => 'Semua Bank',
                                    'desc' => 'Datang langsung ke teller kasir bank mana saja dan serahkan kode billing',
                                    'color' => 'from-green-500 to-emerald-600',
                                    'bg' => 'bg-green-50 dark:bg-green-900/20',
                                    'border' => 'border-green-200 dark:border-green-800',
                                    'badge' => 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300',
                                    'tab' => null,
                                ],
                                [
                                    'num' => '02',
                                    'icon' => 'fa-credit-card',
                                    'title' => 'Melalui ATM',
                                    'sub' => 'BRI · Mandiri · BCA · BNI',
                                    'desc' => 'Gunakan mesin ATM bank pilihan Anda, masukkan kode billing 15 digit',
                                    'color' => 'from-blue-500 to-indigo-600',
                                    'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                                    'border' => 'border-blue-200 dark:border-blue-800',
                                    'badge' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300',
                                    'tab' => 'atm',
                                ],
                                [
                                    'num' => '03',
                                    'icon' => 'fa-mobile-alt',
                                    'title' => 'Internet / Mobile Banking',
                                    'sub' => 'BRI · Mandiri · BCA · BNI · BSI',
                                    'desc' => 'Bayar lewat aplikasi mobile banking atau internet banking bank Anda',
                                    'color' => 'from-violet-500 to-purple-600',
                                    'bg' => 'bg-violet-50 dark:bg-violet-900/20',
                                    'border' => 'border-violet-200 dark:border-violet-800',
                                    'badge' => 'bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300',
                                    'tab' => 'mbanking',
                                ],
                                [
                                    'num' => '04',
                                    'icon' => 'fa-shopping-bag',
                                    'title' => 'E-Commerce & Dompet Digital',
                                    'sub' => 'Gojek · Shopee · Dana · OVO · Indomart · Alfamart',
                                    'desc' => 'Bayar via aplikasi e-commerce, dompet digital, atau minimarket terdekat',
                                    'color' => 'from-orange-400 to-pink-500',
                                    'bg' => 'bg-orange-50 dark:bg-orange-900/20',
                                    'border' => 'border-orange-200 dark:border-orange-800',
                                    'badge' => 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300',
                                    'tab' => 'ecommerce',
                                ],
                            ];
                        @endphp

                        @foreach($methods as $m)
                            <div class="group relative rounded-2xl border {{ $m['border'] }} {{ $m['bg'] }} p-6 hover:shadow-xl dark:hover:shadow-slate-900/70 hover:-translate-y-1 transition-all duration-300 {{ $m['tab'] ? 'cursor-pointer' : '' }}"
                                {{ $m['tab'] ? 'onclick="switchTab(\'' . $m['tab'] . '\')"' : '' }}>
                                {{-- Number badge --}}
                                <span
                                    class="absolute -top-3 left-5 w-8 h-8 rounded-full bg-gradient-to-br {{ $m['color'] }} flex items-center justify-center text-white font-black text-xs shadow-lg">
                                    {{ $m['num'] }}
                                </span>
                                <div
                                    class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $m['color'] }} flex items-center justify-center shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas {{ $m['icon'] }} text-white text-lg"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1 leading-snug">
                                    {{ $m['title'] }}
                                </h3>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-3">{{ $m['sub'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $m['desc'] }}</p>
                                @if($m['tab'])
                                    <div
                                        class="mt-4 flex items-center gap-1 text-xs font-semibold {{ explode(' ', $m['badge'])[3] ?? 'text-blue-700' }}">
                                        <span class="px-3 py-1 rounded-full {{ $m['badge'] }}">Lihat panduan →</span>
                                    </div>
                                @else
                                    <div class="mt-4">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">
                                            Langsung ke bank
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ===== DIVIDER ===== --}}
                <div class="flex items-center gap-4 my-6 info-card opacity-0 translate-y-6 transition-all duration-700"
                    data-delay="100">
                    <div
                        class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-slate-600 to-transparent">
                    </div>
                    <span class="text-gray-400 dark:text-gray-500 text-sm font-semibold px-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-indigo-400"></i> Panduan Detail per Metode
                    </span>
                    <div
                        class="flex-1 h-px bg-gradient-to-l from-transparent via-gray-300 dark:via-slate-600 to-transparent">
                    </div>
                </div>

                {{-- ===== TABS ===== --}}
                <div id="section-panduan-detail"
                    class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-3xl shadow-sm dark:shadow-slate-900/60 border border-gray-100 dark:border-slate-700 overflow-hidden"
                    data-delay="200">

                    {{-- Tab buttons --}}
                    <div class="flex border-b border-gray-100 dark:border-slate-700 overflow-x-auto">
                        <button onclick="switchTab('atm')" id="tab-btn-atm"
                            class="tab-btn flex-1 min-w-[130px] flex items-center justify-center gap-2 px-6 py-4 text-sm font-semibold transition duration-200 border-b-2 border-blue-600 text-blue-700 dark:text-blue-400 bg-blue-50/60 dark:bg-blue-900/20">
                            <i class="fas fa-credit-card"></i> ATM
                        </button>
                        <button onclick="switchTab('mbanking')" id="tab-btn-mbanking"
                            class="tab-btn flex-1 min-w-[130px] flex items-center justify-center gap-2 px-6 py-4 text-sm font-semibold transition duration-200 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700">
                            <i class="fas fa-mobile-alt"></i> Internet / Mobile Banking
                        </button>
                        <button onclick="switchTab('ecommerce')" id="tab-btn-ecommerce"
                            class="tab-btn flex-1 min-w-[130px] flex items-center justify-center gap-2 px-6 py-4 text-sm font-semibold transition duration-200 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700">
                            <i class="fas fa-shopping-bag"></i> E-Commerce
                        </button>
                    </div>

                    {{-- ============ TAB: ATM ============ --}}
                    <div id="tab-atm" class="tab-panel p-6 sm:p-8">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Pembayaran via ATM</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan 15 digit kode billing pada langkah
                                terakhir di setiap bank</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @php
                                $atmBanks = [
                                    [
                                        'name' => 'BRI',
                                        'color' => 'from-blue-600 to-blue-800',
                                        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                                        'border' => 'border-blue-100 dark:border-blue-800/40',
                                        'steps' => ['Pilih Pembayaran', 'Pilih Lainnya', 'Pilih MPN', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'Mandiri',
                                        'color' => 'from-yellow-500 to-yellow-700',
                                        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
                                        'border' => 'border-yellow-100 dark:border-yellow-800/40',
                                        'steps' => ['Pilih Bayar/Beli', 'Pilih Lainnya', 'Pilih Penerimaan Negara', 'Pilih Pajak/PNBP/Cukai', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'BCA',
                                        'color' => 'from-sky-500 to-sky-700',
                                        'bg' => 'bg-sky-50 dark:bg-sky-900/20',
                                        'border' => 'border-sky-100 dark:border-sky-800/40',
                                        'steps' => ['Pilih Pembayaran', 'Pilih MPN/Pajak', 'Pilih Penerimaan Negara', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'BNI',
                                        'color' => 'from-orange-500 to-orange-700',
                                        'bg' => 'bg-orange-50 dark:bg-orange-900/20',
                                        'border' => 'border-orange-100 dark:border-orange-800/40',
                                        'steps' => ['Pilih Pembayaran', 'Pilih Pajak/Penerimaan Negara', 'Pilih Pajak/PNPB/Bea & Cukai', 'Masukkan 15 digit kode billing'],
                                    ],
                                ];
                            @endphp

                            @foreach($atmBanks as $bank)
                                <div class="rounded-xl border {{ $bank['border'] }} {{ $bank['bg'] }} overflow-hidden">
                                    <div class="px-5 py-3 bg-gradient-to-r {{ $bank['color'] }} flex items-center gap-2">
                                        <span class="text-white font-black text-lg">{{ $bank['name'] }}</span>
                                    </div>
                                    <ul class="p-4 space-y-2">
                                        @foreach($bank['steps'] as $i => $step)
                                            <li class="flex items-start gap-3 text-sm text-gray-700 dark:text-gray-300">
                                                <span
                                                    class="flex-shrink-0 w-5 h-5 rounded-full bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-gray-400 mt-0.5">{{ $i + 1 }}</span>
                                                {{ $step }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="mt-5 flex items-start gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40 rounded-xl">
                            <i class="fas fa-lightbulb text-indigo-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm text-indigo-800 dark:text-indigo-300">Kode <strong>billing 15 digit</strong>
                                diperoleh setelah mengajukan permohonan dan petugas menyampaikan tarif melalui sistem PTSP.
                            </p>
                        </div>
                    </div>

                    {{-- ============ TAB: INTERNET/MOBILE BANKING ============ --}}
                    <div id="tab-mbanking" class="tab-panel p-6 sm:p-8 hidden">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Pembayaran via Internet /
                                Mobile Banking</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Buka aplikasi mobile banking atau akses
                                internet banking bank Anda</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @php
                                $mbankBanks = [
                                    [
                                        'name' => 'BRI',
                                        'color' => 'from-blue-600 to-blue-800',
                                        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                                        'border' => 'border-blue-100 dark:border-blue-800/40',
                                        'steps' => ['Pilih Menu Lainnya', 'Pilih Tagihan', 'Pilih Penerimaan Negara', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'Mandiri',
                                        'color' => 'from-yellow-500 to-yellow-700',
                                        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
                                        'border' => 'border-yellow-100 dark:border-yellow-800/40',
                                        'steps' => ['Pilih Bayar', 'Pilih Pembayaran Baru', 'Pilih Penerimaan Negara', 'Pilih IDR Pajak/PNBP/Cukai', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'BCA',
                                        'color' => 'from-sky-500 to-sky-700',
                                        'bg' => 'bg-sky-50 dark:bg-sky-900/20',
                                        'border' => 'border-sky-100 dark:border-sky-800/40',
                                        'steps' => ['Pilih Pembayaran', 'Pilih Pajak', 'Pilih Penerimaan Negara', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'BNI',
                                        'color' => 'from-orange-500 to-orange-700',
                                        'bg' => 'bg-orange-50 dark:bg-orange-900/20',
                                        'border' => 'border-orange-100 dark:border-orange-800/40',
                                        'steps' => ['Pilih Transaksi', 'Pilih Pembelian/Pembayaran', 'Pilih Pembayaran Tagihan', 'Pilih Penerimaan Negara', 'Pilih Pajak/PNBP/Cukai', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'BSI',
                                        'color' => 'from-teal-600 to-teal-800',
                                        'bg' => 'bg-teal-50 dark:bg-teal-900/20',
                                        'border' => 'border-teal-100 dark:border-teal-800/40',
                                        'steps' => ['Pilih Menu Bayar', 'Pilih Penerimaan Negara', 'Pilih Pajak/PNPB/Cukai', 'Masukkan 15 digit kode billing'],
                                    ],
                                ];
                            @endphp

                            @foreach($mbankBanks as $bank)
                                <div class="rounded-xl border {{ $bank['border'] }} {{ $bank['bg'] }} overflow-hidden">
                                    <div class="px-5 py-3 bg-gradient-to-r {{ $bank['color'] }} flex items-center gap-2">
                                        <span class="text-white font-black text-lg">{{ $bank['name'] }}</span>
                                    </div>
                                    <ul class="p-4 space-y-2">
                                        @foreach($bank['steps'] as $i => $step)
                                            <li class="flex items-start gap-3 text-sm text-gray-700 dark:text-gray-300">
                                                <span
                                                    class="flex-shrink-0 w-5 h-5 rounded-full bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-gray-400 mt-0.5">{{ $i + 1 }}</span>
                                                {{ $step }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="mt-5 flex items-start gap-3 p-4 bg-violet-50 dark:bg-violet-900/20 border border-violet-100 dark:border-violet-800/40 rounded-xl">
                            <i class="fas fa-lightbulb text-violet-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm text-violet-800 dark:text-violet-300">Pastikan aplikasi mobile banking Anda
                                <strong>sudah diaktifkan</strong> dan memiliki limit transaksi yang cukup sebelum melakukan
                                pembayaran.
                            </p>
                        </div>
                    </div>

                    {{-- ============ TAB: E-COMMERCE ============ --}}
                    <div id="tab-ecommerce" class="tab-panel p-6 sm:p-8 hidden">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Pembayaran via E-Commerce &
                                Dompet Digital</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bayar kapan saja dan di mana saja lewat
                                platform digital favorit Anda</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @php
                                $ecommerce = [
                                    [
                                        'name' => 'Gojek',
                                        'color' => 'from-green-500 to-green-700',
                                        'bg' => 'bg-green-50 dark:bg-green-900/20',
                                        'border' => 'border-green-100 dark:border-green-800/40',
                                        'steps' => ['Pilih GoTagihan & Pulsa', 'Pilih Penerimaan Negara', 'Pilih Penerimaan Negara Bukan Pajak (PNBP)', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'Shopee',
                                        'color' => 'from-orange-500 to-red-600',
                                        'bg' => 'bg-orange-50 dark:bg-orange-900/20',
                                        'border' => 'border-orange-100 dark:border-orange-800/40',
                                        'steps' => ['Pilih lihat semua produk dan layanan', 'Pilih Layanan', 'Pilih Keuangan', 'Pilih Pulsa dan Tagihan', 'Pilih Layanan Pemerintah', 'Pilih Penerimaan Negara', 'Pilih Bayar PNBP lainnya', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'Dana',
                                        'color' => 'from-sky-500 to-blue-600',
                                        'bg' => 'bg-sky-50 dark:bg-sky-900/20',
                                        'border' => 'border-sky-100 dark:border-sky-800/40',
                                        'steps' => ['Buka aplikasi Dana', 'Pilih menu Lihat Semua', 'Kategori Layanan Pemerintah', 'Penerimaan Negara', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'OVO',
                                        'color' => 'from-purple-600 to-purple-800',
                                        'bg' => 'bg-purple-50 dark:bg-purple-900/20',
                                        'border' => 'border-purple-100 dark:border-purple-800/40',
                                        'steps' => ['Buka aplikasi OVO', 'Pilih menu Pilha Lainnya', 'Lainnya Penerimaan Negara', 'PNBP (SBN, Paspor, e-Tilang)', 'Masukkan 15 digit kode billing'],
                                    ],
                                    [
                                        'name' => 'Indomart / Alfamart',
                                        'color' => 'from-rose-500 to-rose-700',
                                        'bg' => 'bg-rose-50 dark:bg-rose-900/20',
                                        'border' => 'border-rose-100 dark:border-rose-800/40',
                                        'steps' => ['Kunjungi Indomart/Alfamart', 'Sampaikan 15 digit kode billing pada petugas kasir', 'Kasir menyampaikan jumlah tagihan', 'Pelanggan membayar jumlah yang disebutkan dan menyimpan bukti setor/bayar'],
                                    ],
                                ];
                            @endphp

                            @foreach($ecommerce as $platform)
                                <div class="rounded-xl border {{ $platform['border'] }} {{ $platform['bg'] }} overflow-hidden">
                                    <div class="px-5 py-3 bg-gradient-to-r {{ $platform['color'] }} flex items-center gap-2">
                                        <span class="text-white font-black text-lg">{{ $platform['name'] }}</span>
                                    </div>
                                    <ul class="p-4 space-y-2">
                                        @foreach($platform['steps'] as $i => $step)
                                            <li class="flex items-start gap-3 text-sm text-gray-700 dark:text-gray-300">
                                                <span
                                                    class="flex-shrink-0 w-5 h-5 rounded-full bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-gray-400 mt-0.5">{{ $i + 1 }}</span>
                                                {{ $step }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="mt-5 flex items-start gap-3 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800/40 rounded-xl">
                            <i class="fas fa-store text-orange-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm text-orange-800 dark:text-orange-300">Untuk pembayaran di
                                <strong>Indomart/Alfamart</strong>, simpan bukti setor/bayar sebagai dokumen penting untuk
                                proses selanjutnya.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ===== CTA ===== --}}
                <div class="mt-10 info-card opacity-0 translate-y-6 transition-all duration-700 bg-gradient-to-br from-indigo-700 via-purple-700 to-blue-800 rounded-2xl p-8 text-center text-white shadow-xl relative overflow-hidden"
                    data-delay="300">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative z-10">
                        <i class="fas fa-question-circle text-3xl text-indigo-200 mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3">Butuh Bantuan?</h3>
                        <p class="text-white/80 mb-6 max-w-xl mx-auto text-sm">Hubungi kami jika mengalami kendala dalam
                            proses pembayaran PNBP.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="mailto:stageof.yogya@bmkg.go.id"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-indigo-700 font-semibold rounded-full hover:bg-indigo-50 transition duration-200 shadow-lg">
                                <i class="fas fa-envelope"></i> stageof.yogya@bmkg.go.id
                            </a>
                            <a href="{{ route('alur-pelayanan') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/40 text-white font-semibold rounded-full hover:bg-white/30 transition duration-200">
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
        </section>
    </div>

    <script>
        // ===== Tab switching =====
        function switchTab(tab) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            // Reset all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-blue-600', 'border-violet-600', 'border-orange-500',
                    'text-blue-700', 'text-violet-700', 'text-orange-700',
                    'dark:text-blue-400', 'dark:text-violet-400', 'dark:text-orange-400',
                    'bg-blue-50/60', 'bg-violet-50/60', 'bg-orange-50/60',
                    'dark:bg-blue-900/20', 'dark:bg-violet-900/20', 'dark:bg-orange-900/20');
                btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });

            // Show target panel
            document.getElementById('tab-' + tab).classList.remove('hidden');

            // Style active button
            const activeBtn = document.getElementById('tab-btn-' + tab);
            activeBtn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');

            const colorMap = {
                atm: ['border-blue-600', 'text-blue-700', 'dark:text-blue-400', 'bg-blue-50/60', 'dark:bg-blue-900/20'],
                mbanking: ['border-violet-600', 'text-violet-700', 'dark:text-violet-400', 'bg-violet-50/60', 'dark:bg-violet-900/20'],
                ecommerce: ['border-orange-500', 'text-orange-700', 'dark:text-orange-400', 'bg-orange-50/60', 'dark:bg-orange-900/20'],
            };
            colorMap[tab].forEach(cls => activeBtn.classList.add(cls));

            // Smooth scroll to tabs section with offset for fixed header
            const element = document.getElementById('section-panduan-detail');
            const offset = 90; // Adjust based on your nav height
            const bodyRect = document.body.getBoundingClientRect().top;
            const elementRect = element.getBoundingClientRect().top;
            const elementPosition = elementRect - bodyRect;
            const offsetPosition = elementPosition - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }

        // ===== Scroll entrance animations =====
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