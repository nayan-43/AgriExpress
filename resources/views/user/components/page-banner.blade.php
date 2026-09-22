{{--
  Shared inner-page banner.
  Tinted full-bleed band, breadcrumb + eyebrow + heading on the left,
  photo bleeding off the right edge.

  @param string $eyebrow   Small uppercase label
  @param string $heading   Main heading (may contain <br>)
  @param string $subtitle  Supporting copy
  @param string $image     Background photo URL
  @param array  $crumbs    ['Label' => url|null, ...]
  @param string $bg        Tailwind bg class (default bg-brand-50)
  @param array  $cta       optional ['label' => '', 'url' => '']
--}}
@props([
  'eyebrow'  => '',
  'heading'  => '',
  'subtitle' => '',
  'image'    => '',
  'crumbs'   => [],
  'bg'       => 'bg-brand-50',
  'cta'      => null,
])

<section class="relative {{ $bg }} overflow-hidden">
  {{-- Photo bleeding off the right --}}
  @if($image)
    <div class="absolute inset-y-0 right-0 w-1/2 hidden md:block">
      <img src="{{ $image }}" alt="" class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-linear-to-r {{ str_replace('bg-', 'from-', $bg) }} via-transparent to-transparent"></div>
    </div>
  @endif

  <div class="relative max-w-7xl mx-auto px-4 py-12 md:py-16">
    <div class="max-w-lg">
      @if(count($crumbs))
        <p class="text-xs text-gray-500 mb-3">
          @foreach($crumbs as $label => $url)
            @if($url)
              <a href="{{ $url }}" class="hover:text-brand-700">{{ $label }}</a>
              <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            @else
              <span class="text-gray-700">{{ $label }}</span>
            @endif
          @endforeach
        </p>
      @endif

      @if($eyebrow)
        <p class="uppercase tracking-wide text-xs font-semibold text-brand-700 mb-3">{{ $eyebrow }}</p>
      @endif

      <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-3">{!! $heading !!}</h1>

      @if($subtitle)
        <p class="text-gray-600 text-sm md:text-base max-w-md">{{ $subtitle }}</p>
      @endif

      @if($cta)
        <a href="{{ $cta['url'] }}" class="inline-flex items-center gap-2 bg-brand-700 hover:bg-brand-800 text-white px-6 py-3 rounded-md font-medium mt-6">
          {{ $cta['label'] }} <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
      @endif
    </div>
  </div>
</section>
