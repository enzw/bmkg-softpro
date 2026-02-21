@props(['pendingRating'])

@if($pendingRating)
    <div x-data="{ 
            open: true, 
            rating: 0, 
            hoverRating: 0, 
            review: '',
            isLoading: false,
            submitRating() {
                if (this.rating === 0) return;
                this.isLoading = true;
                fetch('/api/rating', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        rateable_id: '{{ $pendingRating['id'] }}',
                        rateable_type: '{{ str_replace('\\', '\\\\', $pendingRating['type']) }}',
                        rating: this.rating,
                        review: this.review
                    })
                })
                .then(response => {
                    if (response.ok) {
                        this.open = false;
                        window.location.reload(); // Reload to update UI or fetch next rating
                    } else {
                        alert('Gagal mengirim rating. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan.');
                })
                .finally(() => {
                    this.isLoading = false;
                });
            }
        }" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
        style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4 transform transition-all"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendapat Anda penting bagi kami!</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Bagaimana kualitas pelayanan <span
                        class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $pendingRating['jenis'] }}</span>
                    kami?</p>

                <!-- Stars -->
                <div class="flex justify-center space-x-2 mb-6">
                    <template x-for="i in 5">
                        <button @click="rating = i" @mouseenter="hoverRating = i" @mouseleave="hoverRating = 0"
                            class="focus:outline-none transform transition-transform duration-100 hover:scale-110">
                            <svg class="w-10 h-10"
                                :class="(hoverRating >= i || rating >= i) ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600 fill-current'"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="0">
                                <!-- Added fill-current and stroke-width-0 for solid stars -->
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </button>
                    </template>
                </div>

                <!-- Review Textarea -->
                <div class="mb-6">
                    <textarea x-model="review"
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 transition-colors resize-none"
                        rows="3" placeholder="Tulis pesan Anda, jika Anda ingin..."></textarea>
                </div>

                <!-- Buttons -->
                <button @click="submitRating" :disabled="rating === 0 || isLoading"
                    :class="{'opacity-50 cursor-not-allowed': rating === 0 || isLoading}"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg shadow-indigo-500/30 mb-4">
                    <span x-show="!isLoading">Beri Rating Sekarang</span>
                    <span x-show="isLoading">Mengirim...</span>
                </button>

                <button @click="open = false"
                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-medium text-sm transition-colors">
                    Mungkin Nanti
                </button>
            </div>
        </div>
    </div>
@endif