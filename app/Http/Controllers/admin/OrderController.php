<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * GET /admin/orders
     */
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'All');
        $statusMap = array_flip(Order::STATUS_LABELS); // 'Processing' => 2, etc.

        $orders = Order::with('user')
            ->when($statusFilter !== 'All', fn ($q) => $q->where('order_status', $statusMap[$statusFilter] ?? 0))
            ->when($request->q, fn ($q) => $q->where(fn ($w) => $w
                ->where('order_number', 'like', "%{$request->q}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->q}%"))))
            ->latest()
            ->paginate(16)
            ->withQueryString();

        $tabs = ['All' => Order::count()];
        foreach (Order::STATUS_LABELS as $value => $label) {
            $tabs[$label] = Order::where('order_status', $value)->count();
        }

        return view('admin.pages.orders.index', compact('orders', 'tabs'));
    }

    /**
     * GET /admin/orders/{order}
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'shippingAddress', 'payments']);

        return view('admin.pages.orders.show', compact('order'));
    }

    /**
     * PATCH /admin/orders/{order}/status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => ['required', Rule::in(array_keys(Order::STATUS_LABELS))],
        ]);

        $order->update($validated);

        return back()->with('status', 'Order status updated to '.Order::STATUS_LABELS[$validated['order_status']].'.');
    }

    /**
     * PATCH /admin/orders/{order}/payment-status
     *
     * Manual override for cases the Stripe webhook doesn't cover — COD
     * orders (nothing ever calls Stripe for these), refunds initiated
     * outside the app, or fixing an order whose webhook never arrived.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', Rule::in(array_keys(Order::PAYMENT_STATUS_LABELS))],
        ]);

        $order->update($validated);

        // Mirror the change onto the order's payment record(s) so the
        // "Payment history" panel on this page stays consistent with the
        // status shown at the top, instead of only the Order row changing.
        $order->payments()->latest()->first()?->update([
            'status' => match ((int) $validated['payment_status']) {
                Order::PAYMENT_PAID => 'paid',
                Order::PAYMENT_REFUNDED => 'refunded',
                Order::PAYMENT_FAILED => 'failed',
                default => 'pending',
            },
        ]);

        return back()->with('status', 'Payment status updated to '.Order::PAYMENT_STATUS_LABELS[$validated['payment_status']].'.');
    }
}
