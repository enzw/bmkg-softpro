/**
 * Chatbot Rating Widget
 * Simple implementation for capturing chatbot ratings and submitting to the API
 */

class ChatbotRatingWidget {
    constructor() {
        this.sessionId = this.generateSessionId();
        this.container = null;
        this.rating = 0;
    }

    /**
     * Generate a unique session ID for the chatbot conversation
     */
    generateSessionId() {
        return `chatbot-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
    }

    /**
     * Create and insert the rating widget HTML
     */
    createWidget() {
        const html = `
            <div id="chatbot-rating-widget" class="fixed bottom-6 right-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 max-w-sm z-50">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Bagaimana layanan kami?</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Berikan rating untuk membantu kami meningkatkan layanan chatbot</p>
                </div>

                <!-- Star Rating -->
                <div id="rating-stars" class="flex gap-2 justify-center mb-4">
                    ${[1, 2, 3, 4, 5].map(i => `
                        <button 
                            type="button" 
                            data-rating="${i}"
                            class="rating-star text-2xl transition-transform hover:scale-110 cursor-pointer"
                            title="${i} bintang"
                        >
                            ⭐
                        </button>
                    `).join('')}
                </div>

                <!-- Review Text (Optional) -->
                <textarea 
                    id="rating-review"
                    placeholder="Saran atau komentar (opsional)..."
                    class="w-full text-xs p-2 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    rows="3"
                ></textarea>

                <!-- Buttons -->
                <div class="flex gap-2 mt-4">
                    <button 
                        id="cancel-rating"
                        type="button"
                        class="flex-1 px-3 py-2 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                    >
                        Tutup
                    </button>
                    <button 
                        id="submit-rating"
                        type="button"
                        class="flex-1 px-3 py-2 text-xs font-semibold bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Kirim
                    </button>
                </div>
            </div>
        `;

        this.container = document.createElement('div');
        this.container.innerHTML = html;
        document.body.appendChild(this.container);
        this.attachEventListeners();
    }

    /**
     * Attach event listeners to the widget
     */
    attachEventListeners() {
        // Star rating clicks
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', (e) => {
                this.rating = parseInt(e.target.dataset.rating);
                this.updateStars();
                this.enableSubmitButton();
            });

            // Hover effect
            star.addEventListener('mouseenter', (e) => {
                const hoverRating = parseInt(e.target.dataset.rating);
                document.querySelectorAll('.rating-star').forEach((s, i) => {
                    if (i < hoverRating) {
                        s.textContent = '⭐';
                    } else {
                        s.textContent = '☆';
                    }
                });
            });
        });

        // Reset on mouse leave
        document.getElementById('rating-stars').addEventListener('mouseleave', () => {
            this.updateStars();
        });

        // Buttons
        document.getElementById('submit-rating').addEventListener('click', () => {
            this.submitRating();
        });

        document.getElementById('cancel-rating').addEventListener('click', () => {
            this.closeWidget();
        });
    }

    /**
     * Update star display based on current rating
     */
    updateStars() {
        document.querySelectorAll('.rating-star').forEach((star, i) => {
            star.textContent = i < this.rating ? '⭐' : '☆';
        });
    }

    /**
     * Enable the submit button when rating is selected
     */
    enableSubmitButton() {
        document.getElementById('submit-rating').disabled = this.rating === 0;
    }

    /**
     * Submit the rating to the API
     */
    async submitRating() {
        if (this.rating === 0) return;

        const review = document.getElementById('rating-review').value.trim();
        const submitBtn = document.getElementById('submit-rating');

        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirim...';

        try {
            const response = await fetch('/api/chatbot/rate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
                body: JSON.stringify({
                    session_id: this.sessionId,
                    rating: this.rating,
                    review: review || null,
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess();
                setTimeout(() => this.closeWidget(), 2000);
            } else {
                this.showError(data.message || 'Gagal mengirim rating');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim';
            }
        } catch (error) {
            console.error('Rating submission error:', error);
            this.showError('Terjadi kesalahan. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim';
        }
    }

    /**
     * Get CSRF token from meta tag or cookie
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    /**
     * Show success message
     */
    showSuccess() {
        const widget = document.getElementById('chatbot-rating-widget');
        if (widget) {
            widget.innerHTML = `
                <div class="text-center py-4">
                    <div class="text-4xl mb-2">✅</div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Terima Kasih!</h3>
                    <p class="text-xs text-gray-5 dark:text-gray-400">Rating Anda sangat membantu kami</p>
                </div>
            `;
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        const widget = document.getElementById('chatbot-rating-widget');
        if (widget) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'p-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs rounded-lg mb-4';
            errorDiv.textContent = message;
            
            const widgetContent = widget.querySelector('.mb-4') || widget.firstChild;
            if (widgetContent) {
                widgetContent.parentNode.insertBefore(errorDiv, widgetContent.nextSibling);
            }
        }
    }

    /**
     * Close and remove the widget
     */
    closeWidget() {
        if (this.container) {
            this.container.remove();
        }
    }

    /**
     * Initialize and show the widget
     */
    show() {
        this.createWidget();
    }
}

/**
 * Usage Example:
 * 
 * // Create and show rating widget
 * const ratingWidget = new ChatbotRatingWidget();
 * ratingWidget.show();
 * 
 * // Or auto-show after chatbot conversation
 * window.addEventListener('load', () => {
 *     setTimeout(() => {
 *         const widget = new ChatbotRatingWidget();
 *         widget.show();
 *     }, 3000); // Show after 3 seconds
 * });
 */

// Auto-initialize if needed
if (window.location.pathname.includes('/chatbot') || document.getElementById('chatbot-container')) {
    window.addEventListener('load', () => {
        // You can trigger the rating widget here
        // const ratingWidget = new ChatbotRatingWidget();
        // ratingWidget.show();
    });
}

export default ChatbotRatingWidget;
