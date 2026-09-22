<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * GET /admin — overview stats, sales chart, recent activity.
     */
    public function index(Request $request)
    {
        $weekStart = Carbon::now()->startOfWeek();
        $lastWeekStart = $weekStart->copy()->subWeek();

        $totalSales = Order::where('payment_status', Order::PAYMENT_PAID)->sum('total_price');
        $totalOrders = Order::count();
        $totalCustomers = User::customers()->count();
        $totalProducts = Product::count();

        $stats = [
            ['label' => 'Total sales', 'value' => '$'.number_format($totalSales, 2), 'change' => $this->weekOverWeek(Order::query(), 'total_price'), 'icon' => 'fa-cart-shopping', 'tone' => 'bg-blue-50', 'iconBg' => 'bg-blue-500', 'line' => '#3b82f6'],
            ['label' => 'Total orders', 'value' => number_format($totalOrders), 'change' => $this->weekOverWeekCount(Order::query()), 'icon' => 'fa-box', 'tone' => 'bg-emerald-50', 'iconBg' => 'bg-emerald-500', 'line' => '#10b981'],
            ['label' => 'Total customers', 'value' => number_format($totalCustomers), 'change' => $this->weekOverWeekCount(User::customers()), 'icon' => 'fa-user', 'tone' => 'bg-violet-50', 'iconBg' => 'bg-violet-500', 'line' => '#8b5cf6'],
            ['label' => 'Total products', 'value' => number_format($totalProducts), 'change' => '—', 'icon' => 'fa-tag', 'tone' => 'bg-amber-50', 'iconBg' => 'bg-amber-500', 'line' => '#f59e0b'],
        ];

        // Category share of the last 30 days of paid orders.
        $categorySales = Category::withSum(['products as order_total' => function ($q) {
            $q->join('order_items', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.created_at', '>=', now()->subDays(30));
        }], 'order_items.total_price')
            ->orderByDesc('order_total')
            ->take(6)
            ->get();

        $categoryTotal = max($categorySales->sum('order_total'), 1);
        $palette = ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#cbd5e1'];
        $categorySales = $categorySales->values()->map(fn ($c, $i) => [
            'name' => $c->name,
            'pct' => round(($c->order_total ?? 0) / $categoryTotal * 100),
            'color' => $palette[$i] ?? '#cbd5e1',
        ]);

        $salesOverview = $this->last7DaysSales();

        $recentOrders = Order::with('user')->withCount('items')->latest()->take(5)->get();
        $topProducts = Product::withCount(['orderItems as sold' => fn ($q) => $q->select(DB::raw('sum(quantity)'))])
            ->orderByDesc('sold')->take(3)->get();
        $recentCustomers = User::customers()->latest()->take(3)->get();
        $popularCategories = Category::withCount('products')->orderByDesc('products_count')->take(4)->get();
        $lowStockProducts = Product::lowStock()->orderBy('stock')->take(5)->get();

        return view('admin.pages.dashboard', compact(
            'stats', 'categorySales', 'salesOverview', 'recentOrders',
            'topProducts', 'recentCustomers', 'popularCategories', 'lowStockProducts'
        ));
    }

    protected function last7DaysSales(): array
    {
        $labels = [];
        $thisWeek = [];
        $lastWeek = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $labels[] = $day->format('M j');
            $thisWeek[] = (float) Order::whereDate('created_at', $day)->sum('total_price');
            $lastWeek[] = (float) Order::whereDate('created_at', $day->copy()->subWeek())->sum('total_price');
        }

        return compact('labels', 'thisWeek', 'lastWeek');
    }

    protected function weekOverWeek($query, string $column): string
    {
        $thisWeek = (clone $query)->where('created_at', '>=', now()->startOfWeek())->sum($column);
        $lastWeek = (clone $query)->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->sum($column);

        if ($lastWeek == 0) {
            return $thisWeek > 0 ? '100%' : '0%';
        }

        return number_format((($thisWeek - $lastWeek) / $lastWeek) * 100, 1).'%';
    }

    protected function weekOverWeekCount($query): string
    {
        $thisWeek = (clone $query)->where('created_at', '>=', now()->startOfWeek())->count();
        $lastWeek = (clone $query)->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        if ($lastWeek == 0) {
            return $thisWeek > 0 ? '100%' : '0%';
        }

        return number_format((($thisWeek - $lastWeek) / $lastWeek) * 100, 1).'%';
    }
}
