<section class="space-y-6">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Nama Lengkap
            </label>
            <input id="name" name="name" type="text" class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition" 
                   :value="old('name', $user->name)" required autofocus autocomplete="name" />
            @if ($errors->has('name'))
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Email
            </label>
            <input id="email" name="email" type="email" class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition" 
                   :value="old('email', $user->email)" required autocomplete="username" />
            @if ($errors->has('email'))
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50">
                    <p class="text-sm text-amber-700 dark:text-amber-400 mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Alamat email Anda belum diverifikasi
                    </p>
                    <button form="send-verification" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                        Kirim ulang email verifikasi
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle mr-2"></i>
                            Email verifikasi telah dikirim ke alamat Anda
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <button type="submit" class="px-6 py-3 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 dark:from-blue-600 dark:to-blue-700 text-white font-semibold transition duration-200 transform hover:scale-105">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</section>
