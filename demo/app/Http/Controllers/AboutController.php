<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        $valueProps = [
            ['icon' => 'fa-solid fa-truck-fast',   'title' => 'Free Shipping',  'subtitle' => 'On orders over $50'],
            ['icon' => 'fa-solid fa-shield-halved','title' => 'Secure Payments','subtitle' => '100% protected'],
            ['icon' => 'fa-solid fa-headset',      'title' => '24/7 Support',   'subtitle' => "We're here to help"],
            ['icon' => 'fa-solid fa-leaf',         'title' => 'Sustainable',    'subtitle' => 'Eco-friendly products'],
        ];

        $mission = [
            'image'   => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&q=80',
            'heading' => 'Quality Products<br>for a Better Life',
            'body'    => 'Our mission is to make high-quality, stylish and affordable products accessible to everyone. We care about our customers, our community and the planet. Every purchase you make supports a more sustainable future.',
            'stats'   => [
                ['value' => '10K+',  'label' => 'Happy Customers'],
                ['value' => '500+',  'label' => 'Products'],
                ['value' => '4.8',   'label' => 'Average Rating', 'star' => true],
            ],
        ];

        $differentiators = [
            ['icon' => 'fa-solid fa-hand-holding-heart', 'title' => 'Curated Selection', 'subtitle' => 'Handpicked products for the best quality.'],
            ['icon' => 'fa-solid fa-tags',               'title' => 'Great Value',       'subtitle' => 'Premium quality at affordable prices.'],
            ['icon' => 'fa-solid fa-box-open',           'title' => 'Fast Delivery',     'subtitle' => 'Get your orders on time, every time.'],
            ['icon' => 'fa-solid fa-heart',              'title' => 'Customer First',    'subtitle' => 'Your happiness is our priority.'],
        ];

        return view('about', compact('valueProps', 'mission', 'differentiators'));
    }
}
