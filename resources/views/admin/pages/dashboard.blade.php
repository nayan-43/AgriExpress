@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('active', 'dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        <p class="muted text-sm mt-1">Welcome back, here's what's happening with your store today.</p>
    </div>

    <div class="space-y-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4">
            @foreach ($stats as $s)
                <x-stat-card :label="$s['label']" :value="$s['value']" :change="$s['change']" :icon="$s['icon']" :tone="$s['tone']"
                    :icon-bg="$s['iconBg']" :line-color="$s['line']" />
            @endforeach
        </div>

        <div class="surface border rounded-2xl">
            <div class="flex items-center justify-between p-5 pb-3">
                <h2 class="font-semibold">Recent orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 font-medium">View all <i
                        class="fa-solid fa-arrow-right text-[11px]"></i></a>
            </div>
            <div class="scroll-x">
                <table class="w-full text-sm min-w-205">
                    <thead>
                        <tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                            <th class="px-5 py-3 font-medium">#</th>
                            <th class="px-5 py-3 font-medium">Customer</th>
                            <th class="px-5 py-3 font-medium">Items</th>
                            <th class="px-5 py-3 font-medium">Total</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Payment</th>
                            <th class="px-5 py-3 font-medium">Date</th>
                            <th class="px-5 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-b">
                        @forelse ($recentOrders as $i => $o)
                            <tr>
                                <td class="px-5 py-3.5 font-medium">{{ $o->order_number }}</td>
                                <td class="px-5 py-3.5"><span class="flex items-center gap-2.5"><x-avatar
                                            :name="$o->user->name ?? 'Guest'" :index="$i" />{{ $o->user->name ?? 'Guest' }}</span>
                                </td>
                                <td class="px-5 py-3.5 muted">{{ $o->items_count }}
                                    {{ \Illuminate\Support\Str::plural('item', $o->items_count) }}</td>
                                <td class="px-5 py-3.5 font-medium">${{ number_format($o->total_price, 2) }}</td>
                                <td class="px-5 py-3.5"><x-pill :status="$o->status_label" /></td>
                                <td class="px-5 py-3.5"><x-pill :status="$o->payment_status_label" /></td>
                                <td class="px-5 py-3.5 muted">{{ $o->created_at->format('M j, Y') }}</td>
                                <td class="px-5 py-3.5"><a href="{{ route('admin.orders.show', $o) }}"
                                        class="border bd rounded-lg px-3 py-1.5 text-[12.5px] text-blue-600 font-medium">View</a>
                                </td>
                            </tr>
                        @empty
                            <x-empty-state :colspan="8" message="No orders yet." />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="surface border rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-[15px]">Top selling products</h2><a
                        href="{{ route('admin.products.index') }}" class="text-xs text-blue-600">View all</a>
                </div>
                <ol class="space-y-4 text-sm">
                    @forelse ($topProducts as $k => $p)
                        <li class="flex items-center gap-3">
                            <span class="muted w-4">{{ $k + 1 }}.</span>
                            <x-thumb :src="$p->main_image_url" icon="fa-box" />
                            <span class="flex-1 font-medium truncate">{{ $p->name }}</span>
                            <span class="muted text-[12.5px]">{{ (int) $p->sold }} sold</span>
                        </li>
                    @empty
                        <li class="muted text-[13px]">No sales recorded yet.</li>
                    @endforelse
                </ol>
            </div>
            <div class="surface border rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-[15px]">Recent customers</h2><a
                        href="{{ route('admin.customers.index') }}" class="text-xs text-blue-600">View all</a>
                </div>
                <ul class="space-y-4 text-sm">
                    @forelse ($recentCustomers as $i => $c)
                        <li class="flex items-center gap-3">
                            <x-avatar :name="$c->name" :index="$i" />
                            <span class="min-w-0 flex-1"><span
                                    class="block font-medium truncate">{{ $c->name }}</span><span
                                    class="block muted text-[12px] truncate">{{ $c->email }}</span></span>
                            <span
                                class="muted text-[11.5px] whitespace-nowrap">{{ $c->created_at->format('M j') }}</span>
                        </li>
                    @empty
                        <li class="muted text-[13px]">No customers yet.</li>
                    @endforelse
                </ul>
            </div>
            <div class="surface border rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-[15px]">Popular categories</h2><a
                        href="{{ route('admin.categories.index') }}" class="text-xs text-blue-600">View all</a>
                </div>
                <ul class="space-y-4 text-sm">
                    @forelse ($popularCategories as $c)
                        <li class="flex items-center gap-3">
                            <x-thumb :src="$c->image_url" icon="fa-layer-group" />
                            <span class="flex-1 font-medium truncate">{{ $c->name }}</span>
                            <span class="muted text-[12.5px]">{{ $c->products_count }} products</span>
                        </li>
                    @empty
                        <li class="muted text-[13px]">No categories yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
@endsection