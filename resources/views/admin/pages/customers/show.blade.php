{{--
  admin/customers/show.blade.php
  Controller: App\Http\Controllers\Admin\CustomerController@show
--}}
@extends('admin.layouts.admin')

@section('title', $customer->name)
@section('active', 'customers')

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div>
        <h1 class="text-2xl font-bold">{{ $customer->name }}</h1>
        <p class="text-sm mt-1">
            <a href="{{ route('admin.customers.index') }}" class="text-blue-600">Customers</a>
            <i class="fa-solid fa-chevron-right text-[9px] muted mx-1"></i>
            <span class="muted">{{ $customer->name }}</span>
        </p>
    </div>
    <form action="{{ route('admin.customers.updateStatus', $customer) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="{{ $customer->status ? 0 : 1 }}">
        <button type="submit" class="border bd rounded-lg px-4 h-10 text-sm font-medium {{ $customer->status ? 'text-red-500' : 'text-emerald-600' }}">
            {{ $customer->status ? 'Block customer' : 'Unblock customer' }}
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-5">
    <div class="surface border rounded-2xl p-5 space-y-5 h-fit">
        <div class="flex items-center gap-3">
            <x-avatar :name="$customer->name" class="w-14 h-14 text-lg" />
            <div>
                <p class="font-semibold">{{ $customer->name }}</p>
                <x-pill :status="$customer->status_label" />
            </div>
        </div>
        <div class="pt-4 border-t bd text-sm space-y-2">
            <p class="flex items-center gap-2"><i class="fa-regular fa-envelope w-4 muted"></i>{{ $customer->email }}</p>
            <p class="flex items-center gap-2"><i class="fa-solid fa-phone w-4 muted"></i>{{ $customer->phone ?? '—' }}</p>
            <p class="flex items-center gap-2"><i class="fa-regular fa-calendar w-4 muted"></i>Joined {{ $customer->created_at->format('M d, Y') }}</p>
        </div>

        @if ($customer->addresses->isNotEmpty())
            <div class="pt-4 border-t bd text-sm">
                <p class="font-medium mb-2">Saved addresses</p>
                <div class="space-y-3">
                    @foreach ($customer->addresses as $address)
                        <div class="text-[13px] muted leading-relaxed">
                            <span class="text-[11px] font-semibold uppercase text-blue-600">{{ $address->type }}{{ $address->is_default ? ' · Default' : '' }}</span><br>
                            {{ $address->first_name }} {{ $address->last_name }}<br>
                            {{ $address->address_line_1 }}{{ $address->address_line_2 ? ', '.$address->address_line_2 : '' }}<br>
                            {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="surface border rounded-2xl">
        <div class="p-5 pb-3"><h2 class="font-semibold">Recent orders</h2></div>
        <div class="scroll-x">
            <table class="w-full text-sm min-w-[640px]">
                <thead><tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                    <th class="px-5 py-3 font-medium">Order</th><th class="px-5 py-3 font-medium">Total</th>
                    <th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium">Action</th>
                </tr></thead>
                <tbody class="divide-b">
                    @forelse ($customer->orders as $order)
                        <tr>
                            <td class="px-5 py-3.5 font-medium">{{ $order->order_number }}</td>
                            <td class="px-5 py-3.5 font-medium">${{ number_format($order->total_price, 2) }}</td>
                            <td class="px-5 py-3.5"><x-pill :status="$order->order_status_label" /></td>
                            <td class="px-5 py-3.5 muted">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3.5"><a href="{{ route('admin.orders.show', $order) }}" class="border bd rounded-lg px-3 py-1.5 text-[12.5px] text-blue-600 font-medium">View</a></td>
                        </tr>
                    @empty
                        <x-empty-state :colspan="5" message="This customer hasn't placed any orders yet." />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
