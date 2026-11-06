@php
    $navBase = 'flex items-center gap-3 px-3 py-2 rounded-md transition-colors';
    $navMuted = $navBase . ' text-gray-600 hover:bg-gray-50';
    $navActive = $navBase . ' bg-brand-50 text-brand-700 font-medium';
    $ordersActive = request()->routeIs('account.orders', 'order.details');
@endphp

<aside class="border border-gray-100 rounded-lg p-4 h-fit">
    <div class="flex items-center gap-3 px-2 pb-4 mb-4 border-b border-gray-100">
        <span class="w-10 h-10 rounded-full bg-brand-700 text-white flex items-center justify-center font-semibold">
            {{ $user['initials'] }}
        </span>
        <div>
            <p class="font-medium text-gray-900 text-sm">{{ $user['name'] }}</p>
            <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
        </div>
    </div>
    <nav class="space-y-1 text-sm">
        <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? $navActive : $navMuted }}"
            aria-current="{{ request()->routeIs('account') ? 'page' : 'false' }}">
            <i class="fa-solid fa-gauge w-4"></i> Dashboard
        </a>
        <a href="{{ route('account.orders') }}" class="{{ $ordersActive ? $navActive : $navMuted }}"
            aria-current="{{ $ordersActive ? 'page' : 'false' }}">
            <i class="fa-solid fa-box w-4"></i> My Orders
        </a>
        <a href="{{ route('account.addresses') }}"
            class="{{ request()->routeIs('account.addresses') ? $navActive : $navMuted }}"
            aria-current="{{ request()->routeIs('account.addresses') ? 'page' : 'false' }}">
            <i class="fa-solid fa-location-dot w-4"></i> Addresses
        </a>
        <a href="{{ route('wishlist') }}" class="{{ request()->routeIs('wishlist') ? $navActive : $navMuted }}"
            aria-current="{{ request()->routeIs('wishlist') ? 'page' : 'false' }}">
            <i class="fa-regular fa-heart w-4"></i> Wishlist
        </a>
        <a href="{{ route('account.profile') }}"
            class="{{ request()->routeIs('account.profile') ? $navActive : $navMuted }}"
            aria-current="{{ request()->routeIs('account.profile') ? 'page' : 'false' }}">
            <i class="fa-solid fa-gear w-4"></i> Profile Settings
        </a>
        <a href="{{ route('account.profile') }}#password"
            class="{{ request()->routeIs('account.profile') ? $navActive : $navMuted }}"
            aria-current="{{ request()->routeIs('account.profile') ? 'page' : 'false' }}">
            <i class="fa-solid fa-key w-4"></i> Change Password
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50 text-left">
                <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Logout
            </button>
        </form>
    </nav>
</aside>
