{{-- Expects a $product array with keys:
     id, name, image, price, old_price, rating, reviews, badge, badge_color, colors --}}
<a href="{{ route('product', $product['id'] ?? 1) }}" class="border border-gray-100 rounded-lg overflow-hidden block hover:shadow-md transition group">
  <div class="relative">
    @if(!empty($product['badge']))
      <span class="absolute top-2 left-2 {{ $product['badge_color'] ?? 'bg-brand-700' }} text-white text-[10px] font-semibold px-2 py-1 rounded">{{ $product['badge'] }}</span>
    @endif
    <button data-wishlist-btn class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white/90 flex items-center justify-center text-gray-500 hover:text-rose-500 z-10">
      <i class="fa-regular fa-heart text-xs"></i>
    </button>
    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-48 object-cover">
  </div>
  <div class="p-3">
    <p class="text-sm font-medium text-gray-800 truncate">{{ $product['name'] }}</p>
    <x-star-rating :rating="$product['rating'] ?? 5" :count="$product['reviews'] ?? 0" class="my-1" />
    <div class="flex items-center gap-2">
      <span class="font-semibold text-gray-900">${{ number_format($product['price'], 2) }}</span>
      @if(!empty($product['old_price']))
        <span class="text-gray-400 line-through text-xs">${{ number_format($product['old_price'], 2) }}</span>
      @endif
    </div>
    @if(!empty($product['colors']))
      <div class="flex items-center justify-between mt-2">
        <div class="flex gap-1">
          @foreach($product['colors'] as $color)
            <span class="w-3.5 h-3.5 rounded-full border {{ $color }}"></span>
          @endforeach
        </div>
        <button class="w-7 h-7 rounded-full bg-gray-100 hover:bg-brand-700 hover:text-white flex items-center justify-center text-gray-600">
          <i class="fa-solid fa-cart-plus text-xs"></i>
        </button>
      </div>
    @endif
  </div>
</a>
