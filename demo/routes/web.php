<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AgriExpress Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product');

// Content pages
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{product}/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/reorder/{order}', [CartController::class, 'reorder'])->name('cart.reorder');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
});

Route::get('/orders/{order}', [OrderController::class, 'show'])->name('order.details');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Laravel's built-in password reset routes can be wired here, e.g. via
// Laravel Breeze/Fortify. A named placeholder keeps the Blade views valid
// before that scaffolding is installed:
Route::get('/forgot-password', fn() => view('auth.login'))->name('password.request');

Route::post('/newsletter', function () {
    request()->validate(['email' => 'required|email']);
    return back()->with('status', 'Subscribed!');
})->name('newsletter.subscribe');
