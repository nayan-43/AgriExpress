<article class="product-card border border-gray-100 rounded-xl overflow-hidden bg-white flex flex-col transition duration-200 group">
    <div class="relative aspect-4/3 overflow-hidden bg-gray-50">
        @if (!empty($product['badge']))
            <span
                class="absolute top-3 left-3 z-10 {{ $product['badge_color'] ?? 'bg-brand-700' }} text-white text-[10px] font-semibold px-2.5 py-1 rounded-full shadow-sm">{{ $product['badge'] }}</span>
        @endif
        <form action="{{ auth('web')->check() ? route('wishlist.toggle', $product['id']) : route('login') }}"
            method="{{ auth('web')->check() ? 'POST' : 'GET' }}" class="absolute top-2 right-2 z-10">
            @if (auth('web')->check())
                @csrf
            @endif
            <button type="submit"
                class="w-9 h-9 rounded-full bg-white/95 shadow-sm flex items-center justify-center text-gray-500 hover:text-rose-500 hover:scale-105 transition"
                aria-label="Add {{ $product['name'] }} to wishlist">
                <i class="fa-regular fa-heart text-xs"></i>
            </button>
        </form>
        <a href="{{ route('product', $product['slug'] ?? $product['id']) }}"><img src="{{ $product['image'] }}"
                alt="{{ $product['name'] }}" loading="lazy"
                class="w-full h-full object-cover transition duration-500 group-hover:scale-105"></a>
    </div>
    <a href="{{ route('product', $product['slug'] ?? $product['id']) }}" class="product-card-body flex flex-1 flex-col p-4">
        <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-brand-700 transition">{{ $product['name'] }}</p>
        <div class="mt-1.5">
            <x-star-rating :rating="$product['rating'] ?? 5" :count="$product['reviews'] ?? 0" />
        </div>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-lg font-bold text-gray-900">${{ number_format($product['price'], 2) }}</span>
            @if (!empty($product['old_price']))
                <span class="text-gray-400 line-through text-xs">${{ number_format($product['old_price'], 2) }}</span>
            @endif
        </div>
        @if (!empty($product['colors']))
            <div class="flex items-center justify-between mt-2">
                <div class="flex gap-1">
                    @foreach ($product['colors'] as $color)
                        <span class="w-3.5 h-3.5 rounded-full border {{ $color }}"></span>
                    @endforeach
                </div>
                <button type="button"
                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-brand-700 hover:text-white flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-cart-plus text-xs"></i>
                </button>
            </div>
        @endif
    </a>
</article>
