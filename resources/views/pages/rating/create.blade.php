@extends('layouts.main')

@section('content')
    <div class="w-full min-h-screen bg-white dark:bg-gray-900 px-6 pt-40 pb-20">

        <div class="max-w-2xl mx-auto">

            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    Berikan Rating
                </h1>

                <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 leading-relaxed font-medium">
                    Bantu kami meningkatkan kualitas layanan dengan memberikan penilaian Anda
                </p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl">
                    <p class="text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl">
                    <p class="text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Rating Form -->
            <div class="bg-white dark:bg-gray-800 p-8 md:p-12 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">

                <form action="{{ route('rating.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Service Type Selection -->
                    <div>
                        <label for="service_type" class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-4">
                            Jenis Layanan Yang Dinilai
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @php
                                $services = [
                                    'Umum' => ['icon' => 'fa-star', 'color' => 'text-gray-600 dark:text-gray-400'],
                                    'Magang' => ['icon' => 'fa-graduation-cap', 'color' => 'text-blue-600 dark:text-blue-400'],
                                    'Kunjungan' => ['icon' => 'fa-building-user', 'color' => 'text-amber-600 dark:text-amber-400'],
                                    'Konsultasi' => ['icon' => 'fa-comments', 'color' => 'text-purple-600 dark:text-purple-400'],
                                    'Asuransi' => ['icon' => 'fa-file-invoice-dollar', 'color' => 'text-orange-600 dark:text-orange-400'],
                                    'Survey' => ['icon' => 'fa-compass', 'color' => 'text-red-600 dark:text-red-400'],
                                    'Layanan Data' => ['icon' => 'fa-database', 'color' => 'text-cyan-600 dark:text-cyan-400'],
                                    'Sewa Alat' => ['icon' => 'fa-tools', 'color' => 'text-green-600 dark:text-green-400'],
                                ];
                            @endphp

                            @foreach ($services as $service => $config)
                                <label class="relative">
                                    <input type="radio" name="service_type" value="{{ $service }}"
                                        class="peer sr-only" required
                                        {{ old('service_type') == $service ? 'checked' : '' }}>

                                    <div
                                        class="p-4 rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 cursor-pointer transition-all peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-300">
                                        <div class="flex items-center gap-3">
                                            <i class="fas {{ $config['icon'] }} text-lg {{ $config['color'] }}"></i>
                                            <span
                                                class="font-semibold text-gray-900 dark:text-white text-sm">{{ $service }}</span>
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
                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6">
                            Berikan Rating
                        </label>

                        <div class="flex items-center gap-4 justify-center py-8">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="rating" value="{{ $i }}"
                                        class="sr-only peer" {{ old('rating') == $i ? 'checked' : '' }}>

                                    <i
                                        class="fas fa-star text-5xl transition-all peer-checked:text-amber-400 peer-hover:text-amber-300 text-gray-300 dark:text-gray-700 group-hover:text-amber-300"></i>
                                </label>
                            @endfor
                        </div>

                        @error('rating')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Review Text -->
                    <div>
                        <label for="review" class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-4">
                            Review (Opsional)
                        </label>

                        <textarea name="review" id="review" rows="6"
                            placeholder="Bagikan pengalaman Anda menggunakan layanan kami... (Maksimal 1000 karakter)"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none"
                            maxlength="1000">{{ old('review') }}</textarea>

                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-400">Bagikan detail pengalaman Anda</p>
                            <p class="text-xs text-gray-400"><span id="char-count">{{ strlen(old('review') ?? '') }}</span>/1000</p>
                        </div>

                        @error('review')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4 pt-8">
                        <button type="submit"
                            class="flex-1 px-8 py-4 bg-green-600 dark:bg-green-700 text-white font-bold uppercase tracking-widest rounded-2xl hover:bg-green-700 dark:hover:bg-green-800 transition-all shadow-sm hover:shadow-md active:scale-95">
                            <i class="fas fa-check mr-2"></i>
                            Kirim Rating
                        </button>

                        <a href="/"
                            class="flex-1 px-8 py-4 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-bold uppercase tracking-widest rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all shadow-sm border border-gray-200 dark:border-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </a>
                    </div>

                </form>

            </div>

            <!-- Info Box -->
            <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl">
                <div class="flex gap-4">
                    <i class="fas fa-info-circle text-2xl text-blue-600 dark:text-blue-400 flex-shrink-0 mt-1"></i>

                    <div class="text-sm text-blue-700 dark:text-blue-300 space-y-2">
                        <p class="font-semibold">Informasi Rating:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Rating Anda membantu kami memperbaiki kualitas layanan</li>
                            <li>Setiap masukan sangat berharga untuk pengembangan kami</li>
                            <li>Data rating akan ditampilkan di halaman dashboard admin</li>
                            <li>Anda dapat memberikan rating untuk setiap jenis layanan</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Update character count
        const reviewTextarea = document.getElementById('review');
        const charCount = document.getElementById('char-count');

        if (reviewTextarea) {
            reviewTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }
    </script>
@endsection
