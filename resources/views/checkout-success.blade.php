@extends('user.layouts.app')
@use('App\Models\Order')

@section('title', 'Order Confirmed — AgriExpress')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">

        <div class="text-center mb-10">
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-check text-2xl"></i>
            </div>
            @if ($order && $order->payment_mode === 'cod')
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Order placed</h1>
                <p class="text-gray-500">Pay in cash when it arrives. We've sent a confirmation to your email.</p>
            @else
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment received</h1>
                <p class="text-gray-500">Thanks for your order — we've sent a confirmation to your email.</p>
            @endif
        </div>

        @if ($order)
            <div class="border border-gray-100 rounded-lg p-5 sm:p-6 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-5 mb-5 border-b border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Order number</p>
                        <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Placed on</p>
                        <p class="text-sm text-gray-700">{{ optional($order->placed_at ?? $order->created_at)->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Payment</p>
                        <p class="text-sm text-gray-700">
                            {{ ucfirst($order->payment_mode ?: 'Stripe') }}
                            <span class="ml-1 {{ $order->payment_status === Order::PAYMENT_PAID ? 'text-emerald-600' : 'text-amber-600' }}">
                                <i class="fa-solid {{ $order->payment_status === Order::PAYMENT_PAID ? 'fa-circle-check' : 'fa-clock' }}"></i>
                                {{ $order->payment_status_label }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                        <p class="text-sm text-gray-700">{{ $order->order_status_label }}</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-[1fr_280px] gap-8">
                    <div>
                        <h2 class="font-semibold text-gray-900 mb-3 text-sm">Order items</h2>
                        <div class="divide-y divide-gray-100">
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-4 py-3">
                                    <img src="{{ $item->image_url ?: asset('assets/images/placeholder.png') }}"
                                        class="w-14 h-14 rounded-md object-cover shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $item->product_name }}</p>
                                        @if ($item->variant_name)
                                            <p class="text-xs text-gray-500">{{ $item->variant_name }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right text-sm shrink-0">
                                        <p class="font-medium text-gray-900">${{ number_format($item->unit_price, 2) }}</p>
                                        <p class="text-xs text-gray-400">x{{ $item->quantity }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 border-t border-gray-100 pt-4 mt-4">
                            <div class="flex justify-between"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                            @if ($order->discount > 0)
                                <div class="flex justify-between text-emerald-600">
                                    <span>Discount{{ $order->coupon_code ? " ({$order->coupon_code})" : '' }}</span>
                                    <span>-${{ number_format($order->discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between"><span>Shipping</span><span>{{ $order->shipping > 0 ? '$'.number_format($order->shipping, 2) : 'Free' }}</span></div>
                            <div class="flex justify-between"><span>Tax</span><span>${{ number_format($order->eco_tax, 2) }}</span></div>
                            <div class="flex justify-between font-semibold text-gray-900 border-t border-gray-100 pt-3 mt-1">
                                <span>Total</span><span>${{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <aside class="space-y-4">
                        @if ($order->shippingAddress)
                            <div class="border border-gray-100 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 text-sm">Shipping address</h3>
                                <p class="text-sm text-gray-600">{{ trim($order->shippingAddress->first_name . ' ' . $order->shippingAddress->last_name) }}</p>
                                <p class="text-sm text-gray-600">{{ $order->shippingAddress->address_line_1 }}</p>
                                <p class="text-sm text-gray-600">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                                <p class="text-sm text-gray-600">{{ $order->shippingAddress->phone }}</p>
                            </div>
                        @endif
                        @if ($order->billingAddress)
                            <div class="border border-gray-100 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 text-sm">Billing address</h3>
                                <p class="text-sm text-gray-600">{{ trim($order->billingAddress->first_name . ' ' . $order->billingAddress->last_name) }}</p>
                                <p class="text-sm text-gray-600">{{ $order->billingAddress->address_line_1 }}</p>
                                <p class="text-sm text-gray-600">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}</p>
                            </div>
                        @endif
                    </aside>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('order.details', $order->order_number) }}"
                    class="border border-gray-200 hover:bg-gray-50 text-gray-700 px-5 py-3 rounded-md font-medium text-sm">View order details</a>
                <a href="{{ route('index') }}"
                    class="bg-brand-700 hover:bg-brand-800 text-white px-5 py-3 rounded-md font-medium text-sm">Continue shopping</a>
            </div>
        @else
            {{-- Fallback for the rare case the order couldn't be resolved
                 (e.g. an old bookmarked link with no ?order= param). --}}
            <div class="text-center">
                <p class="text-gray-500 mb-8">Your order is being processed. You can find it in your account.</p>
                <a href="{{ route('account') }}"
                    class="inline-block bg-brand-700 hover:bg-brand-800 text-white px-5 py-3 rounded-md font-medium">View my orders</a>
            </div>
        @endif
    </div>
@endsection
