{{--
  admin/orders/index.blade.php
  Populated by App\Http\Controllers\Admin\OrderController@index.
--}}
@extends('admin.layouts.admin')

@section('title', 'Orders')
@section('active', 'orders')

@php $activeTab = request('status', 'All'); @endphp

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div><h1 class="text-2xl font-bold">Orders</h1><p class="muted text-sm mt-1">Manage customer orders</p></div>
    <button class="surface border rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-file-export mr-2 muted"></i>Export</button>
</div>

<div class="surface border rounded-2xl">
    <div class="flex gap-6 px-5 border-b bd overflow-x-auto text-sm">
        @foreach ($tabs as $label => $count)
            <a href="{{ route('admin.orders.index', ['status' => $label]) }}"
               class="py-3.5 font-medium border-b-2 whitespace-nowrap {{ $activeTab === $label ? 'border-blue-600 text-blue-600' : 'border-transparent muted' }}">
                {{ $label }} ({{ $count }})
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.orders.index') }}" class="p-4 flex flex-wrap gap-3">
        <input type="hidden" name="status" value="{{ $activeTab }}">
        <label class="flex items-center gap-2 flex-1 min-w-55 border bd rounded-lg px-3 h-10">
            <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="flex-1 text-sm border-0" placeholder="Search by order number, customer...">
        </label>
        <button type="submit" class="border bd rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-filter mr-2 muted"></i>Filter</button>
    </form>

    <div class="scroll-x">
        <table class="w-full text-sm min-w-200">
            <thead><tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                <th class="px-5 py-3 font-medium">Order number</th>
                <th class="px-5 py-3 font-medium">Customer</th><th class="px-5 py-3 font-medium">Total</th>
                <th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Payment</th>
                <th class="px-5 py-3 font-medium">Date</th><th class="px-5 py-3 font-medium">Action</th>
            </tr></thead>
            <tbody class="divide-b">
                @forelse ($orders as $i => $o)
                    <tr>
                        <td class="px-5 py-3.5 font-medium">{{ $o->order_number }}</td>
                        <td class="px-5 py-3.5"><span class="flex items-center gap-2.5"><x-avatar :name="$o->user->name ?? 'Guest'" :index="$i" />{{ $o->user->name ?? 'Guest checkout' }}</span></td>
                        <td class="px-5 py-3.5 font-medium">${{ number_format($o->total_price, 2) }}</td>
                        <td class="px-5 py-3.5"><x-pill :status="$o->status_label" /></td>
                        <td class="px-5 py-3.5"><x-pill :status="$o->payment_status_label" /></td>
                        <td class="px-5 py-3.5 muted">{{ $o->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5"><a href="{{ route('admin.orders.show', $o) }}" class="border bd rounded-lg px-3 py-1.5 text-[12.5px] text-blue-600 font-medium">View</a></td>
                    </tr>
                @empty
                    <x-empty-state :colspan="7" message="No orders here yet. Change the filter to see more." />
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination-bar :paginator="$orders" />
</div>
@endsection
