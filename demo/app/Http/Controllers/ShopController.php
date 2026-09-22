<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = [
            ['id' => 6,  'name' => "Men's Casual Sneakers", 'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=500&q=80', 'price' => 59.99, 'old_price' => 79.99, 'rating' => 5, 'reviews' => 46, 'badge' => 'New', 'badge_color' => 'bg-brand-700'],
            ['id' => 11, 'name' => "Women's Handbag",       'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500&q=80', 'price' => 49.99, 'old_price' => 69.99, 'rating' => 4, 'reviews' => 32, 'badge' => '-20%', 'badge_color' => 'bg-red-500'],
            ['id' => 3,  'name' => 'Smart Watch Series 8',  'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&q=80', 'price' => 199.99, 'old_price' => 249.99, 'rating' => 5, 'reviews' => 18, 'badge' => 'Best Seller', 'badge_color' => 'bg-amber-500'],
            ['id' => 12, 'name' => 'Wireless Headphones',   'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=500&q=80', 'price' => 89.99, 'old_price' => 119.99, 'rating' => 4, 'reviews' => 27],
            ['id' => 4,  'name' => 'Backpack',               'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&q=80', 'price' => 39.99, 'old_price' => 49.99, 'rating' => 4, 'reviews' => 14, 'badge' => '-5%', 'badge_color' => 'bg-red-500'],
            ['id' => 8,  'name' => 'Sunglasses',             'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=500&q=80', 'price' => 24.99, 'old_price' => null, 'rating' => 4, 'reviews' => 21],
            ['id' => 2,  'name' => 'Running Shoes',          'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&q=80', 'price' => 69.99, 'old_price' => 89.99, 'rating' => 4, 'reviews' => 38, 'badge' => '-10%', 'badge_color' => 'bg-red-500'],
            ['id' => 10, 'name' => 'Skincare Set',           'image' => 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=500&q=80', 'price' => 20.99, 'old_price' => 29.99, 'rating' => 3, 'reviews' => 19, 'badge' => '-20%', 'badge_color' => 'bg-red-500'],
            ['id' => 1,  'name' => "Women's Knit Sweater",   'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500&q=80', 'price' => 49.99, 'old_price' => 69.99, 'rating' => 4.5, 'reviews' => 24, 'badge' => 'New', 'badge_color' => 'bg-brand-700'],
        ];

        $filterCategories = [
            ['name' => 'Men', 'slug' => 'men', 'count' => 32],
            ['name' => 'Women', 'slug' => 'women', 'count' => 48],
            ['name' => 'Electronics', 'slug' => 'electronics', 'count' => 18],
            ['name' => 'Home & Living', 'slug' => 'home-living', 'count' => 12],
            ['name' => 'Beauty', 'slug' => 'beauty', 'count' => 8],
            ['name' => 'Sports', 'slug' => 'sports', 'count' => 6],
        ];

        $brands = [
            ['name' => 'Nike', 'count' => 12],
            ['name' => 'Adidas', 'count' => 10],
            ['name' => 'Apple', 'count' => 8],
            ['name' => 'Samsung', 'count' => 6],
        ];

        $category = $request->query('category');
        $totalCount = 124;

        return view('shop', compact('products', 'filterCategories', 'brands', 'category', 'totalCount'));
    }
}
