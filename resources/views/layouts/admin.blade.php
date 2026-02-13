<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title' . ' - Admin BMKG Geofisika Yogyakarta', 'Admin BMKG Geofisika Yogyakarta')</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600|antic-didone:400&display=swap" rel="stylesheet" />

    {{-- asset --}}
    @vite(['public/css/app.css', 'public/js/app.js'])

    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            font-family: 'Inter', sans-serif
        }
    </style>
</head>

<body>
    @include('components.nav')
    <div class="min-h-screen bg-white dark:bg-gray-900 pb-40">
        <div class="container px-4 mx-auto py-10">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar -->
                <div class="pt-12 lg:col-span-1">
                    <x-admin-sidebar />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="pt-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Font Awesome --}}
    {{-- <script src="https://kit.fontawesome.com/1191ef92be.js" crossorigin="anonymous"></script> --}}

    {{-- Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>
</body>

</html>