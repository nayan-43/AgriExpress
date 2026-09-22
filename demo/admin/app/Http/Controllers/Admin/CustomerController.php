<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * GET /admin/customers
     */
    public function index(Request $request)
    {
        $customers = User::customers()
            ->withCount('orders')
            ->when($request->q, fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$request->q}%")
                ->orWhere('email', 'like', "%{$request->q}%")
                ->orWhere('phone', 'like', "%{$request->q}%")))
            ->when($request->status !== null && $request->status !== '', fn ($q) => $q->where('status', $request->status === 'Active'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * GET /admin/customers/{customer}
     */
    public function show(User $customer)
    {
        $customer->load(['addresses', 'orders' => fn ($q) => $q->latest()->take(10)]);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * PATCH /admin/customers/{customer}/status — block/unblock a customer.
     */
    public function updateStatus(Request $request, User $customer)
    {
        $customer->update(['status' => $request->boolean('status')]);

        return back()->with('status', $customer->status ? 'Customer unblocked.' : 'Customer blocked.');
    }
}
