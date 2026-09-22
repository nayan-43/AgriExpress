{{--
  admin/dashboard.blade.php
  Populated by App\Http\Controllers\Admin\DashboardController@index.
  Chart.js is loaded (@push('head')) and initialised (@push('scripts'))
  only on this page — nothing dashboard-specific lives in the shared
  public/js/admin.js.
--}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('active', 'dashboard')

@section('content')
<div class="flex flex-wrap items-end gap-4 justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        <p class="muted text-sm mt-1">Welcome back, here's what's happening with your store today.</p>
    </div>
    <button class="flex items-center gap-3 surface border rounded-xl px-4 h-11 text-sm">
        <i class="fa-regular fa-calendar text-blue-600"></i> {{ now()->subDays(6)->format('M j') }} &ndash; {{ now()->format('M j, Y') }} <i class="fa-solid fa-chevron-down text-[10px] muted"></i>
    </button>
</div>

<div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-5">
    <div class="space-y-5 min-w-0">

        <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4">
            @foreach ($stats as $s)
                <x-stat-card :label="$s['label']" :value="$s['value']" :change="$s['change']" :icon="$s['icon']"
                             :tone="$s['tone']" :icon-bg="$s['iconBg']" :line-color="$s['line']" />
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="surface border rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold">Sales overview</h2>
                    <div class="flex items-center gap-4 text-xs muted">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-[7px] text-blue-600"></i>This week</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-[7px] text-slate-400"></i>Last week</span>
                    </div>
                </div>
                <div class="h-[230px]"><canvas id="salesChart"></canvas></div>
            </div>

            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-4">Sales by category</h2>
                <div class="flex flex-col sm:flex-row items-center gap-5">
                    <div class="relative w-[190px] h-[190px] shrink-0">
                        <canvas id="catChart"></canvas>
                        <div class="absolute inset-0 grid place-items-center pointer-events-none">
                            <div class="text-center"><p class="font-bold text-lg">Last 30d</p><p class="text-[11px] muted">By category</p></div>
                        </div>
                    </div>
                    <ul class="flex-1 w-full space-y-3 text-sm">
                        @forelse ($categorySales as $c)
                            <li class="flex items-center gap-2.5">
                                <i class="fa-solid fa-circle text-[8px]" style="color:{{ $c['color'] }}"></i>
                                <span class="flex-1">{{ $c['name'] }}</span>
                                <span class="font-semibold">{{ $c['pct'] }}%</span>
                            </li>
                        @empty
                            <li class="muted text-[13px]">No sales in the last 30 days yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="surface border rounded-2xl">
            <div class="flex items-center justify-between p-5 pb-3">
                <h2 class="font-semibold">Recent orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 font-medium">View all <i class="fa-solid fa-arrow-right text-[11px]"></i></a>
            </div>
            <div class="scroll-x">
                <table class="w-full text-sm min-w-[820px]">
                    <thead><tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                        <th class="px-5 py-3 font-medium">#</th><th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Items</th><th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Payment</th>
                        <th class="px-5 py-3 font-medium">Date</th><th class="px-5 py-3 font-medium">Action</th>
                    </tr></thead>
                    <tbody class="divide-b">
                        @forelse ($recentOrders as $i => $o)
                            <tr>
                                <td class="px-5 py-3.5 font-medium">{{ $o->order_number }}</td>
                                <td class="px-5 py-3.5"><span class="flex items-center gap-2.5"><x-avatar :name="$o->user->name ?? 'Guest'" :index="$i" />{{ $o->user->name ?? 'Guest' }}</span></td>
                                <td class="px-5 py-3.5 muted">{{ $o->items_count }} {{ \Illuminate\Support\Str::plural('item', $o->items_count) }}</td>
                                <td class="px-5 py-3.5 font-medium">${{ number_format($o->total_price, 2) }}</td>
                                <td class="px-5 py-3.5"><x-pill :status="$o->status_label" /></td>
                                <td class="px-5 py-3.5"><x-pill :status="$o->payment_status_label" /></td>
                                <td class="px-5 py-3.5 muted">{{ $o->created_at->format('M j, Y') }}</td>
                                <td class="px-5 py-3.5"><a href="{{ route('admin.orders.show', $o) }}" class="border bd rounded-lg px-3 py-1.5 text-[12.5px] text-blue-600 font-medium">View</a></td>
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
                <div class="flex items-center justify-between mb-4"><h2 class="font-semibold text-[15px]">Top selling products</h2><a href="{{ route('admin.products.index') }}" class="text-xs text-blue-600">View all</a></div>
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
                <div class="flex items-center justify-between mb-4"><h2 class="font-semibold text-[15px]">Recent customers</h2><a href="{{ route('admin.customers.index') }}" class="text-xs text-blue-600">View all</a></div>
                <ul class="space-y-4 text-sm">
                    @forelse ($recentCustomers as $i => $c)
                        <li class="flex items-center gap-3">
                            <x-avatar :name="$c->name" :index="$i" />
                            <span class="min-w-0 flex-1"><span class="block font-medium truncate">{{ $c->name }}</span><span class="block muted text-[12px] truncate">{{ $c->email }}</span></span>
                            <span class="muted text-[11.5px] whitespace-nowrap">{{ $c->created_at->format('M j') }}</span>
                        </li>
                    @empty
                        <li class="muted text-[13px]">No customers yet.</li>
                    @endforelse
                </ul>
            </div>
            <div class="surface border rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4"><h2 class="font-semibold text-[15px]">Popular categories</h2><a href="{{ route('admin.categories.index') }}" class="text-xs text-blue-600">View all</a></div>
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

    <div class="space-y-5">
        <div class="surface border rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4"><h2 class="font-semibold text-[15px]">Low stock products</h2><a href="{{ route('admin.products.index') }}" class="text-xs text-blue-600">View all</a></div>
            <ul class="space-y-4 text-sm">
                @forelse ($lowStockProducts as $p)
                    @php $width = min(100, round(($p->stock / 30) * 100)).'%'; $bar = $p->stock <= 5 ? 'bg-red-500' : 'bg-amber-500'; @endphp
                    <li class="flex items-center gap-3">
                        <x-thumb :src="$p->main_image_url" icon="fa-box" size="w-10 h-10" />
                        <span class="flex-1 min-w-0">
                            <span class="block font-medium text-[13.5px] truncate">{{ $p->name }}</span>
                            <span class="block muted text-[12px]">Stock: {{ $p->stock }}</span>
                            <span class="block h-1.5 rounded-full mt-1.5" style="background:var(--border)">
                                <span class="block h-1.5 rounded-full {{ $bar }}" style="width:{{ $width }}"></span>
                            </span>
                        </span>
                    </li>
                @empty
                    <li class="muted text-[13px]">Nothing running low right now.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-2xl p-5 text-white" style="background:linear-gradient(135deg,#111d40,#1d2c59)">
            <p class="font-semibold">Manage your store anywhere</p>
            <p class="text-[12.5px] text-slate-300 mt-1.5 leading-relaxed">Use our mobile app to manage orders, products and customers on the go.</p>
            <button class="mt-4 bg-blue-600 hover:bg-blue-500 rounded-lg px-4 py-2 text-sm font-medium"><i class="fa-solid fa-mobile-screen mr-2"></i>Download app</button>
        </div>
    </div>
</div>
@endsection

@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endpush

@push('scripts')
<script>
// Dashboard-only: draws the two charts from the data the controller passed
// in. Lives here (not in the shared admin.js) since no other page uses it.
(function () {
    const grid = getComputedStyle(document.documentElement).getPropertyValue('--border').trim();
    const tick = getComputedStyle(document.documentElement).getPropertyValue('--muted').trim();

    const salesLabels = @json($salesOverview['labels']);
    const salesThisWeek = @json($salesOverview['thisWeek']);
    const salesLastWeek = @json($salesOverview['lastWeek']);
    const catLabels = @json(collect($categorySales)->pluck('name'));
    const catValues = @json(collect($categorySales)->pluck('pct'));
    const catColors = @json(collect($categorySales)->pluck('color'));

    let salesChart, catChart;

    function build() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;
        const g = ctx.getContext('2d').createLinearGradient(0, 0, 0, 220);
        g.addColorStop(0, 'rgba(59,130,246,.28)');
        g.addColorStop(1, 'rgba(59,130,246,0)');

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [
                    { label: 'This week', data: salesThisWeek, borderColor: '#2563eb', backgroundColor: g, fill: true, tension: .4, pointRadius: 4, pointBackgroundColor: '#2563eb', borderWidth: 2.5 },
                    { label: 'Last week', data: salesLastWeek, borderColor: '#94a3b8', borderDash: [5, 5], fill: false, tension: .4, pointRadius: 3, pointBackgroundColor: '#94a3b8', borderWidth: 2 },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { color: tick, callback: v => '$' + v.toLocaleString() }, grid: { color: grid } },
                    x: { ticks: { color: tick }, grid: { display: false } },
                },
            },
        });

        catChart = new Chart(document.getElementById('catChart'), {
            type: 'doughnut',
            data: { labels: catLabels, datasets: [{ data: catValues, backgroundColor: catColors, borderWidth: 0, spacing: 2 }] },
            options: { cutout: '72%', plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false },
        });
    }

    build();

    // Re-draw with theme-correct grid/tick colors when the topbar's theme toggle fires.
    document.addEventListener('theme-changed', () => {
        salesChart?.destroy();
        catChart?.destroy();
        build();
    });
})();
</script>
@endpush
