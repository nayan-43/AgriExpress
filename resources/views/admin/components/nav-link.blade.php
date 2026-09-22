{{--
  <x-nav-link href="{{ route('admin.dashboard') }}" icon="fa-house" active="dashboard">Dashboard</x-nav-link>
  Sidebar link. `active` is matched against $activeNav, which each page sets
  via @section('active', 'products'); highlights automatically.
--}}
@props(['href' => '#', 'icon', 'active' => null, 'badge' => null, 'chevron' => null])
@php
    $isActive =
        $active !== null &&
        (($active === 'dashboard' && request()->routeIs('admin.dashboard')) ||
            ($active !== 'dashboard' && request()->routeIs('admin.' . $active . '*')));
@endphp
<a href="{{ $href }}"
    class="side-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg {{ $isActive ? 'active' : '' }}">
    <i class="fa-solid {{ $icon }} w-4"></i>
    {{ $slot }}
    @if ($badge)
        <span class="ml-auto text-[11px] bg-red-500 text-white rounded-full px-2 py-0.5">{{ $badge }}</span>
    @elseif($chevron === 'down')
        <i class="fa-solid fa-chevron-down ml-auto text-[10px] opacity-60"></i>
    @elseif($chevron === 'right')
        <i class="fa-solid fa-chevron-right ml-auto text-[10px] opacity-60"></i>
    @endif
</a>
