<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected function allPosts(): array
    {
        return [
            [
                'slug' => '10-smart-shopping-tips-to-save-more-in-2025',
                'title' => '10 Smart Shopping Tips to Save More in 2025',
                'excerpt' => 'Discover simple and effective ways to save money while shopping online. These tips will help you get the best deals.',
                'image' => 'https://images.unsplash.com/photo-1556909212-d5b604d0c90d?w=600&q=80',
                'tag' => 'Shopping Tips', 'tag_slug' => 'shopping-tips', 'tag_color' => 'bg-brand-700',
                'author' => 'Emily Carter', 'author_avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'date' => 'Sep 16, 2025', 'read_time' => '5 min read',
            ],
            [
                'slug' => 'top-5-smartwatches-for-your-active-lifestyle',
                'title' => 'Top 5 Smartwatches for Your Active Lifestyle',
                'excerpt' => 'Stay connected, track your health and live smarter with the best smartwatches on the market today.',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
                'tag' => 'Trends', 'tag_slug' => 'trends', 'tag_color' => 'bg-sky-600',
                'author' => 'James Miller', 'author_avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'date' => 'Sep 14, 2025', 'read_time' => '6 min read',
            ],
            [
                'slug' => 'how-to-create-a-cozy-home-on-a-budget',
                'title' => 'How to Create a Cozy Home on a Budget',
                'excerpt' => 'Small changes can make a big difference. Here are simple ideas to turn your space into a cozy, stylish home.',
                'image' => 'https://images.unsplash.com/photo-1567016432779-094069958ea5?w=600&q=80',
                'tag' => 'Lifestyle', 'tag_slug' => 'lifestyle', 'tag_color' => 'bg-amber-500',
                'author' => 'Sophia Davis', 'author_avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'date' => 'Sep 11, 2025', 'read_time' => '4 min read',
            ],
            [
                'slug' => 'how-to-choose-the-perfect-running-shoes',
                'title' => 'How to Choose the Perfect Running Shoes',
                'excerpt' => 'Find the right fit, comfort and support for your running goals with our detailed guide.',
                'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=600&q=80',
                'tag' => 'Product Guides', 'tag_slug' => 'product-guides', 'tag_color' => 'bg-violet-600',
                'author' => 'Daniel Wilson', 'author_avatar' => 'https://randomuser.me/api/portraits/men/51.jpg',
                'date' => 'Sep 8, 2025', 'read_time' => '7 min read',
            ],
            [
                'slug' => 'the-best-skincare-products-for-glowing-skin',
                'title' => 'The Best Skincare Products for Glowing Skin',
                'excerpt' => 'Get that natural glow with our top picks for skin care products that actually work.',
                'image' => 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=600&q=80',
                'tag' => 'Trends', 'tag_slug' => 'trends', 'tag_color' => 'bg-sky-600',
                'author' => 'Olivia Brown', 'author_avatar' => 'https://randomuser.me/api/portraits/women/12.jpg',
                'date' => 'Sep 5, 2025', 'read_time' => '5 min read',
            ],
            [
                'slug' => 'travel-essentials-you-cant-leave-home-without',
                'title' => "Travel Essentials You Can't Leave Home Without",
                'excerpt' => 'Pack smarter and travel better with these must-have essentials for your next trip.',
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
                'tag' => 'Shopping Tips', 'tag_slug' => 'shopping-tips', 'tag_color' => 'bg-brand-700',
                'author' => 'Sophia Davis', 'author_avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'date' => 'Sep 2, 2025', 'read_time' => '3 min read',
            ],
        ];
    }

    public function index(Request $request)
    {
        $blogCategories = [
            ['name' => 'All Posts',      'slug' => null],
            ['name' => 'Shopping Tips',  'slug' => 'shopping-tips'],
            ['name' => 'Trends',         'slug' => 'trends'],
            ['name' => 'Lifestyle',      'slug' => 'lifestyle'],
            ['name' => 'Product Guides', 'slug' => 'product-guides'],
        ];

        $activeCategory = $request->query('category');
        $search = $request->query('q');

        $posts = collect($this->allPosts())
            ->when($activeCategory, fn ($c) => $c->where('tag_slug', $activeCategory))
            ->when($search, fn ($c) => $c->filter(
                fn ($p) => str_contains(strtolower($p['title']), strtolower($search))
                        || str_contains(strtolower($p['excerpt']), strtolower($search))
            ))
            ->values()
            ->all();

        $currentPage = (int) $request->query('page', 1);

        return view('blog', compact('posts', 'blogCategories', 'activeCategory', 'currentPage'));
    }

    public function show(Request $request, string $slug)
    {
        $post = collect($this->allPosts())->firstWhere('slug', $slug);

        abort_if(!$post, 404);

        $related = collect($this->allPosts())
            ->where('slug', '!=', $slug)
            ->take(3)
            ->values()
            ->all();

        return view('blog-show', compact('post', 'related'));
    }
}
