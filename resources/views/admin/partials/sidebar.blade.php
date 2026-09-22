{{--
  partials/sidebar.blade.php
  Included by layouts/admin.blade.php. Highlighting is driven by the
  $activeNav variable the layout extracts from each page's @section('active').
--}}
<aside id="sidebar"
    class="fixed lg:fixed lg:top-0 lg:left-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-200 w-63 shrink-0 min-h-screen text-slate-300 flex flex-col"
    style="background:var(--sidebar)">
    <div class="flex items-center gap-3 px-6 h-17 border-b border-white/5">
        <div class="w-9 h-9 rounded-xl bg-blue-600 grid place-items-center text-white">
            <i class="fa-solid fa-bag-shopping"></i>
        </div>
        <span class="text-white text-lg font-bold tracking-tight">AgriExpress</span>
        <button class="ml-auto lg:hidden text-slate-400" onclick="toggleSidebar()" aria-label="Close menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 text-[14px]">
        <x-nav-link :href="route('admin.dashboard')" icon="fa-house" active="dashboard">Dashboard</x-nav-link>
        <x-nav-link :href="route('admin.categories.index')" icon="fa-layer-group" active="categories">Categories</x-nav-link>
        <x-nav-link :href="route('admin.brands.index')" icon="fa-copyright" active="brands">Brands</x-nav-link>
        <x-nav-link :href="route('admin.products.index')" icon="fa-box" active="products">Products</x-nav-link>
        <x-nav-link :href="route('admin.orders.index')" icon="fa-cart-shopping" active="orders" :badge="$pendingOrderCount ?? null">Orders</x-nav-link>
        <x-nav-link :href="route('admin.customers.index')" icon="fa-users" active="customers">Customers</x-nav-link>
        <x-nav-link :href="route('admin.coupons.index')" icon="fa-ticket" active="coupons">Coupons</x-nav-link>
        <x-nav-link :href="route('admin.reviews.index')" icon="fa-star" active="reviews">Reviews</x-nav-link>
        <x-nav-link :href="route('admin.settings.index')" icon="fa-gear" active="settings" chevron="right">Settings</x-nav-link>
    </nav>

</aside>
<div id="overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>
