<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * In a real app this would come from a Cart model / session.
     * Kept as a plain array here to match the static design data.
     */
    protected function cartItems(): array
    {
        return [
            ['id' => 3, 'name' => 'Smart Watch Series 8',   'variant' => 'Color: Black',              'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80', 'price' => 179.99, 'old_price' => 199.99, 'qty' => 1],
            ['id' => 6, 'name' => "Men's Casual Sneakers",  'variant' => 'Size: 42 | Color: White',   'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=200&q=80', 'price' => 59.99,  'old_price' => 79.99,  'qty' => 1],
            ['id' => 4, 'name' => 'Backpack',                'variant' => 'Color: Green',              'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200&q=80', 'price' => 39.99,  'old_price' => 59.99,  'qty' => 1],
        ];
    }

    public function index(Request $request)
    {
        $cartItems = $this->cartItems();
        $discountPct = 10;
        $taxPct = 8;

        $subtotal = collect($cartItems)->sum(fn ($i) => $i['price'] * $i['qty']);
        $discount = $subtotal * ($discountPct / 100);
        $tax = ($subtotal - $discount) * ($taxPct / 100);
        $total = $subtotal - $discount + $tax;
        $appliedCoupon = 'WELCOME10';

        return view('cart', compact('cartItems', 'discountPct', 'taxPct', 'subtotal', 'discount', 'tax', 'total', 'appliedCoupon'));
    }

    public function add(Request $request, int $productId)
    {
        $qty = (int) $request->input('qty', 1);
        // TODO: persist to session/database cart.
        return redirect()->route('cart')->with('status', "Added to cart ({$qty})");
    }

    public function remove(Request $request, int $productId)
    {
        // TODO: remove line item from session/database cart.
        return redirect()->route('cart')->with('status', 'Item removed');
    }

    public function reorder(Request $request, string $orderNumber)
    {
        // TODO: copy items from the given order back into the cart.
        return redirect()->route('cart')->with('status', "Items from order #{$orderNumber} added to cart");
    }
}
