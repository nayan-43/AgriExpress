<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; AgriExpress</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous"></script>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>

<body class="min-h-screen">

    @php($activeNav = $__env->yieldContent('active'))

    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        <div class="flex-1 min-w-0 flex flex-col lg:ml-63">
            @include('admin.partials.topbar')

            <main class="p-4 sm:p-6 flex-1">
                @if (session('status'))
                    <div
                        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 text-sm px-4 py-3">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div id="toast"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-sm px-5 py-2.5 rounded-lg opacity-0 pointer-events-none transition-opacity z-50">
    </div>

    <script src="{{ asset('assets/js/admin/admin.js') }}"></script>
    @stack('scripts')
</body>

</html>
