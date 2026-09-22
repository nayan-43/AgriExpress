<div class="bg-white rounded-xl p-6 text-left shadow-sm h-full">
    <div class="text-amber-400 text-sm mb-3">
        @for ($i = 0; $i < 5; $i++)
            <i class="fa-solid fa-star"></i>
        @endfor
    </div>
    <p class="text-gray-600 text-sm mb-4">&ldquo;{{ $t['quote'] }}&rdquo;</p>
    <div class="flex items-center gap-3">
        <img src="{{ $t['avatar'] }}" class="w-10 h-10 rounded-full object-cover">
        <div>
            <p class="text-sm font-semibold text-gray-900">{{ $t['name'] }}</p>
            <p class="text-xs text-emerald-600"><i class="fa-solid fa-circle-check"></i> Verified Buyer</p>
        </div>
    </div>
</div>
