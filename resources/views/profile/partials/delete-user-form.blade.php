<section class="space-y-6" data-user-id="{{ auth()->user()->id }}">
    <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50">
        <p class="text-sm text-red-700 dark:text-red-300">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.
        </p>
    </div>

    <button
        @click="
            if (confirm('Apakah Anda yakin ingin menghapus akun Anda?\n\nTindakan ini tidak dapat dibatalkan dan semua data Anda akan hilang selamanya.')) {
                document.getElementById('delete-account-modal').classList.remove('hidden');
            }
        "
        class="px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white font-semibold transition duration-200"
    >
        <i class="fas fa-trash-alt mr-2"></i>Hapus Akun Saya
    </button>

    <!-- Delete Account Modal -->
    <div id="delete-account-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl">
            <!-- Header -->
            <div class="flex items-center justify-center w-16 h-16 mx-auto mt-6 rounded-full bg-red-100 dark:bg-red-900/20">
                <i class="fas fa-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
            </div>

            <!-- Content -->
            <div class="p-6 text-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hapus Akun?</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-3">
                    Tindakan ini akan menghapus akun Anda dan setua data secara permanen. Masukkan kata sandi Anda untuk melanjutkan.
                </p>

                <!-- Password Input -->
                <form method="post" action="{{ route('profile.destroy') }}" class="mt-6" onsubmit="return validateDeleteForm()">
                    @csrf
                    @method('delete')

                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    <input
                        id="delete-password"
                        type="password"
                        name="password"
                        placeholder="Masukkan kata sandi Anda"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 transition"
                        required
                    />
                    @if ($errors->userDeletion->has('password'))
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->userDeletion->first('password') }}</p>
                    @endif

                    <!-- Buttons -->
                    <div class="flex gap-3 mt-6">
                        <button 
                            type="button" 
                            @click="document.getElementById('delete-account-modal').classList.add('hidden'); document.getElementById('delete-password').value = '';"
                            class="flex-1 px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold transition"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white font-semibold transition"
                        >
                            Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateDeleteForm() {
            // Verify user ID hasn't been tampered with
            const currentUserId = {{ auth()->user()->id }};
            const hiddenUserId = parseInt(document.querySelector('input[name="user_id"]').value);
            
            if (currentUserId !== hiddenUserId) {
                alert('Terjadi kesalahan keamanan. Silakan coba lagi.');
                console.error('User ID mismatch detected');
                return false;
            }
            
            const password = document.getElementById('delete-password').value;
            if (!password || password.length === 0) {
                alert('Kata sandi tidak boleh kosong.');
                return false;
            }
            
            return confirm('Akun Anda akan dihapus secara permanen. Anda tidak akan bisa membatalkan tindakan ini.');
        }
    </script>
</section>
