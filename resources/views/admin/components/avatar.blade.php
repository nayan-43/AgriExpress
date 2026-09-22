{{--
  <x-avatar :name="$customer->name" :index="$loop->index" />
  Colored-initials avatar. Swap the span for an <img> once real photos exist.
--}}
@props(['name', 'index' => 0])
@php
$colors = [
    'bg-pink-100 text-pink-700', 'bg-blue-100 text-blue-700', 'bg-emerald-100 text-emerald-700',
    'bg-amber-100 text-amber-700', 'bg-violet-100 text-violet-700', 'bg-cyan-100 text-cyan-700',
];
$parts = preg_split('/\s+/', trim($name));
$initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr($parts[1] ?? '', 0, 1));
$color = $colors[$index % count($colors)];
@endphp
<span {{ $attributes->merge(['class' => "w-8 h-8 rounded-full $color grid place-items-center text-[11px] font-semibold shrink-0"]) }}>
    {{ $initials }}
</span>
