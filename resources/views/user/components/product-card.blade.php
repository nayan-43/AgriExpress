<article
    class="product-card border border-gray-100 rounded-xl overflow-hidden bg-white flex flex-col transition duration-200 group">
    <div class="product-card-image relative h-44 sm:h-48 overflow-hidden bg-gray-50">
        @if (!empty($product['badge']))
            <span
                class="absolute top-3 left-3 z-10 {{ $product['badge_color'] ?? 'bg-brand-700' }} text-white text-[10px] font-semibold px-2.5 py-1 rounded-full shadow-sm">{{ $product['badge'] }}</span>
        @endif
        <span class="absolute top-0 right-0 z-10 flex flex-col gap-2 p-2">
            <form action="{{ auth('web')->check() ? route('wishlist.toggle', $product['id']) : route('login') }}"
                method="{{ auth('web')->check() ? 'POST' : 'GET' }}" class="">
                @if (auth('web')->check())
                    @csrf
                @endif
                <button type="submit"
                    class="w-9 h-9 rounded-full bg-white/95 shadow-sm flex items-center justify-center text-gray-500 hover:text-rose-500 hover:scale-105 transition"
                    aria-label="Add {{ $product['name'] }} to wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </button>
            </form>
            <form action="{{ route('cart.add', $product['id']) }}" method="POST" class="">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                    class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-brand-700 hover:text-white flex items-center justify-center text-gray-600 transition"
                    aria-label="Add {{ $product['name'] }} to cart" title="Add to cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-shopping-cart-plus preview-icon">
                        <path d="M16 5h6" />
                        <path d="M19 2v6" />
                        <path d="m2.05 2.05 1.099-.028a1 1 0 011.008.815l2.69 14.347A1 1 0 007.83 18H18" />
                        <path d="M4.564 5H12" />
                        <path d="M6.25 14h12.712a2 2 0 001.991-1.57l.172-1.041" />
                        <circle cx="18" cy="20" r="2" />
                        <circle cx="8" cy="20" r="2" />
                    </svg>
                </button>
            </form>

        </span>
        <a href="{{ route('product', $product['slug'] ?? $product['id']) }}"><img src="{{ $product['image'] }}"
                alt="{{ $product['name'] }}" loading="lazy"
                class="w-full h-full object-cover transition duration-500 group-hover:scale-105"></a>
    </div>
    <div class="product-card-body flex flex-1 flex-col p-3.5">
        <a href="{{ route('product', $product['slug'] ?? $product['id']) }}" class="block">
            <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-brand-700 transition">
                {{ $product['name'] }}</p>
            <div class="mt-3">
                <x-star-rating :rating="$product['rating'] ?? 5" :count="$product['reviews'] ?? 0" />
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-lg font-bold text-gray-900">${{ number_format($product['price'], 2) }}</span>
                @if (!empty($product['old_price']))
                    <span
                        class="text-gray-400 line-through text-xs">${{ number_format($product['old_price'], 2) }}</span>
                @endif
            </div>
        </a>
        @if (!empty($product['colors']))
            <div class="flex items-center gap-2 mt-auto pt-3">
                <div class="flex gap-1.5" aria-label="Available colors">
                    @foreach ($product['colors'] as $color)
                        <span class="w-3.5 h-3.5 rounded-full border border-gray-200"
                            style="background-color: {{ str_starts_with($color, '#') ? $color : '#' . ltrim($color, '#') }}"></span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
