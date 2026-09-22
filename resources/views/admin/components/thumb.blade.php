@props(['icon' => 'fa-image', 'tone' => 'bg-slate-100 text-slate-600', 'size' => 'w-9 h-9', 'src' => null, 'alt' => ''])
@if($src)
    <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => "$size rounded-lg object-cover shrink-0"]) }}>
@else
    <span {{ $attributes->merge(['class' => "$size $tone rounded-lg grid place-items-center shrink-0"]) }}>
        <i class="fa-solid {{ $icon }} text-[13px]"></i>
    </span>
@endif
