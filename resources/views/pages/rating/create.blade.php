@extends('layouts.main')

@section('content')
    <x-guest-layout :wide="true">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Berikan saran atau penilaian pelayanan</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Bantu kami meningkatkan kualitas layanan dengan memberikan penilaian Anda</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200 flex items-start gap-3">
                    <i class="fas fa-check-circle flex-shrink-0 mt-0.5"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-800 dark:text-red-200 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Rating Form -->
            <form action="{{ route('rating.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Service Type Selection -->
                <div>
                    <label for="service_type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-concierge-bell text-gray-500 dark:text-gray-400 mr-2"></i>Jenis Layanan Yang Dinilai
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
                                    class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 cursor-pointer transition-all peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-300">
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
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-star text-gray-500 dark:text-gray-400 mr-2"></i>Berikan Penilaian
                    </label>

                    <div class="rating-group flex flex-row-reverse items-center gap-2 justify-center py-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                            class="peer hidden" {{ old('rating') == $i ? 'checked' : '' }}>
                        <label for="star{{ $i }}" class="cursor-pointer text-gray-300 dark:text-gray-600 hover:text-amber-400 peer-checked:text-amber-400 peer-hover:text-amber-400 transition-all duration-200">
                            <i class="fas fa-star text-4xl md:text-5xl"></i>
                        </label>
                    @endfor
                </div>

                <style>
                    /* Highlight all stars after the checked/hovered star in the DOM */
                    /* Because of flex-row-reverse, these are the stars to the left */
                    .rating-group label:hover ~ label,
                    .rating-group input:checked ~ label {
                        color: #fbbf24; /* amber-400 */
                    }
                </style>

                    @error('rating')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Review Text -->
                <div>
                    <label for="review" class="block text-sm font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-comment-dots text-gray-500 dark:text-gray-400 mr-2"></i>Saran atau Masukan (Opsional)
                    </label>

                    <textarea name="review" id="review" rows="5"
                        placeholder="Bagikan pengalaman Anda menggunakan layanan kami... (Maksimal 1000 karakter)"
                        class="w-full px-5 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none"
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
            <div class="pt-4">
                <button type="submit"
                    class="w-full px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Saran/Penilaian
                </button>
            </div>

            </form>

            <!-- Info Box -->
            <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl">
                <div class="flex gap-4">
                    <i class="fas fa-info-circle text-xl text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5"></i>

                    <div class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                        <p class="font-bold">Informasi Saran / Penilaian:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs opacity-90">
                            <li>Saran Anda membantu kami memperbaiki kualitas layanan</li>
                            <li>Setiap masukan sangat berharga untuk pengembangan kami</li>
                            <li>Data penilaian akan ditampilkan di halaman dashboard admin</li>
                            <li>Anda dapat memberikan saran untuk setiap jenis layanan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-guest-layout>

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