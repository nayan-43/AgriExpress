<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * GET /admin/coupons
     */
    public function index(Request $request)
    {
        $coupons = Coupon::query()
            ->when($request->q, fn ($q) => $q->where('code', 'like', "%{$request->q}%"))
            ->when($request->status !== null && $request->status !== '', fn ($q) => $q->where('status', $request->status === 'Active'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.coupons.index', compact('coupons'));
    }

    /**
     * GET /admin/coupons/create
     */
    public function create()
    {
        return view('admin.pages.coupons.create', ['coupon' => new Coupon()]);
    }

    /**
     * POST /admin/coupons
     */
    public function store(StoreCouponRequest $request)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        $data['status'] = $request->boolean('status');

        $coupon = Coupon::create($data);

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', "Coupon \"{$coupon->code}\" created.");
    }

    /**
     * GET /admin/coupons/{coupon}/edit
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.pages.coupons.create', compact('coupon'));
    }

    /**
     * PUT/PATCH /admin/coupons/{coupon}
     */
    public function update(StoreCouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        $data['status'] = $request->boolean('status');

        $coupon->update($data);

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', "Coupon \"{$coupon->code}\" updated.");
    }

    /**
     * DELETE /admin/coupons/{coupon}
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with('status', 'Coupon deleted.');
    }
}
