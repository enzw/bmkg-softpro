@extends('layouts.main')

@section('title', 'Berikan Penilaian & Saran')

@section('content')
    <div class="dark:bg-slate-900 dark:text-white pb-20">

        {{-- ===== HERO SECTION ===== --}}
        <header class="relative pt-[70px] overflow-hidden bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-40 -mt-40"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-40 -mb-20"></div>
            </div>

            <div class="container px-4 mx-auto py-16 relative z-10 text-center">
                <span
                    class="inline-block px-5 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 rounded-full text-xs font-semibold mb-6 tracking-wide uppercase">
                    <i class="fas fa-comment-dots mr-2 text-emerald-400"></i>Saran & Penilaian
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                    Berikan <span class="text-emerald-400">Penilaian</span>
                </h1>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mb-6">Stasiun Geofisika Kelas I
                    Sleman</p>
                <p class="text-base text-white/60 max-w-xl mx-auto leading-relaxed font-medium">
                    Bantu kami meningkatkan kualitas layanan dengan memberikan saran dan penilaian jujur Anda.
                </p>
            </div>

        </header>

        {{-- ===== MAIN CONTENT ===== --}}
        <section class="relative -mt-8 z-20">
            <div class="container px-4 mx-auto max-w-4xl">

                {{-- Messages --}}
                @if (session('success'))
                    <div
                        class="info-card mb-8 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <p class="text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="info-card mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <p class="text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <div
                    class="info-card bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden max-w-3xl mx-auto">
                    <div class="h-1.5 bg-gradient-to-r from-green-500 via-emerald-500 to-green-600"></div>
                    <div class="p-8 md:p-10">

                        <form action="{{ route('rating.store') }}" method="POST" class="space-y-10">
                            @csrf

                            <!-- Service Type -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/40 flex items-center justify-center text-green-600 dark:text-green-400">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Jenis Layanan Yang Dinilai
                                    </h3>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                    @php
                                        $services = [
                                            'Umum' => ['icon' => 'fa-star', 'color' => 'blue'],
                                            'Magang' => ['icon' => 'fa-graduation-cap', 'color' => 'blue'],
                                            'Kunjungan' => ['icon' => 'fa-building-user', 'color' => 'amber'],
                                            'Konsultasi' => ['icon' => 'fa-comments', 'color' => 'purple'],
                                            'Asuransi' => ['icon' => 'fa-file-invoice-dollar', 'color' => 'orange'],
                                            'Survey' => ['icon' => 'fa-compass', 'color' => 'red'],
                                            'Layanan Data' => ['icon' => 'fa-database', 'color' => 'cyan'],
                                            'Sewa Alat' => ['icon' => 'fa-tools', 'color' => 'green'],
                                        ];
                                    @endphp

                                    @foreach ($services as $service => $config)
                                        <label class="relative group cursor-pointer">
                                            <input type="radio" name="service_type" value="{{ $service }}" class="peer sr-only"
                                                required {{ old('service_type') == $service ? 'checked' : '' }}>

                                            <div
                                                class="p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 transition-all group-hover:border-green-300 dark:group-hover:border-green-700 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 peer-checked:shadow-inner">
                                                <div class="flex flex-col items-center gap-3 text-center">
                                                    <i
                                                        class="fas {{ $config['icon'] }} text-xl text-slate-400 peer-checked:text-green-500 transition-colors"></i>
                                                    <span
                                                        class="font-bold text-gray-900 dark:text-white text-xs tracking-tight">{{ $service }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('service_type')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Star Rating -->
                            <div class="space-y-8 pt-8 border-t border-gray-100 dark:border-slate-700">
                                <div class="text-center">
                                    <h3
                                        class="text-2xl font-black text-gray-900 dark:text-white mb-2 uppercase tracking-tighter">
                                        Seberapa Puas Anda?</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Klik pada bintang untuk memberikan
                                        nilai</p>
                                </div>

                                <div
                                    class="flex items-center gap-3 justify-center py-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="rating" value="{{ $i }}" class="rating-input sr-only peer"
                                                {{ old('rating') == $i ? 'checked' : '' }}>
                                            <i
                                                class="star-icon fas fa-star text-4xl sm:text-5xl transition-all transform group-hover:scale-110 active:scale-95 text-gray-200 dark:text-slate-800"></i>
                                            <span
                                                class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 text-center font-bold">{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Review -->
                            <div class="space-y-4 pt-8 border-t border-gray-100 dark:border-slate-700">
                                <label for="review"
                                    class="flex items-center gap-3 text-lg font-bold text-gray-900 dark:text-white">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center text-green-600 dark:text-green-400">
                                        <i class="fas fa-comment-alt text-sm"></i>
                                    </div>
                                    Review (Opsional)
                                </label>

                                <div class="relative group">
                                    <textarea name="review" id="review" rows="5" placeholder="Ceritakan pengalaman Anda..."
                                        class="w-full px-6 py-5 rounded-[1.5rem] border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all resize-none shadow-sm"
                                        maxlength="1000">{{ old('review') }}</textarea>
                                    <div class="absolute bottom-4 right-6 flex items-center gap-2">
                                        <span id="char-count"
                                            class="text-xs font-bold text-slate-400">{{ strlen(old('review') ?? '') }}</span>
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">/
                                            1000</span>
                                    </div>
                                </div>
                                @error('review')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="flex flex-col gap-4 pt-6">
                                <button type="submit"
                                    class="w-full px-10 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-black uppercase tracking-widest rounded-2xl hover:from-green-700 hover:to-green-800 transition-all shadow-xl hover:shadow-green-500/30 active:scale-[0.98] flex items-center justify-center gap-3 group">
                                    <i
                                        class="fas fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                                    Kirim Penilaian & Saran
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- FAQ/Info Box -->
                <div class="info-card mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="p-6 bg-green-50/50 dark:bg-green-900/10 rounded-3xl border border-green-100 dark:border-green-800/30">
                        <div class="flex gap-4">
                            <i class="fas fa-shield-alt text-xl text-green-500"></i>
                            <div>
                                <p class="font-bold text-sm text-gray-900 dark:text-white mb-1">Privasi Terjaga</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Penilaian Anda akan
                                    digunakan secara internal untuk evaluasi kualitas pelayanan kami.</p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="p-6 bg-emerald-50/50 dark:bg-emerald-900/10 rounded-3xl border border-emerald-100 dark:border-emerald-800/30">
                        <div class="flex gap-4">
                            <i class="fas fa-rocket text-xl text-emerald-500"></i>
                            <div>
                                <p class="font-bold text-sm text-gray-900 dark:text-white mb-1">Peningkatan Layanan</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Setiap bintang yang Anda
                                    berikan mendorong kami untuk terus berinovasi.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <script>
        // Character count
        const textarea = document.getElementById('review');
        const count = document.getElementById('char-count');
        if (textarea && count) {
            textarea.addEventListener('input', () => {
                count.textContent = textarea.value.length;
                if (textarea.value.length >= 900) {
                    count.classList.add('text-orange-500');
                } else {
                    count.classList.remove('text-orange-500');
                }
            });
        }

        // Star rating behavior
        const ratingInputs = document.querySelectorAll('.rating-input');
        const starIcons = document.querySelectorAll('.star-icon');

        function updateStars(rating) {
            starIcons.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-200', 'dark:text-slate-800');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.add('text-gray-200', 'dark:text-slate-800');
                    star.classList.remove('text-amber-400');
                }
            });
        }

        ratingInputs.forEach(input => {
            input.addEventListener('change', () => updateStars(input.value));
        });

        // Hover effect
        starIcons.forEach((star, index) => {
            const label = star.parentElement;
            label.addEventListener('mouseenter', () => {
                starIcons.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.add('text-amber-300');
                        s.classList.remove('text-gray-200', 'dark:text-slate-800');
                    }
                });
            });
            label.addEventListener('mouseleave', () => {
                starIcons.forEach((s, i) => s.classList.remove('text-amber-300'));
                const checked = document.querySelector('.rating-input:checked');
                updateStars(checked ? checked.value : 0);
            });
        });

        // Initial state
        const initialChecked = document.querySelector('.rating-input:checked');
        if (initialChecked) updateStars(initialChecked.value);

        // Intersection Observer for Animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.info-card').forEach(card => {
            card.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-1000');
            observer.observe(card);
        });
    </script>
@endsection