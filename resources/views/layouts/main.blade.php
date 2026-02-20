<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title' . ' - BMKG Geofisika Yogyakarta', 'BMKG Geofisika Yogyakarta')</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900|antic-didone:400&display=swap"
        rel="stylesheet" />

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

    {{-- Font Awesome --}}
    {{--
    <script src="https://kit.fontawesome.com/1191ef92be.js" crossorigin="anonymous"></script> --}}
</body>

</html>