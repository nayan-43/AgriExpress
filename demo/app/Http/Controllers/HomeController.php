<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $heroSlides = [
            [
                'eyebrow'  => 'Modern Living',
                'title'    => 'Discover Your<br>New Favorites',
                'subtitle' => 'Curated products for a more beautiful, comfortable and stylish life.',
                'cta'      => 'Shop Now',
                'bg'       => 'bg-brand-50',
                'image'    => 'https://images.unsplash.com/photo-1567016432779-094069958ea5?w=900&q=80',
            ],
            [
                'eyebrow'  => 'Limited Time',
                'title'    => 'Autumn Style<br>Refresh',
                'subtitle' => 'New season arrivals across fashion, footwear and accessories.',
                'cta'      => 'Explore Collection',
                'bg'       => 'bg-rose-50',
                'image'    => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=900&q=80',
            ],
            [
                'eyebrow'  => 'Tech Essentials',
                'title'    => 'Upgrade Your<br>Everyday Carry',
                'subtitle' => 'Smart watches, headphones and gadgets built for daily life.',
                'cta'      => 'Shop Electronics',
                'bg'       => 'bg-sky-50',
                'image'    => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=900&q=80',
            ],
        ];

        $categories = [
            ['name' => 'Home & Living', 'slug' => 'home-living', 'icon' => 'fa-solid fa-couch',        'bg' => 'bg-emerald-50', 'color' => 'text-emerald-600'],
            ['name' => 'Women',         'slug' => 'women',        'icon' => 'fa-solid fa-shirt',        'bg' => 'bg-rose-50',    'color' => 'text-rose-500'],
            ['name' => 'Men',           'slug' => 'men',          'icon' => 'fa-solid fa-shirt',        'bg' => 'bg-sky-50',     'color' => 'text-sky-500'],
            ['name' => 'Electronics',   'slug' => 'electronics',  'icon' => 'fa-solid fa-mobile-screen','bg' => 'bg-violet-50',  'color' => 'text-violet-500'],
            ['name' => 'Accessories',   'slug' => 'accessories',  'icon' => 'fa-solid fa-clock',        'bg' => 'bg-amber-50',   'color' => 'text-amber-500'],
            ['name' => 'Footwear',      'slug' => 'footwear',     'icon' => 'fa-solid fa-shoe-prints',  'bg' => 'bg-green-50',   'color' => 'text-green-600'],
            ['name' => 'Beauty',        'slug' => 'beauty',       'icon' => 'fa-solid fa-pump-soap',    'bg' => 'bg-red-50',     'color' => 'text-red-400'],
            ['name' => 'Sports',        'slug' => 'sports',       'icon' => 'fa-solid fa-baseball',     'bg' => 'bg-blue-50',    'color' => 'text-blue-500'],
        ];

        $newArrivals = [
            ['id' => 1, 'name' => "Women's Knit Sweater", 'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500&q=80', 'price' => 49.99, 'old_price' => 69.99, 'rating' => 4.5, 'reviews' => 24, 'badge' => 'New', 'badge_color' => 'bg-brand-700', 'colors' => ['bg-amber-200', 'bg-rose-200', 'bg-gray-900']],
            ['id' => 2, 'name' => "Men's Running Shoes",  'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&q=80', 'price' => 79.99, 'old_price' => 99.99, 'rating' => 4, 'reviews' => 18, 'badge' => '-20%', 'badge_color' => 'bg-red-500', 'colors' => ['bg-gray-900', 'bg-blue-800', 'bg-gray-300']],
            ['id' => 3, 'name' => 'Smart Watch Series 8', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&q=80', 'price' => 199.99, 'old_price' => null, 'rating' => 5, 'reviews' => 32, 'badge' => 'New', 'badge_color' => 'bg-brand-700', 'colors' => ['bg-gray-900', 'bg-gray-400', 'bg-white']],
            ['id' => 4, 'name' => 'Travel Backpack',      'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&q=80', 'price' => 59.99, 'old_price' => 69.99, 'rating' => 4, 'reviews' => 16, 'badge' => '-15%', 'badge_color' => 'bg-red-500', 'colors' => ['bg-amber-700', 'bg-gray-900', 'bg-emerald-700']],
            ['id' => 5, 'name' => 'Indoor Plant',         'image' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=500&q=80', 'price' => 29.99, 'old_price' => null, 'rating' => 5, 'reviews' => 12, 'badge' => 'New', 'badge_color' => 'bg-brand-700', 'colors' => ['bg-green-700', 'bg-gray-300']],
        ];

        $bestSellers = [
            ['id' => 6,  'name' => 'Casual Sneakers',      'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=500&q=80', 'price' => 59.99, 'old_price' => 79.99, 'rating' => 5, 'reviews' => 46, 'badge' => '-25%', 'badge_color' => 'bg-red-500'],
            ['id' => 7,  'name' => "Men's Polo T-Shirt",   'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=500&q=80', 'price' => 34.99, 'old_price' => null, 'rating' => 4, 'reviews' => 38, 'badge' => 'Top Rated', 'badge_color' => 'bg-amber-500'],
            ['id' => 8,  'name' => 'Polarized Sunglasses', 'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=500&q=80', 'price' => 44.99, 'old_price' => 49.99, 'rating' => 4, 'reviews' => 27],
            ['id' => 9,  'name' => 'Classic Watch',        'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=500&q=80', 'price' => 129.99, 'old_price' => null, 'rating' => 4, 'reviews' => 21, 'badge' => 'New', 'badge_color' => 'bg-brand-700'],
            ['id' => 10, 'name' => 'Skincare Set',         'image' => 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=500&q=80', 'price' => 42.49, 'old_price' => 49.99, 'rating' => 3.5, 'reviews' => 19, 'badge' => '-15%', 'badge_color' => 'bg-red-500'],
        ];

        $promoBanners = [
            ['eyebrow' => 'Up to 50% Off', 'title' => 'Premium Electronics', 'subtitle' => 'Top brands. Best prices.', 'cta' => 'Shop Electronics', 'slug' => 'electronics', 'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=1600&h=500&fit=crop&q=80'],
            ['eyebrow' => 'Fashion Sale',  'title' => 'Flat 40% Off',        'subtitle' => 'Trendy looks for every season.', 'cta' => 'Shop Now', 'slug' => 'women', 'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1600&h=500&fit=crop&q=80'],
        ];

        $testimonials = [
            ['quote' => "Amazing quality and fast delivery! I'm really happy with my purchase.", 'name' => 'Sarah M.', 'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg'],
            ['quote' => 'Great products, easy checkout and excellent customer service!', 'name' => 'James T.', 'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg'],
            ['quote' => "The best online shopping experience I've had. Highly recommend!", 'name' => 'Priya S.', 'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg'],
        ];

        return view('home', compact('heroSlides', 'categories', 'newArrivals', 'bestSellers', 'promoBanners', 'testimonials'));
    }
}
