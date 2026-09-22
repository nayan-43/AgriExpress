{{--
  partials/topbar.blade.php
  Included by layouts/admin.blade.php. Uses Laravel's auth() helper for the
  signed-in admin's name and the logout route/form.
--}}
<header class="sticky top-0 z-20 h-[68px] surface border-b flex items-center gap-4 px-4 sm:px-6">
    <button class="text-slate-500" onclick="toggleSidebar()" aria-label="Open menu">
        <i class="fa-solid fa-bars"></i>
    </button>

    <form action="{{ route('admin.search') ?? '#' }}" method="GET" class="hidden sm:flex items-center gap-2 flex-1 max-w-[430px] rounded-xl px-4 h-10 border bd" style="background:var(--bg)">
        <input type="search" name="q" class="flex-1 text-sm border-0" placeholder="Search for products, orders, customers...">
        <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i></button>
    </form>

    <div class="ml-auto flex items-center gap-4">
        <button class="relative text-slate-500" aria-label="Notifications">
            <i class="fa-regular fa-bell text-lg"></i>
            <span class="absolute -top-2 -right-2 text-[10px] bg-red-500 text-white rounded-full w-4 h-4 grid place-items-center">{{ $unreadNotifications ?? 3 }}</span>
        </button>

        <button class="text-slate-500 hidden sm:block" onclick="toggleTheme()" title="Switch theme">
            <i class="fa-solid fa-circle-half-stroke text-lg"></i>
        </button>

        <div class="relative">
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 grid place-items-center font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 1)) }}
                </div>
                <div class="hidden sm:block leading-tight text-left">
                    <p class="text-[13.5px] font-semibold">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[11.5px] muted">{{ auth()->user()->role ?? 'Super Admin' }}</p>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            <div class="hidden absolute right-0 mt-2 w-44 surface border bd rounded-xl shadow-lg py-1 text-sm z-30">
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-black/5">
                    <i class="fa-solid fa-gear w-4 muted"></i> Settings
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-black/5 text-red-500">
                        <i class="fa-solid fa-right-from-bracket w-4"></i> Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
