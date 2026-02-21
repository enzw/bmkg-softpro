<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title' . ' - BMKG Geofisika Yogyakarta', 'BMKG Geofisika Yogyakarta')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900|antic-didone:400&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- asset --}}
    @vite(['public/css/app.css', 'public/js/app.js'])

    <style>
        :root {
            font-family: 'Inter', sans-serif
        }
    </style>
</head>

<body>
    @include('components.nav')
    @yield('content')
    
    {{-- Floating Chatbot --}}
    @include('components.floating-chatbot')
    
    {{-- Chatbot/Dialogflow disabled --}}
    {{-- <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger intent="WELCOME" chat-title="Megabot" agent-id="c9f258c1-8808-4b8e-b660-8efdca1c1703"
        chat-icon="images/icon.png" language-code="en">
        <!-- session-id="SESSION_ID_HERE" -->
    </df-messenger>
    <script>
        window.addEventListener("dfMessengerLoaded", function () {
            const messenger = document.querySelector("df-messenger");
            const sessionId = localStorage.getItem("dialogflowSessionId") || generateSessionId();
            messenger.setAttribute("session-id", sessionId);
        });

        function generateSessionId() {
            const id = Math.random().toString(36).substring(7);
            localStorage.setItem("dialogflowSessionId", id);
            return id;
        }
    </script> --}}
    @include('components.footer')

    {{-- Session Timeout Warning --}}
    @auth
        <script src="{{ asset('js/session-timeout.js') }}"></script>
    @endauth
</body>

</html>