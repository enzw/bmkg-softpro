<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>@yield('title', 'BMKG Geofisika Yogyakarta')</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

    @yield('styles')

    <!-- Style -->
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .chart-container {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            /* Distribute bars evenly with spacing */
            width: 80%;
            height: 300px;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
            position: relative;
        }

        .chart-container::before {
            content: '';
            position: absolute;
            left: -10px;
            bottom: 0;
            width: 10px;
            height: 10px;
            background: #333;
        }

        .bar {
            flex: 1;
            /* Allow bars to take up equal space */
            max-width: 50px;
            /* Set a maximum width for bars */
            display: flex;
            align-items: flex-end;
            justify-content: center;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px 4px 0 0;
            margin: 0 5px;
            /* Add horizontal spacing between bars */
        }

        .bar span {
            position: relative;
            top: -3px;
            font-size: 10px;
        }

        .x-labels {
            margin-top: 10px;
            display: flex;
            justify-content: space-around;
            /* Align labels with bars */
            width: 80%;
        }

        .x-label {
            text-align: center;
            flex: 1;
        }

        .custom-select {
            text-align: right;
            min-width: 100px;
        }

        .custom-select select {
            appearance: none;
            width: 25%;
            height: 50px;
            font-size: 1rem;
            padding: 0.1em 0.1em 0.1em 0.5em;
            background-color: #fff;
            border: 1px solid #caced1;
            border-radius: 0.15rem;
            color: #000;
            cursor: pointer;
        }

        .y-labels {
            display: flex;
            flex-direction: column-reverse;
            justify-content: space-between;
            height: 300px;
            margin-right: 10px;
        }

        .y-label {
            text-align: right;
            font-size: 12px;
            padding-right: 5px;
        }

        .chart-container-wrapper {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
        }
    </style>
    <!-- Scripts -->
    @vite(['public/css/app.css', 'public/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        @if (isset($header))
        <header class="py-6 bg-white shadow dark:bg-gray-800">
            <div class="flex items-center px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mr-auto">
                    {{ $header }}
                </div>

                <span class="font-bold dark:text-white">{{ now()->toFormattedDateString() }}</span>
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="relative">
            {{ $slot }}

            @if (Auth::user()->role != 'admin' || !request()->is('admin/*'))
            <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
            <df-messenger
                intent="WELCOME"
                chat-title="Megabot"
                agent-id="c9f258c1-8808-4b8e-b660-8efdca1c1703"
                chat-icon="images/icon.png"
                language-code="en">
            </df-messenger>
            @endif
        </main>
    </div>

    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/1191ef92be.js" crossorigin="anonymous"></script>

    @yield('scripts')
</body>

</html>