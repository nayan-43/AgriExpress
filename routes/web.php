<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeWebhookController;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

//  ===========  ADMIN ===========

// ---------------------------------------------------------------- Auth

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('authenticate');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'store')->name('store');
    Route::post('/logout', 'logout')->name('logout');

    Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');

    Route::get('/admin/login', 'adminlogin')->name('admin.login');
    Route::post('/admin/authenticate', 'adminauthenticate')->name('admin.authenticate');
});
// ---------------------------------------------------------------- Admin
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [DashboardController::class, 'index'])->name('search'); // stub target for the topbar search form

    Route::resource('products', ProductController::class)->except('show');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('brands', BrandController::class)->except('show');
    Route::resource('coupons', CouponController::class)->except('show');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}/status', [CustomerController::class, 'updateStatus'])->name('customers.updateStatus');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'adminlogout')->name('logout');
    });


    // Route::controller(CategoryController::class)->group(function () {
    //     Route::get('/category', 'index')->name('category.index');
    //     Route::get('/category/create', 'create')->name('category.create');
    //     Route::post('/category', 'store')->name('category.store');
    //     Route::get('/category/{id}/edit', 'edit')->name('category.edit');
    //     Route::put('/category/{id}', 'update')->name('category.update');
    //     Route::delete('/category/{id}', 'destroy')->name('category.destroy');
    // });

    // Route::controller(ProductController::class)->group(function () {
    //     Route::get('/product', 'index')->name('product.index');
    //     Route::get('/product/create', 'create')->name('product.create');
    //     Route::post('/product', 'store')->name('product.store');
    //     Route::get('/product/{id}/edit', 'edit')->name('product.edit');
    //     Route::put('/product/{id}', 'update')->name('product.update');
    //     Route::delete('/product/{id}', 'destroy')->name('product.destroy');
    // });
});


//  ========  USER =========
Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/product/{slug}', 'product')->name('product');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'send')->name('contact.send');
    Route::get('/about', 'about')->name('about');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{slug}', 'showBlog')->name('blog.show');

    Route::post('/newsletter/subscribe', 'newsletterSubscribe')->name('newsletter.subscribe');
});

Route::middleware('auth:web')->group(function () {
    Route::get('/orders/{orderNumber}', [SiteController::class, 'orders'])->name('order.details');
    Route::get('/cart', [SiteController::class, 'cart'])->name('cart');
    Route::post('/cart/coupon', [SiteController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon', [SiteController::class, 'removeCoupon'])->name('cart.coupon.remove');
    Route::patch('/cart/{item}', function (Request $request, int $item) {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:10']]);
        $cartItem = CartItem::whereKey($item)->whereHas('cart', fn($cart) => $cart->where('user_id', auth('web')->id()))->firstOrFail();
        $cartItem->update(['quantity' => $data['quantity']]);
        return redirect()->route('cart')->with('status', 'Cart updated.');
    })->name('cart.update');
    Route::post('/cart/{product}/add', function (Request $request, Product $product) {
        abort_unless($product->status, 404);
        $cart = Cart::firstOrCreate(['user_id' => auth('web')->id()]);
        $item = CartItem::firstOrNew(['cart_id' => $cart->id, 'product_id' => $product->id, 'variant_id' => null]);
        $item->price = $product->sale_price ?? $product->price;
        $item->quantity = min(10, ($item->quantity ?? 0) + max(1, (int) $request->input('quantity', 1)));
        $item->save();
        return redirect()->route('cart')->with('status', 'Item added to cart.');
    })->name('cart.add');
    Route::delete('/cart/{item}', function (int $item) {
        CartItem::whereKey($item)->whereHas('cart', fn($cart) => $cart->where('user_id', auth('web')->id()))->delete();
        return redirect()->route('cart')->with('status', 'Item removed from cart.');
    })->name('cart.remove');
    Route::post('/cart/reorder/{orderNumber}', function (string $orderNumber) {
        $order = Order::with('items')->where('user_id', auth('web')->id())->where('order_number', $orderNumber)->firstOrFail();
        $cart = Cart::firstOrCreate(['user_id' => auth('web')->id()]);
        foreach ($order->items as $orderItem) {
            $item = CartItem::firstOrNew(['cart_id' => $cart->id, 'product_id' => $orderItem->product_id, 'variant_id' => $orderItem->variant_id]);
            $item->price = $orderItem->unit_price;
            $item->quantity = min(10, ($item->quantity ?? 0) + $orderItem->quantity);
            $item->save();
        }
        return redirect()->route('cart')->with('status', 'Order items added to cart.');
    })->name('cart.reorder');
    Route::get('/wishlist', [SiteController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{product}/toggle', [SiteController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/checkout', [SiteController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/stripe', [PaymentController::class, 'checkout'])->name('checkout.stripe');
    Route::get('/checkout/success', [PaymentController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [PaymentController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/account', [SiteController::class, 'account'])->name('account');
    Route::get('/account/profile', [SiteController::class, 'profile'])->name('account.profile');
    Route::put('/account/profile', [SiteController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/addresses', [SiteController::class, 'addresses'])->name('account.addresses');
    Route::post('/account/addresses', [SiteController::class, 'storeAddress'])->name('account.addresses.store');
    Route::delete('/account/addresses/{address}', [SiteController::class, 'destroyAddress'])->name('account.addresses.destroy');
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
