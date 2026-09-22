{{--
  layouts/guest.blade.php
  Layout for unauthenticated pages (login). No sidebar/topbar — just the
  shared head and design tokens, so the auth screens can define their own
  full-bleed two-column structure.
--}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign in') &middot; AgriExpress</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous"></script>
    @stack('head')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            background: #eef1f8;
        }

        .brand-side {
            background: radial-gradient(1100px 500px at -10% -10%, #16234a 0%, #0b1533 55%), #0b1533;
        }

        .feature-icon {
            background: rgba(37, 99, 235, .35);
        }

        .laptop-shadow {
            filter: drop-shadow(0 30px 40px rgba(0, 0, 0, .35));
        }

        .blob {
            position: absolute;
            border-radius: 9999px;
            filter: blur(2px);
            opacity: .5;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }
        }

        .floaty {
            animation: floaty 5s ease-in-out infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .floaty {
                animation: none;
            }
        }
    </style>
</head>

<body class="min-h-screen">

    @yield('content')

    <div id="toast"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-sm px-5 py-2.5 rounded-lg opacity-0 pointer-events-none transition-opacity z-50">
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>

</html>
