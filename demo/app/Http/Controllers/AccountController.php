<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = [
            'name'     => 'Nayan Sau',
            'email'    => 'nayan@example.com',
            'initials' => 'NS',
        ];

        $stats = ['orders' => 5, 'wishlist' => 2, 'addresses' => 1];

        $orders = [
            ['number' => 'ORD-1024', 'date' => 'Sep 16, 2025', 'status' => 'Delivered',  'status_classes' => 'bg-emerald-50 text-emerald-600', 'total' => 272.13],
            ['number' => 'ORD-1023', 'date' => 'Sep 15, 2025', 'status' => 'Shipped',    'status_classes' => 'bg-sky-50 text-sky-600',         'total' => 149.99],
            ['number' => 'ORD-1022', 'date' => 'Sep 14, 2025', 'status' => 'Processing', 'status_classes' => 'bg-amber-50 text-amber-600',     'total' => 89.99],
            ['number' => 'ORD-1021', 'date' => 'Sep 12, 2025', 'status' => 'Cancelled',  'status_classes' => 'bg-red-50 text-red-500',         'total' => 59.99],
        ];

        return view('account', compact('user', 'stats', 'orders'));
    }
}
