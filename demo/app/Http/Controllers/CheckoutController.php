<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = [
            ['id' => 3, 'name' => 'Smart Watch Series 8',  'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80', 'price' => 179.99, 'qty' => 1],
            ['id' => 6, 'name' => "Men's Casual Sneakers", 'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=200&q=80', 'price' => 59.99, 'qty' => 1],
            ['id' => 4, 'name' => 'Backpack',               'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200&q=80', 'price' => 39.99, 'qty' => 1],
        ];

        $addresses = [
            ['id' => 1, 'label' => 'Home', 'default' => true,  'name' => 'Nayan Sau', 'line1' => '123 Green Park, Near City Mall', 'city' => 'Kolkata, West Bengal 700001', 'phone' => '+91 98765 43210'],
            ['id' => 2, 'label' => 'Office', 'default' => false, 'name' => 'Nayan Sau', 'line1' => 'Tech Park, Block B, Salt Lake', 'city' => 'Kolkata, West Bengal 700091', 'phone' => '+91 98765 43210'],
        ];

        $taxPct = 8;
        $appliedCoupon = 'WELCOME10';
        $discountPct = 10;

        $subtotal = collect($cartItems)->sum(fn ($i) => $i['price'] * $i['qty']);
        $discount = $subtotal * ($discountPct / 100);
        $tax = ($subtotal - $discount) * ($taxPct / 100);
        $total = $subtotal - $discount + $tax;

        return view('checkout', compact('cartItems', 'addresses', 'taxPct', 'appliedCoupon', 'subtotal', 'discount', 'tax', 'total'));
    }
}
