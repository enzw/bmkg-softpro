<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['public/css/app.css', 'public/js/app.js'])


</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10">
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center gap-3">
                <img src="{{ asset('/images/logo-bmkg.png') }}" alt="BMKG" width="80" height="80">
                <span class="text-xl font-bold text-gray-900 dark:text-white text-center">Stasiun Geofisika Yogyakarta</span>
            </a>
        </div>

        <div class="w-full max-w-md px-6 py-8 bg-white dark:bg-gray-800 shadow-lg dark:shadow-xl overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
            {{ $slot }}
        </div>
    </div>
</body>

</html>