<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['public/css/app.css', 'public/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        html {
            color-scheme: light dark;
        }
        
        body {
            background-color: #f3f4f6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #111827;
                color: #fff;
            }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        
        @media (prefers-color-scheme: dark) {
            .glass-effect {
                background: rgba(31, 41, 55, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="mt-12 min-h-screen flex flex-col justify-center items-center px-4 py-8">
        <!-- Logo & Title -->
        <div class="mb-12 text-center">
            <a href="/" class="inline-flex flex-col items-center gap-4 group">
                <div class="relative">
                    <div class="absolute inset-0 bg-gray-200/30 dark:bg-gray-700/30 rounded-2xl blur-lg group-hover:blur-xl transition-all"></div>
                    <img src="{{ asset('/images/logo-bmkg.png') }}" alt="BMKG" class="w-20 h-20 relative">
                </div>
                <div class="text-center">
                    <h1 class="text-3xl font-bold mb-1 text-gray-900 dark:text-white drop-shadow">Stasiun Geofisika Yogyakarta</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm drop-shadow">Sistem Pelayanan Online</p>
                </div>
            </a>
        </div>

        @props(['wide' => false])

        <div class="w-full {{ $wide ? 'max-w-3xl' : 'max-w-md' }} glass-effect dark:glass-effect.dark rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-8 py-10">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-8 text-center">© {{ date('Y') }} Stasiun Geofisika Yogyakarta. All rights reserved.</p>
    </div>

    <script>
        // Dark mode detection dan aplikasi
        function initDarkMode() {
            const html = document.documentElement;
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (isDark) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }

        // Initialize on page load
        initDarkMode();

        // Listen untuk perubahan system preference
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            const html = document.documentElement;
            if (e.matches) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        });
    </script>
</body>

</html>