<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AgriExpress — Modern Living, Modern Shopping')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('assets/images/favicon.png') }}" rel="icon" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        [data-carousel-track] {
            display: flex;
            transition: transform .5s ease;
        }

        [data-carousel-slide] {
            flex: 0 0 100%;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-white text-gray-800" data-cart-count="{{ $cartCount ?? 0 }}">

    @include('user.partials.topbar')
    @include('user.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('user.partials.footer')

    <script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>
    @stack('scripts')
</body>

</html>
