{{--
  partials/sidebar.blade.php
  Included by layouts/admin.blade.php. Highlighting is driven by the
  $activeNav variable the layout extracts from each page's @section('active').
--}}
<aside id="sidebar"
    class="fixed lg:static z-40 -translate-x-full lg:translate-x-0 transition-transform duration-200 w-[252px] shrink-0 min-h-screen text-slate-300 flex flex-col"
    style="background:var(--sidebar)">
    <div class="flex items-center gap-3 px-6 h-[68px] border-b border-white/5">
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
        <x-nav-link :href="route('admin.products.index')" icon="fa-box" active="products">Products</x-nav-link>
        <x-nav-link :href="route('admin.categories.index')" icon="fa-layer-group" active="categories">Categories</x-nav-link>
        <x-nav-link :href="route('admin.brands.index')" icon="fa-copyright" active="brands">Brands</x-nav-link>
        <x-nav-link :href="route('admin.orders.index')" icon="fa-cart-shopping" active="orders" :badge="$pendingOrderCount ?? null">Orders</x-nav-link>
        <x-nav-link :href="route('admin.customers.index')" icon="fa-users" active="customers">Customers</x-nav-link>
        <x-nav-link :href="route('admin.coupons.index')" icon="fa-ticket" active="coupons">Coupons</x-nav-link>
        <x-nav-link :href="route('admin.reviews.index')" icon="fa-star" active="reviews">Reviews</x-nav-link>
        <x-nav-link href="#" icon="fa-clipboard-list" active="inventory">Inventory</x-nav-link>
        <x-nav-link href="#" icon="fa-chart-column" active="reports" chevron="right">Reports</x-nav-link>
        <x-nav-link href="#" icon="fa-bullhorn" active="marketing" chevron="right">Marketing</x-nav-link>
        <x-nav-link :href="route('admin.settings.index')" icon="fa-gear" active="settings" chevron="right">Settings</x-nav-link>
    </nav>

    <div class="m-4 rounded-2xl p-5 text-white" style="background:var(--sidebar-2)">
        <i class="fa-solid fa-bag-shopping text-2xl text-blue-400"></i>
        <p class="font-semibold mt-3">Grow your sales</p>
        <p class="text-[12.5px] text-slate-400 mt-1 leading-relaxed">Track performance, manage your store and reach more
            customers.</p>
        <button class="mt-3 w-full bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg py-2">Upgrade
            plan</button>
    </div>
</aside>
<div id="overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>
