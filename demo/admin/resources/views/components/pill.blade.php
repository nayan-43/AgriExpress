{{--
  <x-pill :status="$order->status" />
  Renders a status badge. Colors are keyed by the common AgriExpress statuses;
  anything unrecognised falls back to a neutral grey.
--}}
@props(['status'])
@php
    $map = [
        'Processing' => 'bg-amber-100 text-amber-700',
        'Shipped' => 'bg-sky-100 text-sky-700',
        'Delivered' => 'bg-emerald-100 text-emerald-700',
        'Cancelled' => 'bg-red-100 text-red-700',
        'Paid' => 'bg-emerald-100 text-emerald-700',
        'Pending' => 'bg-red-100 text-red-600',
        'Published' => 'bg-emerald-100 text-emerald-700',
        'Draft' => 'bg-amber-100 text-amber-700',
        'Active' => 'bg-emerald-100 text-emerald-700',
        'Inactive' => 'bg-red-100 text-red-600',
        'Blocked' => 'bg-red-100 text-red-600',
    ];
    $class = $map[$status] ?? 'bg-slate-100 text-slate-600';
@endphp
<span {{ $attributes->merge(['class' => "text-[11.5px] font-semibold rounded-full px-2.5 py-1 $class"]) }}>
    {{ $status }}
</span>
