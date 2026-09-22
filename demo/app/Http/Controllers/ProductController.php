<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, int $id)
    {
        $product = [
            'id'              => $id,
            'name'            => 'Smart Watch Series 8',
            'category'        => 'Electronics',
            'category_slug'   => 'electronics',
            'price'           => 199.99,
            'old_price'       => 249.99,
            'discount_pct'    => 20,
            'rating'          => 5,
            'reviews'         => 18,
            'description'     => 'Stay connected, track your fitness, and get more done with the latest Smart Watch Series 8. Features a stunning display, heart rate monitoring, and long battery life.',
            'long_description'=> "The Smart Watch Series 8 is designed to help you live a healthier, more connected life. With advanced sensors, a sleek design and a crystal-clear display, it's the perfect companion for everyday use.",
            'gallery'         => [
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80',
                'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&q=80',
                'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&q=80',
                'https://images.unsplash.com/photo-1434493907317-a46b5bbe7834?w=800&q=80',
                'https://images.unsplash.com/photo-1544117519-31a4b719223d?w=800&q=80',
            ],
            'colors' => [
                ['name' => 'Black', 'class' => 'bg-gray-900'],
                ['name' => 'Graphite', 'class' => 'bg-gray-400'],
                ['name' => 'Rose', 'class' => 'bg-rose-200'],
            ],
            'specs' => [
                'Display'   => '1.9" AMOLED, Always-On',
                'Battery'   => 'Up to 36 hours',
                'Water Resistance' => '5 ATM',
                'Connectivity' => 'Bluetooth 5.3, Wi-Fi',
                'Compatibility' => 'iOS 15+ / Android 9+',
            ],
        ];

        $related = [
            ['id' => 12, 'name' => 'Wireless Headphones', 'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=500&q=80', 'price' => 89.99],
            ['id' => 9,  'name' => 'Classic Watch',       'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=500&q=80', 'price' => 129.99],
            ['id' => 13, 'name' => 'Bluetooth Speaker',   'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80', 'price' => 59.99],
            ['id' => 14, 'name' => 'Smart Watch Pro',     'image' => 'https://images.unsplash.com/photo-1434493907317-a46b5bbe7834?w=500&q=80', 'price' => 249.99],
        ];

        return view('product', compact('product', 'related'));
    }
}
