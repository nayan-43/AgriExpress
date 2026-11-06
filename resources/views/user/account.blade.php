@extends('user.layouts.app')

@section('title', 'My Account — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-[240px_1fr] gap-8">
        @include('user.partials.account-sidebar')

        <div>
            @if (session('status'))
                <div class="mb-5 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700"
                    role="status">
                    {{ session('status') }}
                </div>
            @endif
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $showAllOrders ? 'My Orders' : 'My Account' }}</h1>
                    <p class="text-gray-500 text-sm">
                        {{ $showAllOrders ? 'View and manage your orders' : 'Manage your account and orders' }}
                    </p>
                </div>
                @unless ($showAllOrders)
                    <a href="{{ route('account.profile') }}"
                        class="border border-gray-200 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i class="fa-solid fa-pen mr-1"></i> Edit Profile
                    </a>
                @endunless
            </div>

            @unless ($showAllOrders)
                <div class="flex items-center gap-4 border border-gray-100 rounded-lg p-5 mb-6">
                    <span
                        class="w-14 h-14 rounded-full bg-brand-700 text-white flex items-center justify-center text-xl font-semibold">{{ $user['initials'] }}</span>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $user['name'] }} <i
                                class="fa-solid fa-circle-check text-emerald-500 text-sm ml-1"></i></p>
                        <p class="text-sm text-gray-500">{{ $user['email'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="border border-gray-100 rounded-lg p-5 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['orders'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Orders</p>
                    </div>
                    <div class="border border-gray-100 rounded-lg p-5 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['wishlist'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Wishlist Items</p>
                    </div>
                    <div class="border border-gray-100 rounded-lg p-5 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['addresses'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Addresses</p>
                    </div>
                </div>
            @endunless

            <div id="orders" class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">{{ $showAllOrders ? 'My Orders' : 'Recent Orders' }}</h2>
                @unless ($showAllOrders)
                    <a href="{{ route('account.orders') }}" class="text-brand-700 text-sm font-medium hover:underline">View All <i
                            class="fa-solid fa-arrow-right text-xs"></i></a>
                @endunless
            </div>

            <div class="border border-gray-100 rounded-lg overflow-hidden overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium">Order #</th>
                            <th class="text-left px-4 py-3 font-medium">Date</th>
                            <th class="text-left px-4 py-3 font-medium">Status</th>
                            <th class="text-left px-4 py-3 font-medium">Total</th>
                            <th class="text-left px-4 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">#{{ $order['number'] }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $order['date'] }}</td>
                                <td class="px-4 py-3"><span
                                        class="{{ $order['status_classes'] }} text-xs font-medium px-2 py-1 rounded-full">{{ $order['status'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-800">${{ number_format($order['total'], 2) }}</td>
                                <td class="px-4 py-3"><a href="{{ route('order.details', $order['number']) }}"
                                        class="border border-gray-200 rounded-md px-3 py-1 text-xs hover:bg-gray-50">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                    You have not placed any orders yet.
                                    <a href="{{ route('shop') }}" class="text-brand-700 font-medium hover:underline">Start shopping</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
