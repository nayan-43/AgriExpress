<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Request $request, string $orderNumber)
    {
        $order = [
            'number'         => $orderNumber,
            'placed_at'      => 'Sep 16, 2025',
            'delivered_at'   => 'Sep 18, 2025',
            'payment_method' => 'Razorpay (UPI / Card / Wallet)',
            'payment_status' => 'Paid',
            'timeline' => [
                ['label' => 'Order Placed', 'date' => 'Sep 16', 'icon' => 'fa-check'],
                ['label' => 'Processing',   'date' => 'Sep 16', 'icon' => 'fa-box'],
                ['label' => 'Shipped',      'date' => 'Sep 17', 'icon' => 'fa-truck'],
                ['label' => 'Delivered',    'date' => 'Sep 18', 'icon' => 'fa-check-double'],
            ],
            'items' => [
                ['name' => 'Smart Watch Series 8',  'variant' => 'Color: Black',            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80', 'price' => 179.99, 'qty' => 1],
                ['name' => "Men's Casual Sneakers", 'variant' => 'Size: 42 | Color: White', 'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=200&q=80', 'price' => 59.99, 'qty' => 1],
                ['name' => 'Backpack',               'variant' => 'Color: Green',            'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200&q=80', 'price' => 39.99, 'qty' => 1],
            ],
            'address' => [
                'name'  => 'Nayan Sau',
                'line1' => '123 Green Park, Near City Mall',
                'city'  => 'Kolkata, West Bengal 700001',
                'phone' => '+91 98765 43210',
            ],
        ];

        return view('order-details', compact('order'));
    }
}
