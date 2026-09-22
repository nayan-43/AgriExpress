<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SiteController extends Controller
{
    public function newsletterSubscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return response()->json(['message' => 'Thank you for subscribing to our newsletter!']);
    }

    public function toggleWishlist(Request $request, Product $product)
    {
        abort_unless($product->status, 404);
        $wishlist = Wishlist::firstOrCreate(['user_id' => auth('web')->id()]);
        $item = $wishlist->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->delete();
            $message = 'Removed from your wishlist.';
        } else {
            $wishlist->items()->create(['product_id' => $product->id]);
            $message = 'Added to your wishlist.';
        }

        return back()->with('status', $message);
    }

    public function profile()
    {
        return view('user.profile', ['account' => auth('web')->user()]);
    }

    public function updateProfile(Request $request)
    {
        $account = auth('web')->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $account->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $account->fill(collect($data)->only(['name', 'email', 'phone'])->all());
        if (!empty($data['password'])) {
            $account->password = Hash::make($data['password']);
        }
        $account->save();

        return back()->with('status', 'Account details updated.');
    }

    public function addresses()
    {
        return view('user.addresses', ['addresses' => auth('web')->user()->addresses()->latest()->get()]);
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'max:30'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'is_default' => ['boolean'],
        ]);
        $account = auth('web')->user();
        if (!empty($data['is_default'])) {
            $account->addresses()->update(['is_default' => false]);
        }
        $account->addresses()->create($data);

        return back()->with('status', 'Address added.');
    }

    public function destroyAddress(int $address)
    {
        auth('web')->user()->addresses()->whereKey($address)->delete();
        return back()->with('status', 'Address removed.');
    }
    public function index()
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
            ['name' => 'Electronics',   'slug' => 'electronics',  'icon' => 'fa-solid fa-mobile-screen', 'bg' => 'bg-violet-50',  'color' => 'text-violet-500'],
            ['name' => 'Accessories',   'slug' => 'accessories',  'icon' => 'fa-solid fa-clock',        'bg' => 'bg-amber-50',   'color' => 'text-amber-500'],
            ['name' => 'Footwear',      'slug' => 'footwear',     'icon' => 'fa-solid fa-shoe-prints',  'bg' => 'bg-green-50',   'color' => 'text-green-600'],
            ['name' => 'Beauty',        'slug' => 'beauty',       'icon' => 'fa-solid fa-pump-soap',    'bg' => 'bg-red-50',     'color' => 'text-red-400'],
            ['name' => 'Sports',        'slug' => 'sports',       'icon' => 'fa-solid fa-baseball',     'bg' => 'bg-blue-50',    'color' => 'text-blue-500'],
        ];

        $promoBanners = [
            ['eyebrow' => 'Up to 50% Off', 'title' => 'Premium Electronics', 'subtitle' => 'Top brands. Best prices.', 'cta' => 'Shop Electronics', 'slug' => 'electronics', 'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=1600&h=500&fit=crop&q=80'],
            ['eyebrow' => 'Fashion Sale',  'title' => 'Flat 40% Off',        'subtitle' => 'Trendy looks for every season.', 'cta' => 'Shop Now', 'slug' => 'women', 'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1600&h=500&fit=crop&q=80'],
        ];

        $newArrivals = Schema::hasTable('products')
            ? $this->productCards(Product::query()->active()->latest()->with('images')->take(5)->get())
            : [];
        $bestSellers = Schema::hasTable('products')
            ? $this->productCards(Product::query()->active()->where('featured', true)->with('images')->take(5)->get())
            : [];

        return view('user.index', compact('heroSlides', 'categories', 'newArrivals', 'bestSellers', 'promoBanners'));
    }

    public function shop(Request $request)
    {
        $category = $request->query('category');
        $query = Product::query()->active()->with(['images', 'category', 'brand']);
        $query->when($request->filled('q'), fn($products) => $products->where(fn($search) => $search
            ->where('name', 'like', '%' . $request->string('q') . '%')
            ->orWhere('sku', 'like', '%' . $request->string('q') . '%')));
        $query->when($category && !in_array($category, ['new', 'best-sellers', 'sale'], true), fn($products) => $products->whereHas('category', fn($categories) => $categories->where('slug', $category)));
        $query->when($category === 'new', fn($products) => $products->latest());
        $query->when($category === 'best-sellers', fn($products) => $products->where('featured', true));
        $query->when($category === 'sale', fn($products) => $products->whereNotNull('sale_price'));
        $query->when($request->filled('brand'), fn($products) => $products->whereHas('brand', fn($brand) => $brand->whereIn('slug', (array) $request->input('brand'))));
        $query->when($request->filled('max_price'), fn($products) => $products->whereRaw('COALESCE(sale_price, price) <= ?', [(float) $request->input('max_price')]));

        match ($request->input('sort')) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) desc'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $productPaginator = $query->paginate(12)->withQueryString();
        $products = $this->productCards($productPaginator->getCollection());
        $filterCategories = Category::query()->active()->withCount('products')->get()->map(fn($item) => ['name' => $item->name, 'slug' => $item->slug, 'count' => $item->products_count])->all();
        $brands = Brand::query()->active()->withCount('products')->orderBy('name')->get()->map(fn($brand) => ['name' => $brand->name, 'slug' => $brand->slug, 'count' => $brand->products_count])->all();
        $totalCount = $productPaginator->total();

        return view('user.shop', compact('products', 'productPaginator', 'filterCategories', 'brands', 'category', 'totalCount'));
    }

    public function orders(Request $request, string $orderNumber)
    {
        $model = auth('web')->user()->orders()->with(['items', 'shippingAddress'])->where('order_number', $orderNumber)->firstOrFail();
        $order = [
            'number' => $model->order_number,
            'placed_at' => optional($model->placed_at ?? $model->created_at)->format('M d, Y'),
            'delivered_at' => $model->order_status === 3 ? optional($model->updated_at)->format('M d, Y') : 'Pending',
            'payment_method' => ucfirst($model->payment_mode ?: 'Stripe'),
            'payment_status' => $model->payment_status_label,
            'timeline' => [
                ['label' => 'Order Placed', 'date' => optional($model->created_at)->format('M d'), 'icon' => 'fa-check'],
                ['label' => 'Processing', 'date' => $model->order_status >= 1 ? optional($model->created_at)->format('M d') : '-', 'icon' => 'fa-box'],
                ['label' => 'Shipped', 'date' => $model->order_status >= 2 ? optional($model->updated_at)->format('M d') : '-', 'icon' => 'fa-truck'],
                ['label' => 'Delivered', 'date' => $model->order_status >= 3 ? optional($model->updated_at)->format('M d') : '-', 'icon' => 'fa-check-double'],
            ],
            'items' => $model->items->map(fn($item) => [
                'name' => $item->product_name,
                'variant' => $item->variant_name,
                'image' => $item->image_url ?: asset('assets/images/placeholder.png'),
                'price' => (float) $item->unit_price,
                'qty' => $item->quantity,
            ])->all(),
            'address' => [
                'name' => trim(($model->shippingAddress?->first_name ?? '') . ' ' . ($model->shippingAddress?->last_name ?? '')),
                'line1' => $model->shippingAddress?->address_line_1,
                'city' => trim(($model->shippingAddress?->city ?? '') . ', ' . ($model->shippingAddress?->state ?? '') . ' ' . ($model->shippingAddress?->postal_code ?? '')),
                'phone' => $model->shippingAddress?->phone,
            ],
        ];

        return view('user.order-details', compact('order'));
    }

    public function product(Request $request, string $slug)
    {
        $model = Product::query()->active()->with(['images', 'category'])->where('slug', $slug)->firstOrFail();
        $price = (float) ($model->sale_price ?? $model->price);
        $product = [
            'id' => $model->id,
            'slug' => $model->slug,
            'name' => $model->name,
            'category' => $model->category?->name,
            'category_slug' => $model->category?->slug,
            'price' => $price,
            'old_price' => $model->sale_price ? (float) $model->price : null,
            'discount_pct' => $model->sale_price ? (int) round((1 - $price / (float) $model->price) * 100) : 0,
            'rating' => (float) ($model->reviews()->approved()->avg('rating') ?? 0),
            'reviews' => $model->reviews()->approved()->count(),
            'description' => $model->short_description ?: $model->description,
            'long_description' => $model->description,
            'gallery' => collect([$model->main_image_url])->merge($model->images->map(fn($image) => \App\Support\Supabase::url($image->image)))->filter()->values()->all(),
            'colors' => [],
            'specs' => [],
        ];

        $related = $this->productCards(Product::query()->active()->where('category_id', $model->category_id)->where('id', '!=', $model->id)->take(4)->get());

        return view('user.product', compact('product', 'related'));
    }

    protected function currentCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth('web')->id()])->load('items.product.images');
    }

    protected function cartItems(): array
    {
        return $this->currentCart()->items->map(function ($item) {
            $product = $item->product;
            return [
                'id' => $item->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'variant' => $item->variant?->name,
                'image' => $product->main_image_url ?: asset('assets/images/placeholder.png'),
                'price' => (float) $item->price,
                'old_price' => null,
                'qty' => $item->quantity,
            ];
        })->all();
    }

    public function cart(Request $request)
    {
        $cartItems = $this->cartItems();
        $taxPct = 8;

        $subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
        $appliedCoupon = session('coupon_code');
        $coupon = $appliedCoupon ? Coupon::where('code', $appliedCoupon)->first() : null;
        $discountPct = $coupon?->type === 'percentage' ? (float) $coupon->value : 0;
        $discount = $coupon ? $this->couponDiscount($coupon, $subtotal) : 0;
        $tax = ($subtotal - $discount) * ($taxPct / 100);
        $total = $subtotal - $discount + $tax;

        return view('user.cart', compact('cartItems', 'discountPct', 'taxPct', 'subtotal', 'discount', 'tax', 'total', 'appliedCoupon'));
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:50']]);
        $coupon = Coupon::where('code', strtoupper(trim($data['code'])))->first();
        $subtotal = collect($this->cartItems())->sum(fn($item) => $item['price'] * $item['qty']);

        if (!$coupon || !$coupon->status || ($coupon->starts_at && $coupon->starts_at->isFuture()) || ($coupon->expires_at && $coupon->expires_at->isPast())) {
            return back()->withErrors(['code' => 'This coupon is invalid or expired.']);
        }
        if ($subtotal < (float) $coupon->minimum_order_amount) {
            return back()->withErrors(['code' => 'Your cart does not meet the minimum order amount for this coupon.']);
        }

        session(['coupon_code' => $coupon->code]);
        return back()->with('status', "Coupon {$coupon->code} applied.");
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');
        return back()->with('status', 'Coupon removed.');
    }

    public function account(Request $request)
    {
        $account = auth('web')->user();
        $user = ['name' => $account->name, 'email' => $account->email, 'initials' => strtoupper(substr($account->name, 0, 2))];
        $stats = ['orders' => $account->orders()->count(), 'wishlist' => $account->wishlist?->items()->count() ?? 0, 'addresses' => $account->addresses()->count()];
        $orders = $account->orders()->latest()->take(10)->get()->map(fn($order) => [
            'number' => $order->order_number,
            'date' => optional($order->placed_at ?? $order->created_at)->format('M d, Y'),
            'status' => $order->order_status_label,
            'status_classes' => 'bg-gray-100 text-gray-600',
            'total' => (float) $order->total_price,
        ])->all();

        return view('user.account', compact('user', 'stats', 'orders'));
    }

    public function checkout(Request $request)
    {
        $cartItems = $this->cartItems();
        abort_if(empty($cartItems), 404, 'Your cart is empty.');
        $addresses = auth('web')->user()->addresses()->orderByDesc('is_default')->get();

        $taxPct = 8;
        $appliedCoupon = session('coupon_code');
        $subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']);
        $coupon = $appliedCoupon ? Coupon::where('code', $appliedCoupon)->first() : null;
        $discountPct = $coupon?->type === 'percentage' ? (float) $coupon->value : 0;
        $discount = $coupon ? $this->couponDiscount($coupon, $subtotal) : 0;
        $tax = ($subtotal - $discount) * ($taxPct / 100);
        $total = $subtotal - $discount + $tax;

        return view('user.checkout', compact('cartItems', 'addresses', 'taxPct', 'appliedCoupon', 'subtotal', 'discount', 'tax', 'total'));
    }

    protected function couponDiscount(Coupon $coupon, float $subtotal): float
    {
        $discount = $coupon->type === 'percentage'
            ? $subtotal * ((float) $coupon->value / 100)
            : (float) $coupon->value;

        if ($coupon->maximum_discount !== null) {
            $discount = min($discount, (float) $coupon->maximum_discount);
        }

        return min(round($discount, 2), round($subtotal, 2));
    }

    protected function productCards(Collection $products): array
    {
        return $products->map(fn($product) => [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'image' => $product->main_image_url ?: asset('assets/images/placeholder.png'),
            'price' => (float) ($product->sale_price ?? $product->price),
            'old_price' => $product->sale_price ? (float) $product->price : null,
            'rating' => (float) ($product->reviews()->approved()->avg('rating') ?? 0),
            'reviews' => $product->reviews()->approved()->count(),
            'badge' => $product->featured ? 'Featured' : null,
            'badge_color' => 'bg-brand-700',
        ])->all();
    }

    public function wishlist(Request $request)
    {
        $wishlistItems = [
            ['id' => 1, 'name' => "Women's Knit Sweater", 'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=200&q=80', 'price' => 49.99, 'old_price' => 69.99, 'rating' => 4.5, 'reviews' => 24],
            ['id' => 2, 'name' => "Men's Running Shoes",  'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200&q=80', 'price' => 79.99, 'old_price' => 99.99, 'rating' => 4, 'reviews' => 18],
            ['id' => 3, 'name' => 'Smart Watch Series 8', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80', 'price' => 199.99, 'old_price' => null, 'rating' => 5, 'reviews' => 32],
        ];

        return view('user.wishlist', compact('wishlistItems'));
    }

    public function about(Request $request)
    {
        $valueProps = [
            ['icon' => 'fa-solid fa-truck-fast',   'title' => 'Free Shipping',  'subtitle' => 'On orders over $50'],
            ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Secure Payments', 'subtitle' => '100% protected'],
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

        return view('user.about', compact('valueProps', 'mission', 'differentiators'));
    }

    // ============ Blog ===========

    protected function allPosts(): array
    {
        return [
            [
                'slug' => '10-smart-shopping-tips-to-save-more-in-2025',
                'title' => '10 Smart Shopping Tips to Save More in 2025',
                'excerpt' => 'Discover simple and effective ways to save money while shopping online. These tips will help you get the best deals.',
                'image' => 'https://images.unsplash.com/photo-1556909212-d5b604d0c90d?w=600&q=80',
                'tag' => 'Shopping Tips',
                'tag_slug' => 'shopping-tips',
                'tag_color' => 'bg-brand-700',
                'author' => 'Emily Carter',
                'author_avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'date' => 'Sep 16, 2025',
                'read_time' => '5 min read',
            ],
            [
                'slug' => 'top-5-smartwatches-for-your-active-lifestyle',
                'title' => 'Top 5 Smartwatches for Your Active Lifestyle',
                'excerpt' => 'Stay connected, track your health and live smarter with the best smartwatches on the market today.',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
                'tag' => 'Trends',
                'tag_slug' => 'trends',
                'tag_color' => 'bg-sky-600',
                'author' => 'James Miller',
                'author_avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'date' => 'Sep 14, 2025',
                'read_time' => '6 min read',
            ],
            [
                'slug' => 'how-to-create-a-cozy-home-on-a-budget',
                'title' => 'How to Create a Cozy Home on a Budget',
                'excerpt' => 'Small changes can make a big difference. Here are simple ideas to turn your space into a cozy, stylish home.',
                'image' => 'https://images.unsplash.com/photo-1567016432779-094069958ea5?w=600&q=80',
                'tag' => 'Lifestyle',
                'tag_slug' => 'lifestyle',
                'tag_color' => 'bg-amber-500',
                'author' => 'Sophia Davis',
                'author_avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'date' => 'Sep 11, 2025',
                'read_time' => '4 min read',
            ],
            [
                'slug' => 'how-to-choose-the-perfect-running-shoes',
                'title' => 'How to Choose the Perfect Running Shoes',
                'excerpt' => 'Find the right fit, comfort and support for your running goals with our detailed guide.',
                'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=600&q=80',
                'tag' => 'Product Guides',
                'tag_slug' => 'product-guides',
                'tag_color' => 'bg-violet-600',
                'author' => 'Daniel Wilson',
                'author_avatar' => 'https://randomuser.me/api/portraits/men/51.jpg',
                'date' => 'Sep 8, 2025',
                'read_time' => '7 min read',
            ],
            [
                'slug' => 'the-best-skincare-products-for-glowing-skin',
                'title' => 'The Best Skincare Products for Glowing Skin',
                'excerpt' => 'Get that natural glow with our top picks for skin care products that actually work.',
                'image' => 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=600&q=80',
                'tag' => 'Trends',
                'tag_slug' => 'trends',
                'tag_color' => 'bg-sky-600',
                'author' => 'Olivia Brown',
                'author_avatar' => 'https://randomuser.me/api/portraits/women/12.jpg',
                'date' => 'Sep 5, 2025',
                'read_time' => '5 min read',
            ],
            [
                'slug' => 'travel-essentials-you-cant-leave-home-without',
                'title' => "Travel Essentials You Can't Leave Home Without",
                'excerpt' => 'Pack smarter and travel better with these must-have essentials for your next trip.',
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
                'tag' => 'Shopping Tips',
                'tag_slug' => 'shopping-tips',
                'tag_color' => 'bg-brand-700',
                'author' => 'Sophia Davis',
                'author_avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'date' => 'Sep 2, 2025',
                'read_time' => '3 min read',
            ],
        ];
    }

    public function blog(Request $request)
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
            ->when($activeCategory, fn($c) => $c->where('tag_slug', $activeCategory))
            ->when($search, fn($c) => $c->filter(
                fn($p) => str_contains(strtolower($p['title']), strtolower($search))
                    || str_contains(strtolower($p['excerpt']), strtolower($search))
            ))
            ->values()
            ->all();

        $currentPage = (int) $request->query('page', 1);

        return view('user.blog', compact('posts', 'blogCategories', 'activeCategory', 'currentPage'));
    }

    public function showBlog(Request $request, string $slug)
    {
        $post = collect($this->allPosts())->firstWhere('slug', $slug);

        abort_if(!$post, 404);

        $related = collect($this->allPosts())
            ->where('slug', '!=', $slug)
            ->take(3)
            ->values()
            ->all();

        return view('user.blog-show', compact('post', 'related'));
    }

    // =========== Contact ===========

    public function contact(Request $request)
    {
        $subjects = [
            'General Inquiry',
            'Order Support',
            'Returns & Refunds',
            'Shipping Question',
            'Product Feedback',
            'Partnership',
        ];

        $contactChannels = [
            [
                'icon'  => 'fa-solid fa-location-dot',
                'label' => 'Our Location',
                'lines' => ['123 Green Park, Near City Mall', 'Kolkata, West Bengal 700001'],
            ],
            [
                'icon'  => 'fa-solid fa-envelope',
                'label' => 'Email Us',
                'lines' => ['support@shopease.com'],
                'href'  => 'mailto:support@shopease.com',
            ],
            [
                'icon'  => 'fa-solid fa-phone',
                'label' => 'Call Us',
                'lines' => ['+91 98765 43210'],
                'href'  => 'tel:+919876543210',
            ],
            [
                'icon'  => 'fa-solid fa-clock',
                'label' => 'Working Hours',
                'lines' => ['Mon - Sat: 9:00 AM - 8:00 PM', 'Sunday: 10:00 AM - 4:00 PM'],
            ],
        ];

        $socials = [
            ['name' => 'Facebook',  'icon' => 'fa-brands fa-facebook-f', 'url' => '#'],
            ['name' => 'Instagram', 'icon' => 'fa-brands fa-instagram',  'url' => '#'],
            ['name' => 'YouTube',   'icon' => 'fa-brands fa-youtube',    'url' => '#'],
            ['name' => 'Twitter',   'icon' => 'fa-brands fa-twitter',    'url' => '#'],
            ['name' => 'Pinterest', 'icon' => 'fa-brands fa-pinterest',  'url' => '#'],
        ];

        $storeName = 'AgriExpress';
        $storeAddress = [
            'line1' => '123 Green Park, Near City Mall',
            'city'  => 'Kolkata, West Bengal 700001',
        ];

        // Keyless OpenStreetMap embed so the map renders without configuration.
        // Swap for a Google Maps embed URL if you have an API key.
        $mapEmbedUrl = 'https://www.openstreetmap.org/export/embed.html?bbox=88.32%2C22.53%2C88.40%2C22.59&layer=mapnik&marker=22.5626%2C88.3630';

        return view('user.contact', compact(
            'subjects',
            'contactChannels',
            'socials',
            'storeName',
            'storeAddress',
            'mapEmbedUrl'
        ));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // TODO: persist and/or dispatch a Mailable, e.g.:
        // Mail::to(config('mail.from.address'))->send(new ContactMessage($data));

        return back()->with('status', "Thanks for reaching out! We'll get back to you shortly.");
    }
}
