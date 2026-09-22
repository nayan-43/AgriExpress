{{--
  <x-thumb :src="$product->main_image ? Storage::disk('supabase')->url($product->main_image) : null"
           icon="fa-box" tone="bg-slate-100 text-slate-600" />
  Shows the real image when a URL is given; falls back to an icon tile
  (used for products/categories/brands with no image yet).
--}}
@props(['icon' => 'fa-image', 'tone' => 'bg-slate-100 text-slate-600', 'size' => 'w-9 h-9', 'src' => null, 'alt' => ''])
@if($src)
    <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => "$size rounded-lg object-cover shrink-0"]) }}>
@else
    <span {{ $attributes->merge(['class' => "$size $tone rounded-lg grid place-items-center shrink-0"]) }}>
        <i class="fa-solid {{ $icon }} text-[13px]"></i>
    </span>
@endif
