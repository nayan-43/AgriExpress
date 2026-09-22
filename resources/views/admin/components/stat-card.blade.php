{{--
  <x-stat-card label="Total sales" value="$12,493.00" change="12.5%" icon="fa-cart-shopping"
               tone="bg-blue-50" icon-bg="bg-blue-500" line-color="#3b82f6" />
--}}
@props(['label', 'value', 'change', 'icon', 'tone' => 'bg-blue-50', 'iconBg' => 'bg-blue-500', 'lineColor' => '#3b82f6'])
<div class="rounded-2xl p-5 border {{ $tone }}" style="border-color:rgba(0,0,0,.05)">
    <div class="flex items-start gap-3">
        <span class="w-11 h-11 rounded-xl {{ $iconBg }} text-white grid place-items-center shrink-0">
            <i class="fa-solid {{ $icon }}"></i>
        </span>
        <div class="min-w-0">
            <p class="text-[13px] text-slate-600">{{ $label }}</p>
            <p class="text-[22px] font-bold text-slate-900 mt-0.5">{{ $value }}</p>
        </div>
    </div>
    <div class="flex items-end justify-between mt-3">
        <p class="text-[12.5px]">
            <span class="text-emerald-600 font-semibold"><i class="fa-solid fa-arrow-up text-[10px]"></i> {{ $change }}</span>
            <span class="text-slate-500">vs last week</span>
        </p>
        <svg viewBox="0 0 88 22" class="w-[86px] h-[26px]" aria-hidden="true">
            <polyline points="0,18 14,14 28,16 42,10 56,12 70,6 84,3" fill="none" stroke="{{ $lineColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</div>
