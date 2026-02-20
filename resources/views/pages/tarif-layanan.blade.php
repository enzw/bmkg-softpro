@extends('layouts.main')

@section('title', 'Jenis Layanan dan Tarif PNBP')

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
                    <i class="fas fa-tags mr-2 text-emerald-400"></i>Informasi Biaya
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                    Tarif <span class="text-emerald-400">Layanan</span>
                </h1>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mb-6">Stasiun Geofisika Kelas I
                    Sleman</p>
                <p class="text-base text-white/60 max-w-xl mx-auto leading-relaxed font-medium">
                    Daftar resmi tarif Penerimaan Negara Bukan Pajak (PNBP) untuk seluruh layanan informasi geofisika.
                </p>
            </div>

        </header>

        <section class="py-20 bg-gray-50 dark:bg-slate-900">
            <div class="container px-4 mx-auto max-w-6xl">

                {{-- Ketentuan Umum Card --}}
                <div
                    class="mb-12 info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-100 dark:border-slate-700 shadow-xl">
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div
                            class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-balance-scale text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Penerapan Tarif Nol Rupiah</h2>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                Tarif PNBP dapat dikenakan sebesar <strong>Rp 0,00 (nol rupiah)</strong> untuk kegiatan
                                tertentu seperti penanggulangan bencana, pertahanan keamanan, pendidikan & penelitian
                                non-komersial, serta kegiatan sosial lainnya sesuai persyaratan peraturan yang berlaku.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tabs Header Section --}}
                <div class="mb-6 info-card opacity-0 translate-y-6 transition-all duration-700" data-delay="100">
                    <div class="text-center mb-10">
                        <span
                            class="inline-block px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-semibold mb-3">
                            <i class="fas fa-list-ul mr-2"></i>Kategori Layanan
                        </span>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Pilih Jenis Layanan</h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Daftar tarif terbagi ke dalam 7 kategori utama</p>
                    </div>
                </div>

                {{-- TABS NAVIGATION --}}
                <div class="info-card opacity-0 translate-y-6 transition-all duration-700 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden mb-10"
                    data-delay="200">
                    <div class="flex border-b border-gray-100 dark:border-slate-700 overflow-x-auto scrollbar-hide">
                        @php
                            $tabs = [
                                ['id' => 'info-mkg', 'label' => 'Informasi MKG', 'icon' => 'fa-satellite-dish', 'color' => 'blue'],
                                ['id' => 'konsultasi', 'label' => 'Konsultasi MKG', 'icon' => 'fa-comments-dollar', 'color' => 'amber'],
                                ['id' => 'kalibrasi', 'label' => 'Kalibrasi Alat', 'icon' => 'fa-tools', 'color' => 'emerald'],
                                ['id' => 'sewa-alat', 'label' => 'Penggunaan Alat', 'icon' => 'fa-stopwatch-20', 'color' => 'cyan'],
                                ['id' => 'stmkg', 'label' => 'STMKG', 'icon' => 'fa-university', 'color' => 'indigo'],
                                ['id' => 'diklat', 'label' => 'Diklat MKG', 'icon' => 'fa-chalkboard-teacher', 'color' => 'orange'],
                                ['id' => 'gedung', 'label' => 'Sewa Gedung', 'icon' => 'fa-building', 'color' => 'rose'],
                            ];
                        @endphp

                        @foreach($tabs as $tab)
                            <button onclick="switchTab('{{ $tab['id'] }}')" id="tab-btn-{{ $tab['id'] }}"
                                class="tab-btn flex-1 min-w-[130px] flex items-center justify-center gap-2 px-6 py-4 text-sm font-semibold transition duration-200 border-b-2 
                                                                                                                                                                                                                    {{ $loop->first ? 'active border-blue-600 text-blue-700 dark:text-blue-400 bg-blue-50/60 dark:bg-blue-900/20' : 'border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700' }}">
                                <i class="fas {{ $tab['icon'] }}"></i> {{ $tab['label'] }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Panels --}}
                    <div class="p-6 sm:p-8">

                        {{-- 1. INFORMASI MKG --}}
                        <div id="tab-info-mkg" class="tab-panel block space-y-12">

                            {{-- A. INFORMASI KHUSUS MKG --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                    <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> A. Informasi Khusus
                                    Meteorologi, Klimatologi, dan Geofisika
                                </h3>

                                <div
                                    class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
                                    <table class="w-full text-sm text-left">
                                        <thead
                                            class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                            <tr>
                                                <th class="px-8 py-5">Jenis Penerimaan negara bukan pajak</th>
                                                <th class="px-8 py-5">Satuan</th>
                                                <th class="px-8 py-5 text-right">Tarif</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            {{-- Basic Items 1-4 --}}
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-4 font-medium text-slate-700 dark:text-slate-300">1.
                                                    Informasi Cuaca untuk Penerbangan</td>
                                                <td
                                                    class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per route unit
                                                </td>
                                                <td
                                                    class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 text-xs">
                                                    4% dari biaya pelayanan jasa navigasi penerbangan</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-4 font-medium text-slate-700 dark:text-slate-300">2.
                                                    Informasi Cuaca untuk Pelayaran</td>
                                                <td
                                                    class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per route per
                                                    hari</td>
                                                <td
                                                    class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp 250.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-4 font-medium text-slate-700 dark:text-slate-300">3.
                                                    Informasi Cuaca untuk Pelabuhan</td>
                                                <td
                                                    class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per lokasi per
                                                    hari</td>
                                                <td
                                                    class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp 225.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-4 font-medium text-slate-700 dark:text-slate-300">4.
                                                    Informasi Cuaca untuk Pengeboran Lepas Pantai</td>
                                                <td
                                                    class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    dokumen per lokasi per hari</td>
                                                <td
                                                    class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp 330.000,00</td>
                                            </tr>

                                            {{-- Group 5: Agro Industri --}}
                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    5. Informasi Iklim untuk Agro Industri</td>
                                            </tr>
                                            @php
                                                $agro = [
                                                    ['a', 'Analisis dan Prakiraan Hujan Bulanan', 'per buku', '65.000'],
                                                    ['b', 'Prakiraan Musim Kemarau', 'per buku', '230.000'],
                                                    ['c', 'Prakiraan Musim Hujan', 'per buku', '230.000'],
                                                    ['d', 'Atlas Kesesuaian Agroklimat', 'per buku', '470.000'],
                                                    ['e', 'Atlas Normal Temperatur Periode 1981-2010', 'per buku', '1.500.000'],
                                                    ['f', 'Atlas Windrose Wilayah Indonesia Periode 1981-2010', 'per buku', '1.500.000'],
                                                    ['g', 'Atlas Curah Hujan di Indonesia Rata-rata Periode 1981-2010', 'per buku', '1.500.000'],
                                                ];
                                            @endphp
                                            @foreach($agro as $item)
                                                <tr
                                                    class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                    <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                        {{ $item[0] }}. {{ $item[1] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                        {{ $item[2] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                        Rp
                                                        {{ $item[3] }},00
                                                    </td>
                                                </tr>
                                            @endforeach

                                            {{-- Group 6: Kualitas Udara Industri --}}
                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    6. Informasi Kualitas Udara Rata-rata Mingguan untuk Industri</td>
                                            </tr>
                                            @php
                                                $air = [
                                                    ['a', 'Particulate Matter (PM10)', 'per stasiun per tahun', '70.000'],
                                                    ['b', 'Particulate Matter (PM2.5)', 'per stasiun per tahun', '70.000'],
                                                    ['c', 'Sulfur Dioksida (SO2)', 'per stasiun per tahun', '60.000'],
                                                    ['d', 'Nitrogen Oksida (NOx)', 'per stasiun per tahun', '60.000'],
                                                    ['e', 'Ozon (O3)', 'per stasiun per tahun', '60.000'],
                                                    ['f', 'Karbon Monoksida (CO)', 'per stasiun per tahun', '60.000'],
                                                    ['g', 'Karbon Dioksida (CO2)', 'per sampel', '80.000'],
                                                    ['h', 'Methan (CH4)', 'per sampel', '80.000'],
                                                ];
                                            @endphp
                                            @foreach($air as $item)
                                                <tr
                                                    class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                    <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                        {{ $item[0] }}. {{ $item[1] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                        {{ $item[2] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                        Rp
                                                        {{ $item[3] }},00
                                                    </td>
                                                </tr>
                                            @endforeach

                                            {{-- Group 7: Peta Kegempaan --}}
                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    7. Informasi Peta Kegempaan Untuk Perencanaan Kontruksi</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    a. Peta
                                                    Kegempaan</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    provinsi per tahun</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    250.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    b. Peta
                                                    Percepatan Tanah</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    provinsi per tahun</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    250.000,00</td>
                                            </tr>

                                            {{-- Group 8: Klaim Asuransi --}}
                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    8. Informasi Meteorologi, Klimatologi, dan Geofisika untuk Keperluan
                                                    Klaim Asuransi</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    a. Informasi
                                                    Meteorologi</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    lokasi per hari</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    175.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    b. Informasi
                                                    Geofisika</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    lokasi per hari</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    185.000,00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- B. INFORMASI KHUSUS MKG SESUAI PERMINTAAN --}}
                            <div class="mt-6 space-y-6 pt-10">
                                <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                    <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> B. Informasi Khusus
                                    Meteorologi, Klimatologi, dan Geofisika Sesuai Permintaan
                                </h3>

                                <div
                                    class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                    <table class="w-full text-sm text-left">
                                        <thead
                                            class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                            <tr>
                                                <th class="px-8 py-5">Jenis Penerimaan negara bukan pajak</th>
                                                <th class="px-8 py-5">Satuan</th>
                                                <th class="px-8 py-5 text-right">Tarif</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            {{-- Group 1: Meteorologi --}}
                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    1. Informasi Meteorologi</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    a. Informasi
                                                    Cuaca Khusus untuk Kegiatan Olah Raga</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    lokasi per hari</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    100.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    b. Informasi
                                                    Cuaca Khusus untuk Kegiatan Komersial Outdoor/Indoor</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    lokasi per hari</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    100.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    c. Informasi
                                                    Radar Cuaca (per 10 menit)</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    data per lokasi</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    70.000,00</td>
                                            </tr>

                                            {{-- Group 2: Klimatologi --}}
                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    2. Informasi Klimatologi</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 font-medium text-slate-700 dark:text-slate-300">
                                                    a.
                                                    Informasi Iklim Maritim</td>
                                                <td colspan="2"></td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-20 text-slate-700 dark:text-slate-300 font-medium">
                                                    1. Peta
                                                    Spasial Informasi Maritim</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    peta per bulan</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    300.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-20 text-slate-700 dark:text-slate-300 font-medium">
                                                    2. Informasi Tabular dan Grafik Maritim</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    tabel per bulan</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    350.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    b. Atlas
                                                    Potensi Rawan Banjir</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    atlas</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    350.000,00</td>
                                            </tr>

                                            {{-- Group 3: Perubahan Iklim & Kualitas Udara --}}
                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    3. Informasi Perubahan Iklim dan Kualitas Udara</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 font-medium text-slate-700 dark:text-slate-300">
                                                    a.
                                                    Informasi Perubahan Iklim</td>
                                                <td colspan="2"></td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-20 text-slate-700 dark:text-slate-300 font-medium">
                                                    1.
                                                    Publikasi Berupa Informasi Perubahan Iklim dan Kualitas Udara</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    buku</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    100.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-20 text-slate-700 dark:text-slate-300 font-medium">
                                                    2. Atlas</td>
                                                <td colspan="2"></td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-24 text-slate-700 dark:text-slate-300 font-medium">
                                                    a.
                                                    Kerentanan Perubahan Iklim</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    atlas</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    450.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-24 text-slate-700 dark:text-slate-300 font-medium">
                                                    b.
                                                    Potensi Energi Matahari di Indonesia</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    atlas</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    300.000,00</td>
                                            </tr>
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-24 text-slate-700 dark:text-slate-300 font-medium">
                                                    c.
                                                    Potensi Energi Angin di Indonesia</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    atlas</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    300.000,00</td>
                                            </tr>

                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 font-medium text-slate-700 dark:text-slate-300">
                                                    b.
                                                    Pengambilan Sampel Kualitas Udara</td>
                                                <td colspan="2"></td>
                                            </tr>
                                            @php
                                                $sampel = [['1', 'Sulfur Dioksida (SO2)', 'per sampel', '30.000'], ['2', 'Nitrogen Oksida (NO2)', 'per sampel', '30.000'], ['3', 'Karbon Dioksida (CO2)', 'per sampel', '40.000'], ['4', 'Ozon (O3)', 'per sampel', '30.000'], ['5', 'Suspended Particulate Matter (SPM)', 'per sampel', '60.000'], ['6', 'Debu Particulate Matter (PM10)', 'per sampel', '60.000'], ['7', 'Debu Particulate Matter (PM2.5)', 'per sampel', '90.000'], ['8', 'Kimia Air Hujan', 'per sampel', '230.000'], ['9', 'Methan (CH4)', 'per sampel', '40.000']];
                                            @endphp
                                            @foreach($sampel as $item)
                                                <tr
                                                    class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                    <td class="px-8 py-2 pl-20 text-slate-700 dark:text-slate-300 font-medium">
                                                        {{ $item[0] }}. {{ $item[1] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-2 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                        {{ $item[2] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-2 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                        Rp
                                                        {{ $item[3] }},00
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 font-medium text-slate-700 dark:text-slate-300">
                                                    c.
                                                    Pengujian Sampel Kualitas Udara</td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per
                                                    atlas</td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    470.000,00</td>
                                            </tr>
                                            @php
                                                $uji = [['1', 'Sulfur Dioksida (SO2)', 'per sampel', '20.000'], ['2', 'Nitrogen Oksida (NO2)', 'per sampel', '20.000'], ['3', 'Karbon Dioksida (CO2)', 'per sampel', '30.000'], ['4', 'Ozon (O3)', 'per sampel', '20.000'], ['5', 'Suspended Particulate Matter (SPM)', 'per sampel', '50.000'], ['6', 'Debu Particulate Matter (PM10)', 'per sampel', '50.000'], ['7', 'Debu Particulate Matter (PM2.5)', 'per sampel', '70.000'], ['8', 'Kimia Air Hujan', 'per sampel', '240.000'], ['9', 'Methan (CH4)', 'per sampel', '30.000']];
                                            @endphp
                                            @foreach($uji as $item)
                                                <tr
                                                    class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                    <td class="px-8 py-2 pl-20 text-slate-700 dark:text-slate-300 font-medium">
                                                        {{ $item[0] }}. {{ $item[1] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-2 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                        {{ $item[2] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-2 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                        Rp
                                                        {{ $item[3] }},00
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                                <td colspan="3"
                                                    class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                    4. Informasi Geofisika</td>
                                            </tr>
                                            @php
                                                $geo_req = [['a', 'Buku dan Peta Variasi Magnet Bumi (Epoch)', 'per buku', '300.000'], ['b', 'Peta Tingkat Kerawanan Petir', 'per lokasi per tahun', '200.000'], ['c', 'Waktu Terbit dan Terbenam Matahari atau Bulan', 'per lokasi per tahun', '50.000'], ['d', 'Buku Almanak Badan Meteorologi Klimatologi dan Geofisika', 'per buku per tahun', '150.000'], ['e', 'Buku Peta Ketinggian Hilal', 'per buku per tahun', '150.000'], ['f', 'Titik Dasar Gaya Berat (Gravitasi)', 'per titik dasar gaya berat', '150.000'], ['g', 'Kejadian Petir', 'per lokasi per hari', '75.000']];
                                            @endphp
                                            @foreach($geo_req as $item)
                                                <tr
                                                    class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                    <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                        {{ $item[0] }}. {{ $item[1] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                        {{ $item[2] }}
                                                    </td>
                                                    <td
                                                        class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                        Rp
                                                        {{ $item[3] }},00
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- 2. KONSULTASI --}}
                        <div id="tab-konsultasi" class="tab-panel hidden space-y-6">
                            <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> Jasa Konsultasi Meteorologi,
                                Klimatologi, dan Geofisika
                            </h3>
                            <div
                                class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th class="px-8 py-5 font-semibold">Jenis Layanan</th>
                                            <th class="px-8 py-5 font-semibold">Satuan</th>
                                            <th class="px-8 py-5 text-right font-semibold">Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        {{-- A. Meteorologi --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                A. Jasa Konsultasi Meteorologi
                                            </td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                Informasi Meteorologi Khusus untuk Pendukung Kegiatan Proyek, Survei, dan
                                                Penelitian Komersial
                                            </td>
                                            <td
                                                class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per lokasi</td>
                                            <td
                                                class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp 3.750.000,00</td>
                                        </tr>

                                        {{-- B. Klimatologi --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                B. Jasa Konsultasi Klimatologi
                                            </td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                Analisis Iklim
                                            </td>
                                            <td
                                                class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per lokasi</td>
                                            <td
                                                class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp 9.500.000,00</td>
                                        </tr>

                                        {{-- C. Geofisika --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                C. Jasa Konsultasi Geofisika
                                            </td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                Informasi Pendahuluan di Bidang Geofisika sebagai Pendukung Kegiatan Proyek,
                                                Survei, dan Penelitian Komersial
                                            </td>
                                            <td
                                                class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per lokasi</td>
                                            <td
                                                class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp 12.300.000,00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 3. KALIBRASI --}}
                        <div id="tab-kalibrasi" class="tab-panel hidden space-y-6">
                            <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span> Jasa Kalibrasi Alat Meteorologi,
                                Klimatologi, dan Geofisika
                            </h3>
                            <div
                                class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th class="px-8 py-5">Jenis Penerimaan Negara Bukan Pajak</th>
                                            <th class="px-8 py-5">Satuan</th>
                                            <th class="px-8 py-5 text-right">Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        {{-- A. Peralatan Sederhana Mekanik --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                A. Peralatan Sederhana Mekanik (Konvensional)</td>
                                        </tr>
                                        @php
                                            $mekanik = [
                                                ['1', 'Barometer Aneroid', '400.000'],
                                                ['2', 'Barometer Air Raksa', '400.000'],
                                                ['3', 'Barograph', '400.000'],
                                                ['4', 'Thermometer Bola Basah', '285.000'],
                                                ['5', 'Thermometer Bola Kering', '285.000'],
                                                ['6', 'Thermometer Maksimum', '285.000'],
                                                ['7', 'Thermometer Minimum', '285.000'],
                                                ['8', 'Thermometer Tanah', '280.000'],
                                                ['9', 'Thermometer Apung', '285.000'],
                                                ['10', 'Thermometer Rumput', '285.000'],
                                                ['11', 'Thermometer Minimum Rumput', '285.000'],
                                                ['12', 'Thermohygraph (2 sensor)', '735.000'],
                                                ['13', 'Portable Weather Station (PWS) (5 sensor)', '2.570.000'],
                                                ['14', 'Hygrometer (Kelembaban Udara)', '450.000'],
                                                ['15', 'Campbell Stokes', '205.000'],
                                                ['16', 'Panci Penguapan', '150.000'],
                                                ['17', 'Alat Penguapan Lengkap', '2.020.000'],
                                                ['18', 'Cup Counter Anemometer', '1.150.000'],
                                                ['19', 'Psycrometer Assman (2 sensor)', '570.000'],
                                                ['20', 'Actinograph', '205.000'],
                                                ['21', 'Penakar Hujan Obs', '210.000'],
                                                ['22', 'Penakar Hujan Hellman', '265.000'],
                                                ['23', 'Penakar Tipping Bucket', '270.000'],
                                                ['24', 'Still Well', '150.000'],
                                                ['25', 'Theodolite', '200.000'],
                                                ['26', 'Thermohygrometer (2 sensor)', '735.000'],
                                                ['27', 'Pyranometer', '400.000'],
                                            ];
                                        @endphp
                                        @foreach($mekanik as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per unit
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[2] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- B. Peralatan Sederhana Elektronik --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                B. Peralatan Sederhana Elektronik (Otomatis)</td>
                                        </tr>
                                        @php
                                            $elektronik = [
                                                ['1', 'Anemometer (2 sensor)', '1.235.000'],
                                                ['2', 'Digital Hand Anemometer (1 sensor)', '1.150.000'],
                                                ['3', 'Digital Hand Anemometer (2 sensor)', '1.235.000'],
                                                ['4', 'Digital Barometer', '400.000'],
                                            ];
                                        @endphp
                                        @foreach($elektronik as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per unit
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[2] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- C. Peralatan Teknologi Canggih --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                C. Peralatan Teknologi Canggih (Modern)</td>
                                        </tr>
                                        @php
                                            $modern = [
                                                ['1', 'Automatic Weather Station (AWS) (5 sensor)', '2.240.000'],
                                                ['2', 'Automatic Weather Station (AWS) (6 sensor)', '2.640.000'],
                                                ['3', 'Automatic Weather Station (AWS) (7 sensor)', '3.040.000'],
                                                ['4', 'Automatic Weather Station (AWS) (11 sensor)', '4.775.000'],
                                                ['5', 'Marine Automatic Weather Station (MAWS) (9 sensor)', '3.475.000'],
                                                ['6', 'Automatic Weather Observation System (AWOS) (9 sensor)', '4.790.000'],
                                                ['7', 'Agroclimate Automatic Weather System (AAWS) (11 sensor)', '4.360.000'],
                                                ['8', 'Agroclimate Automatic Weather System (AAWS) (32 sensor)', '6.600.000'],
                                                ['9', 'Sensor Runway Visual Range (RVR)', '800.000'],
                                                ['10', 'Ceillometer (AWOS) (9 sensor)', '950.000'],
                                            ];
                                        @endphp
                                        @foreach($modern as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per unit
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[2] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- C. Alat Meteorologi (Repeated C per image) --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                C. Alat Meteorologi</td>
                                        </tr>
                                        @php
                                            $meteorologi_std = [
                                                ['1', 'Barometer Standar', '1.180.000'],
                                                ['2', 'Thermometer Standar', '920.000'],
                                                ['3', 'Hygrometer Standar', '2.010.000'],
                                                ['4', 'Anemometer Standar', '1.650.000'],
                                                ['5', 'Anemometer Standar (2 sensor)', '2.310.000'],
                                                ['6', 'Gelas Penakar Hujan Standar', '810.000'],
                                                ['7', 'Pyranometer Standar', '990.000'],
                                            ];
                                        @endphp
                                        @foreach($meteorologi_std as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per unit
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[2] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- D. Alat Kualitas Udara --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                D. Alat Kualitas Udara</td>
                                        </tr>
                                        @php
                                            $udara_std = [
                                                ['1', 'pH Meter', '50.000'],
                                                ['2', 'Conductivity Meter', '50.000'],
                                                ['3', 'Timbangan Analitik', '100.000'],
                                                ['4', 'Ion Chromatograph (IC)', '750.000'],
                                                ['5', 'Atomic Absorption Spectrophotometer (AAS)', '500.000'],
                                                ['6', 'High Volume Air Sampler (HVAS)', '400.000'],
                                                ['7', 'Rainfall Water Sampler (RWS)', '50.000'],
                                                ['8', 'Aerosol Sampler', '50.000'],
                                                ['9', 'Pemantau Karbon Dioksida (CO2 Monitoring)', '250.000'],
                                                ['10', 'Pemantau Sulfur Dioksida (SO2 Monitoring)', '250.000'],
                                                ['11', 'Pemantau Nitrogen Dioksida (NO2 Monitoring)', '250.000'],
                                                ['12', 'Ozon Analyzer', '250.000'],
                                                ['13', 'Betha Attenuator Monitoring (BAM)', '250.000'],
                                                ['14', 'Gelas Ukur', '50.000'],
                                                ['15', 'Spectrophotometer', '200.000'],
                                            ];
                                        @endphp
                                        @foreach($udara_std as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per unit
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[2] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- F. Alat Geofisika --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                F. Alat Geofisika</td>
                                        </tr>
                                        @php
                                            $geofisika_std = [
                                                ['1', 'Portable Analog Seismograph', '1.500.000'],
                                                ['2', 'Short Period Seismograph (SPS-1)', '1.500.000'],
                                                ['3', 'Short Period Seismograph (SPS-3)', '1.500.000'],
                                                ['4', 'Portable Digital Seismograph (3 Komponen)', '1.750.000'],
                                                ['5', 'Digital Broadband Seismograph (3 Komponen)', '1.750.000'],
                                                ['6', 'Digital Accelerograph (3 Komponen)', '1.750.000'],
                                                ['7', 'Gravimeter', '4.450.000'],
                                                ['8', 'Terrameter SAS 1000', '280.000'],
                                            ];
                                        @endphp
                                        @foreach($geofisika_std as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per unit
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[2] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- G. Alat Ukur Kelistrikan --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                G. Alat Ukur Kelistrikan Meteorologi, Klimatologi, dan Geofisika</td>
                                        </tr>
                                        @php
                                            $listrik = [
                                                ['1', 'Multi Meter', '260.000'],
                                                ['2', 'Clamp Meter', '260.000'],
                                                ['3', 'Grounding Tester', '300.000'],
                                                ['4', 'Oscilloscope', '1.000.000'],
                                                ['5', 'Frequency Counter', '1.000.000'],
                                                ['6', 'Function Generator', '1.900.000'],
                                            ];
                                        @endphp
                                        @foreach($listrik as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    per unit
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[2] }},00
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 4. SEWA ALAT --}}
                        <div id="tab-sewa-alat" class="tab-panel hidden space-y-6">
                            <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> Jasa Penggunaan Alat Meteorologi,
                                Klimatologi, dan Geofisika
                            </h3>
                            <div
                                class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th class="px-8 py-5">Jenis Penerimaan Negara Bukan Pajak</th>
                                            <th class="px-8 py-5">Satuan</th>
                                            <th class="px-8 py-5 text-right">Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        {{-- A. Peralatan Sederhana Mekanik --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                A. Peralatan Sederhana Mekanik (Konvensional)</td>
                                        </tr>
                                        @php
                                            $mekanik_sewa = [
                                                ['1', 'Barometer Aneroid', 'per minggu', '60.000'],
                                                ['2', 'Barometer Air Raksa', 'per minggu', '60.000'],
                                                ['3', 'Barograph', 'per minggu', '70.000'],
                                                ['4', 'Thermometer Tanah', 'per minggu', '55.000'],
                                                ['5', 'Thermohygraph', 'per minggu', '55.000'],
                                                ['6', 'Portable Weather Station (PWS)', 'per minggu', '150.000'],
                                                ['7', 'Campbell Stokes', 'per minggu', '70.000'],
                                                ['8', 'Cup Counter Anemometer', 'per minggu', '35.000'],
                                                ['9', 'Psychrometer Assman', 'per minggu', '45.000'],
                                                ['10', 'Actinograph', 'per minggu', '45.000'],
                                            ];
                                        @endphp
                                        @foreach($mekanik_sewa as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    {{ $item[2] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[3] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- B. Peralatan Sederhana Elektronik --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                B. Peralatan Sederhana Elektronik (Otomatis)</td>
                                        </tr>
                                        @php
                                            $elektronik_sewa = [
                                                ['1', 'Anemometer', 'per minggu', '190.000'],
                                                ['2', 'Digital Hand Anemometer', 'per minggu', '90.000'],
                                                ['3', 'Digital Barometer', 'per minggu', '160.000'],
                                                ['4', 'Teropong Rukyat (Low Grade)', 'per hari per unit', '230.000'],
                                            ];
                                        @endphp
                                        @foreach($elektronik_sewa as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    {{ $item[2] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[3] }},00
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- C. Peralatan Teknologi Canggih --}}
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td colspan="3"
                                                class="px-8 py-3 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                                C. Peralatan Teknologi Canggih (Modern)</td>
                                        </tr>
                                        @php
                                            $modern_sewa = [
                                                ['1', 'Portable Automatic Weather Station (PAWS)', 'per minggu', '700.000'],
                                                ['2', 'Portable Marine Automatic Weather Station (PMAWS)', 'per minggu', '700.000'],
                                                ['3', 'Thermal Imager', 'per hari', '150.000'],
                                                ['4', 'System Grounding Tester', 'per hari', '200.000'],
                                                ['5', 'Proton Magnetograph', 'per hari per unit', '400.000'],
                                                ['6', 'Portable Digital Short Period Seismograph', 'per hari per unit', '640.000'],
                                                ['7', 'Portable Digital Broadband Seismograph', 'per hari per unit', '970.000'],
                                                ['8', 'Portable Digital Broadband Accelerograph', 'per hari per unit', '735.000'],
                                                ['9', 'Mikrotremor Array', 'per hari per unit', '4.000.000'],
                                                ['10', 'Mikrotremor Civil Engineering', 'per hari per unit', '680.000'],
                                                ['11', 'Multichannel Analysis of Surface Wave (MASW)', 'per hari per unit', '1.750.000'],
                                                ['12', 'Gravimeter', 'per hari per unit', '600.000'],
                                                ['13', 'GPS Geodesi', 'per hari per unit', '270.000'],
                                                ['14', 'Deklinasi dan Inklinasi Magnetometer', 'per hari per unit', '400.000'],
                                                ['15', 'Portable Digital Modular Area Direct Monitoring Automatic (Gray Wolf)', 'per hari', '250.000'],
                                                ['16', 'Pengukur Partikulat Portable', 'per hari', '83.000'],
                                                ['17', 'Magnetotellurik 5 CH', 'per hari', '4.000.000'],
                                                ['18', 'Computing Server of Climate Change', 'per core per bulan', '300.000'],
                                                ['19', 'Teropong Rukyat High Grade', 'per hari per unit', '400.000'],
                                            ];
                                        @endphp
                                        @foreach($modern_sewa as $item)
                                            <tr
                                                class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                                <td class="px-8 py-3 pl-12 text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $item[0] }}.
                                                    {{ $item[1] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                    {{ $item[2] }}
                                                </td>
                                                <td
                                                    class="px-8 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                    Rp
                                                    {{ $item[3] }},00
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 5. STMKG --}}
                        <div id="tab-stmkg" class="tab-panel hidden space-y-6">
                            <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> Jasa Penyelenggaraan Sekolah Tinggi
                                Meteorologi Klimatologi dan Geofisika
                            </h3>
                            <div
                                class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th class="px-8 py-5">Jenis Penerimaan Negara Bukan Pajak</th>
                                            <th class="px-8 py-5">Satuan</th>
                                            <th class="px-8 py-5 text-right">Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">
                                                A. Uang Pendaftaran dan Seleksi Masuk Sekolah Tinggi Meteorologi Klimatologi
                                                dan Geofisika
                                            </td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per orang
                                            </td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp 75.000,00</td>
                                        </tr>
                                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">
                                                B. Sumbangan Pembinaan Pendidikan Tetap Sekolah Tinggi Meteorologi
                                                Klimatologi dan Geofisika dari Instansi lain
                                            </td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per orang
                                                per semester</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp 4.500.000,00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 6. DIKLAT --}}
                        <div id="tab-diklat" class="tab-panel hidden space-y-6">
                            <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> Jasa Penyelenggaraan Pendidikan
                                dan
                                Pelatihan Meteorologi, Klimatologi, dan Geofisika
                            </h3>
                            <div
                                class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th class="px-8 py-5">Jenis Penerimaan Negara Bukan Pajak</th>
                                            <th class="px-8 py-5">Satuan</th>
                                            <th class="px-8 py-5 text-right">Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">
                                                A. Pendidikan dan Pelatihan Teknis/Fungsional/Sertifikasi Meteorologi,
                                                Klimatologi, dan Geofisika Nonpegawai Badan Meteorologi, Klimatologi, dan
                                                Geofisika (10 hari dan minimal 30 orang)
                                            </td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per orang</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp 5.500.000,00</td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">
                                                B. Modul Pendidikan dan Pelatihan Bidang Meteorologi, Klimatologi, atau
                                                Geofisika
                                            </td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per buku</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp 100.000,00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 7. SEWA GEDUNG --}}
                        <div id="tab-gedung" class="tab-panel hidden space-y-6">
                            <h3 class="text-lg font-bold flex items-center gap-2 text-gray-900 dark:text-white">
                                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> Jasa Penggunaan Gedung Untuk
                                Kegiatan
                                Pendidikan dan Pelatihan/Workshop/Seminar di Bidang Meteorologi, Klimatologi, dan Geofisika
                            </h3>
                            <div
                                class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="bg-slate-50/80 dark:bg-slate-900/50 backdrop-blur-sm text-slate-500 uppercase text-[11px] font-bold tracking-widest border-b border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th class="px-8 py-5">Jenis Penerimaan Negara Bukan Pajak</th>
                                            <th class="px-8 py-5">Satuan</th>
                                            <th class="px-8 py-5 text-right">Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        {{-- A. Ruang Aula --}}
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">A. Ruang
                                                Aula</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per 8 jam</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                1.500.000,00</td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td
                                                class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium pl-12 italic text-sm">
                                                Tambahan
                                                Penggunaan Ruang Aula</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per jam</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                200.000,00</td>
                                        </tr>

                                        {{-- B. Ruang Sinema --}}
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">B. Ruang
                                                Sinema</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per 8 jam</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                1.500.000,00</td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td
                                                class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium pl-12 italic text-sm">
                                                Tambahan
                                                Penggunaan Ruang Sinema</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per jam</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                200.000,00</td>
                                        </tr>

                                        {{-- C. Ruang Kelas --}}
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">C. Ruang
                                                Kelas</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per 8 jam per
                                                ruang</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                400.000,00</td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td
                                                class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium pl-12 italic text-sm">
                                                Tambahan
                                                Penggunaan Ruang Kelas</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per jam per
                                                ruang</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                50.000,00</td>
                                        </tr>

                                        {{-- D. Ruang Komputer --}}
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">D. Ruang
                                                Komputer</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per 8 jam</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                400.000,00</td>
                                        </tr>
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td
                                                class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium pl-12 italic text-sm">
                                                Tambahan
                                                Penggunaan Ruang Komputer</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per jam</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                50.000,00</td>
                                        </tr>

                                        {{-- E. Kamar Asrama --}}
                                        <tr
                                            class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                            <td class="px-8 py-4 text-slate-700 dark:text-slate-300 font-medium">E. Kamar
                                                Asrama</td>
                                            <td
                                                class="px-8 py-4 italic text-[11px] text-slate-400 font-medium uppercase tracking-tighter">
                                                per orang per
                                                hari</td>
                                            <td
                                                class="px-8 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                                Rp
                                                225.000,00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ===== CTA ===== --}}
                <div class="mt-10 info-card opacity-0 translate-y-6 transition-all duration-700 bg-gradient-to-br from-indigo-700 via-purple-700 to-blue-800 rounded-2xl p-8 text-center text-white shadow-xl relative overflow-hidden"
                    data-delay="300">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative z-10">
                        <i class="fas fa-file-invoice-dollar text-3xl text-indigo-200 mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3">Butuh Informasi Lebih Lengkap?</h3>
                        <p class="text-white/80 mb-6 max-w-xl mx-auto text-sm">
                            Terdapat banyak layanan spesifik lainnya sesuai dengan Lampiran PP 47/2018. Silakan hubungi
                            petugas kami untuk informasi detail lebih lanjut.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('pembayaran-pnbp') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-indigo-700 font-semibold rounded-full hover:bg-indigo-50 transition duration-200 shadow-lg">
                                <i class="fas fa-credit-card"></i> Cara Pembayaran
                            </a>
                            <a href="https://wa.me/6289612643202" target="_blank"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/40 text-white font-semibold rounded-full hover:bg-white/30 transition duration-200">
                                <i class="fab fa-whatsapp"></i> Chat Petugas
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
                panel.classList.remove('block');
            });

            // Reset all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove(
                    'active', 'border-blue-600', 'border-amber-500', 'border-emerald-500',
                    'border-cyan-500', 'border-indigo-600', 'border-orange-500', 'border-rose-500',
                    'text-blue-700', 'text-amber-700', 'text-emerald-700',
                    'text-cyan-700', 'text-indigo-700', 'text-orange-700', 'text-rose-700',
                    'dark:text-blue-400', 'dark:text-amber-400', 'dark:text-emerald-400',
                    'dark:text-cyan-400', 'dark:text-indigo-400', 'dark:text-orange-400', 'dark:text-rose-400',
                    'bg-blue-50/60', 'bg-amber-50/60', 'bg-emerald-50/60',
                    'bg-cyan-50/60', 'bg-indigo-50/60', 'bg-orange-50/60', 'bg-rose-50/60',
                    'dark:bg-blue-900/20', 'dark:bg-amber-900/20', 'dark:bg-emerald-900/20',
                    'dark:bg-cyan-900/20', 'dark:bg-indigo-900/20', 'dark:bg-orange-900/20', 'dark:bg-rose-900/20'
                );
                btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });

            // Show target panel
            const targetPanel = document.getElementById('tab-' + tab);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
                targetPanel.classList.add('block');
            }

            // Style active button
            const activeBtn = document.getElementById('tab-btn-' + tab);
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                activeBtn.classList.add(
                    'active', 'border-blue-600', 'text-blue-700', 'dark:text-blue-400',
                    'bg-blue-50/60', 'dark:bg-blue-900/20'
                );
            }

            // Smooth scroll to container with offset
            const container = document.querySelector('.info-card.bg-white.dark\\:bg-slate-800.rounded-2xl.shadow-xl');
            if (container) {
                const navHeight = 90;
                const containerPosition = container.getBoundingClientRect().top + window.pageYOffset - navHeight;
                window.scrollTo({
                    top: containerPosition,
                    behavior: 'smooth'
                });
            }
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