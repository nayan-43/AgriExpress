{{--
  admin/orders/show.blade.php
  Populated by App\Http\Controllers\Admin\OrderController@show.
--}}
@extends('admin.layouts.admin')

@section('title', 'Order '.$order->order_number)
@section('active', 'orders')

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div>
        <h1 class="text-2xl font-bold">Order details</h1>
        <p class="text-sm mt-1">
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600">Orders</a>
            <i class="fa-solid fa-chevron-right text-[9px] muted mx-1"></i>
            <span class="muted">{{ $order->order_number }}</span>
        </p>
    </div>
    <div class="flex gap-2 items-center">
        <x-pill :status="$order->order_status_label" />
        <button class="surface border rounded-lg px-4 h-10 text-sm" onclick="window.print()"><i class="fa-solid fa-print mr-2 muted"></i>Print</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-5">
    <div class="surface border rounded-2xl p-5 space-y-5 h-fit">
        <div>
            <h2 class="font-semibold">{{ $order->order_number }}</h2>
            <p class="text-[12.5px] muted mt-0.5">Placed on {{ ($order->placed_at ?? $order->created_at)->format('M j, Y, g:i A') }}</p>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t bd">
            <x-avatar :name="$order->user->name ?? 'Guest'" />
            <div class="text-sm">
                <p class="font-medium">{{ $order->user->name ?? 'Guest checkout' }}</p>
                @if($order->user)
                    <p class="muted text-[12.5px]">{{ $order->user->email }}</p>
                    <p class="muted text-[12.5px]">{{ $order->user->phone }}</p>
                @endif
            </div>
        </div>

        @if($order->shippingAddress)
            <div class="pt-4 border-t bd text-sm">
                <p class="font-medium mb-1.5">Shipping address</p>
                <p class="muted leading-relaxed text-[13px]">
                    {{ $order->shippingAddress->full_name }}<br>
                    {{ $order->shippingAddress->address_line_1 }}@if($order->shippingAddress->address_line_2), {{ $order->shippingAddress->address_line_2 }}@endif<br>
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}<br>
                    {{ $order->shippingAddress->phone }}
                </p>
            </div>
        @endif

        <div class="pt-4 border-t bd text-sm">
            <p class="font-medium mb-1.5">Payment</p>
            <p class="muted text-[13px]">{{ $order->payment_mode ?? 'Not specified' }} &middot; <x-pill :status="$order->payment_status_label" /></p>
        </div>

        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="pt-4 border-t bd">
            @csrf
            @method('PATCH')
            <label for="order_status" class="block mb-1.5 font-medium text-sm">Update status</label>
            <div class="flex gap-2">
                <select id="order_status" name="order_status" class="flex-1 border bd rounded-lg px-3 h-10 text-sm">
                    @foreach (\App\Models\Order::STATUS_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected($order->order_status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 text-sm font-medium">Save</button>
            </div>
        </form>

        <form action="{{ route('admin.orders.updatePaymentStatus', $order) }}" method="POST" class="pt-4 border-t bd">
            @csrf
            @method('PATCH')
            <label for="payment_status" class="block mb-1.5 font-medium text-sm">Update payment status</label>
            <div class="flex gap-2">
                <select id="payment_status" name="payment_status" class="flex-1 border bd rounded-lg px-3 h-10 text-sm">
                    @foreach (\App\Models\Order::PAYMENT_STATUS_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected($order->payment_status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 text-sm font-medium">Save</button>
            </div>
            <p class="text-[11.5px] muted mt-1.5">
                Manual override — Stripe orders update this automatically once the webhook confirms payment.
                Use this for COD orders, refunds, or fixing a payment that didn't sync.
            </p>
        </form>
    </div>

    <div class="space-y-5">
        <div class="surface border rounded-2xl p-5">
            <h2 class="font-semibold mb-4">Order items</h2>
            <div class="scroll-x">
                <table class="w-full text-sm min-w-130">
                    <thead><tr class="text-left muted text-[12.5px] border-b bd">
                        <th class="pb-3 font-medium">Product</th><th class="pb-3 font-medium">Qty</th>
                        <th class="pb-3 font-medium">Price</th><th class="pb-3 font-medium text-right">Subtotal</th>
                    </tr></thead>
                    <tbody class="divide-b">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="py-3.5">
                                    <span class="flex items-center gap-3">
                                        <x-thumb :src="$item->image_url" icon="fa-box" size="w-10 h-10" />
                                        <span><span class="block font-medium">{{ $item->product_name }}</span><span class="block muted text-[12px]">{{ $item->variant_name ?? $item->sku }}</span></span>
                                    </span>
                                </td>
                                <td class="py-3.5">{{ $item->quantity }}</td>
                                <td class="py-3.5">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3.5 text-right font-medium">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5 ml-auto w-full sm:w-75 text-sm space-y-2.5">
                <div class="flex justify-between"><span class="muted">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span class="muted">Discount</span><span class="text-red-500">-${{ number_format($order->discount, 2) }}</span></div>
                @if($order->eco_tax > 0)
                    <div class="flex justify-between"><span class="muted">Eco tax</span><span>${{ number_format($order->eco_tax, 2) }}</span></div>
                @endif
                <div class="flex justify-between"><span class="muted">Shipping</span><span>${{ number_format($order->shipping, 2) }}</span></div>
                <div class="flex justify-between font-semibold text-base pt-3 border-t bd"><span>Total</span><span>${{ number_format($order->total_price, 2) }}</span></div>
                @if($order->coupon_code)
                    <p class="text-[11.5px] muted">Coupon applied: <span class="font-medium">{{ $order->coupon_code }}</span></p>
                @endif
            </div>
        </div>

        @if($order->customer_note)
            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-2">Customer note</h2>
                <p class="text-sm muted">{{ $order->customer_note }}</p>
            </div>
        @endif

        @if($order->payments->isNotEmpty())
            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-3">Payment history</h2>
                <ul class="divide-b text-sm">
                    @foreach ($order->payments as $payment)
                        <li class="py-2.5 flex items-center justify-between">
                            <span>{{ $payment->payment_method }} &middot; <span class="muted">{{ $payment->transaction_id ?? '—' }}</span></span>
                            <span class="font-medium">${{ number_format($payment->amount, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection
