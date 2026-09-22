@props(['rating' => 5, 'count' => 0])
<div {{ $attributes->merge(['class' => 'flex items-center gap-1 text-amber-400 text-xs']) }}>
  @for ($i = 1; $i <= 5; $i++)
    @if ($rating >= $i)
      <i class="fa-solid fa-star"></i>
    @elseif ($rating > $i - 1)
      <i class="fa-solid fa-star-half-stroke"></i>
    @else
      <i class="fa-regular fa-star"></i>
    @endif
  @endfor
  @if($count)
    <span class="text-gray-400 ml-1">({{ $count }})</span>
  @endif
</div>
