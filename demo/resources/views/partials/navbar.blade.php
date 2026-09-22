<header class="border-b border-gray-100 sticky top-0 bg-white z-40">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between gap-6">

        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <span class="w-9 h-9 rounded-lg bg-brand-700 text-white flex items-center justify-center"><i
                    class="fa-solid fa-bag-shopping"></i></span>
            <span class="text-xl font-semibold text-gray-900">AgriExpress</span>
        </a>

        <!-- Desktop nav -->
        <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-gray-700">
            <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'text-brand-700 border-b-2 border-brand-700 pb-1' : 'hover:text-brand-700' }}">Home</a>

            <div class="relative" data-dropdown>
                <button data-dropdown-trigger
                    class="flex items-center gap-1 {{ request()->routeIs('shop') ? 'text-brand-700' : 'hover:text-brand-700' }}">
                    Shop <i class="fa-solid fa-chevron-down text-[10px] mt-0.5 chevron transition-transform"></i>
                </button>
                <div data-dropdown-menu
                    class="hidden absolute top-full left-0 mt-3 w-48 bg-white border border-gray-100 rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('shop') }}"
                        class="block px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700">All Products</a>
                    <a href="{{ route('shop') }}?category=new"
                        class="block px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700">New Arrivals</a>
                    <a href="{{ route('shop') }}?category=best-sellers"
                        class="block px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700">Best Sellers</a>
                    <a href="{{ route('shop') }}?category=sale"
                        class="block px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700">On Sale</a>
                </div>
            </div>

            <div class="relative" data-dropdown>
                <button data-dropdown-trigger class="flex items-center gap-1 hover:text-brand-700">
                    Categories <i class="fa-solid fa-chevron-down text-[10px] mt-0.5 chevron transition-transform"></i>
                </button>
                <div data-dropdown-menu
                    class="hidden absolute top-full left-0 mt-3 w-56 bg-white border border-gray-100 rounded-lg shadow-lg py-2 z-50 grid grid-cols-1">
                    <a href="{{ route('shop') }}?category=home-living"
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700"><i
                            class="fa-solid fa-couch w-4 text-emerald-600"></i> Home & Living</a>
                    <a href="{{ route('shop') }}?category=women"
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700"><i
                            class="fa-solid fa-shirt w-4 text-rose-500"></i> Women</a>
                    <a href="{{ route('shop') }}?category=men"
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700"><i
                            class="fa-solid fa-shirt w-4 text-sky-500"></i> Men</a>
                    <a href="{{ route('shop') }}?category=electronics"
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700"><i
                            class="fa-solid fa-mobile-screen w-4 text-violet-500"></i> Electronics</a>
                    <a href="{{ route('shop') }}?category=footwear"
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700"><i
                            class="fa-solid fa-shoe-prints w-4 text-green-600"></i> Footwear</a>
                    <a href="{{ route('shop') }}?category=beauty"
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-brand-50 hover:text-brand-700"><i
                            class="fa-solid fa-pump-soap w-4 text-red-400"></i> Beauty</a>
                </div>
            </div>

            <a href="{{ route('about') }}"
                class="{{ request()->routeIs('about') ? 'text-brand-700 border-b-2 border-brand-700 pb-1' : 'hover:text-brand-700' }}">About</a>
            <a href="{{ route('blog') }}"
                class="{{ request()->routeIs('blog*') ? 'text-brand-700 border-b-2 border-brand-700 pb-1' : 'hover:text-brand-700' }}">Blog</a>
            <a href="{{ route('contact') }}"
                class="{{ request()->routeIs('contact') ? 'text-brand-700 border-b-2 border-brand-700 pb-1' : 'hover:text-brand-700' }}">Contact</a>
        </nav>

        <div class="flex items-center gap-4">
            <form action="{{ route('shop') }}" method="GET"
                class="hidden md:flex items-center bg-gray-100 rounded-full px-4 py-2 w-56">
                <input type="text" name="q" placeholder="Search products..."
                    class="bg-transparent outline-none text-sm w-full">
                <button type="submit"><i class="fa-solid fa-magnifying-glass text-gray-500"></i></button>
            </form>

            <a href="{{ auth()->check() ? route('account') : route('login') }}"
                class="text-gray-700 hover:text-brand-700">
                <i class="fa-regular fa-user text-lg"></i>
            </a>
            <a href="{{ url('/wishlist') }}" class="text-gray-700 hover:text-brand-700"><i
                    class="fa-regular fa-heart text-lg"></i></a>
            <a href="{{ route('cart') }}" class="relative text-gray-700 hover:text-brand-700">
                <i class="fa-solid fa-cart-shopping text-lg"></i>
                <span data-cart-badge
                    class="absolute -top-2 -right-2 bg-brand-700 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount ?? 2 }}</span>
            </a>

            <!-- Mobile menu button -->
            <button data-mobile-menu-btn aria-expanded="false"
                class="lg:hidden text-gray-700 w-8 h-8 flex items-center justify-center">
                <i class="fa-solid fa-bars text-xl icon-open"></i>
                <i class="fa-solid fa-xmark text-xl icon-close hidden"></i>
            </button>
        </div>
    </div>

    <!-- Mobile menu panel -->
    <div data-mobile-menu-panel
        class="hidden lg:hidden border-t border-gray-100 px-4 py-4 space-y-3 text-sm font-medium text-gray-700">
        <form action="{{ route('shop') }}" method="GET"
            class="flex items-center bg-gray-100 rounded-full px-4 py-2 mb-2">
            <input type="text" name="q" placeholder="Search products..."
                class="bg-transparent outline-none text-sm w-full">
            <button type="submit"><i class="fa-solid fa-magnifying-glass text-gray-500"></i></button>
        </form>
        <a href="{{ route('home') }}" class="block py-1">Home</a>
        <a href="{{ route('shop') }}" class="block py-1">Shop</a>
        <a href="{{ route('shop') }}" class="block py-1">Categories</a>
        <a href="{{ route('about') }}" class="block py-1">About</a>
        <a href="{{ route('blog') }}" class="block py-1">Blog</a>
        <a href="{{ route('contact') }}" class="block py-1">Contact</a>
    </div>
</header>
