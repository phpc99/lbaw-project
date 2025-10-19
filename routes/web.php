<?php

use App\Http\Controllers\AdminController;
use App\Models\Product;
use App\Http\Controllers\CategoryController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MailController;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/products');
//Route::get('/', [ProductController::class, 'index'])->name('home');

// Page redirects
//Route::view('/about', 'pages.about')->name('about');

// Product related Routes
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'list')->name('products.list');
    Route::get('/product/{id}', 'show')->name('product.show')->middleware('auth');
    Route::put('/product/{id}/update', 'update')->name('product.update');
    Route::post('/products/add', 'add')->name('product.add')->middleware(['auth', 'admin']);
    Route::delete('/product/{id}', 'remove')->name('product.remove')->middleware(['auth', 'admin']);
    Route::post('/product/{id}/reviews', 'addReview')->name('product.addReview')->middleware('auth');   
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
});

// Category Routes 
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'list')->name('categories.list');
    Route::post('/categories', 'makeNew')->name('categories.makeNew');
    Route::put('/categories/{id}', 'edit')->name('categories.edit');
    Route::delete('/categories/{id}', 'erase')->name('categories.erase');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
});

// User related routes
Route::controller(UserController::class)->group(function() {
    Route::get('/user/{id}', 'show')->name('user.show')->middleware('auth');
    Route::get('/profile', 'profile')->name('user.profile')->middleware('auth');
    Route::put('/profile/update', 'update')->name('user.update')->middleware('auth');
    Route::delete('/profile', 'destroy')->name('account.delete')->middleware('auth');   
});

Route::controller(PurchaseController::class)->group(function () {
    Route::get('/purchases', 'list')->name('purchase.list')->middleware('auth');
    Route::get('/purchase/{id}', 'show')->name('purchase.show')->middleware('auth');
    Route::delete('/purchase/{id}', 'destroy')->name('purchase.delete')->middleware('auth');
    Route::get('/admin/purchase-history', 'adminList')->name('admin.purchase.history')->middleware('auth', 'admin');
});

Route::controller(WishlistController::class)->group(function () {
    Route::get('/wishlist', 'show')->name('wishlist.show')->middleware('auth');
    Route::post('/wishlist/add/{id}', 'add')->name('wishlist.add')->middleware('auth');
    Route::delete('/wishlist/{id}', 'remove')->name('wishlist.remove')->middleware('auth');
});
    
//notifications
Route::controller(NotificationController::class)->group(function () {
    Route::get('/notifications', 'list')->name('notifications.list')->middleware('auth');
    Route::patch('/notifications/{id}/mark-as-read', 'markAsRead')->name('notifications.markAsRead')->middleware('auth');
});

// global searches
Route::get('/search', [SearchController::class, 'search'])->name('global.search');

// Cart Routes
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'show')->name('cart.show')->middleware('auth');
    Route::post('/cart/add/{product_id}', 'add')->name('cart.add')->middleware('auth');
    Route::put('/cart/update/{id}', 'update')->name('cart.update')->middleware('auth');
    Route::delete('/cart/{id}', 'remove')->name('cart.remove')->middleware('auth');
    Route::get('/cart/checkout', 'checkout')->name('cart.checkout');
    Route::post('/cart/checkout', 'checkoutProcess')->name('checkout.process');
});

// Checkout Routes
Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'show')->name('checkout.show');
    Route::post('/checkout/complete', 'complete')->name('checkout.complete');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('recover/form', 'recoverForm')->name('password.recover.form');
    Route::get('new-password/form', 'newPasswordForm')->name('password.new.form');
    Route::post('recover/reset-password', 'resetPassword')->name('password.reset');
});

Route::controller(App\Http\Controllers\Auth\LoginController::class)->group(function () {
    Route::get('login/google', 'googleLogin')->name('login.google');
    Route::get('login/google-callback', 'googleAuthentication')->name('login.google-callback');
});
// Send Email controller
Route::post('/send', [MailController::class, 'send'])->name('password.email');

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Static Pages
Route::controller(StaticPagesController::class)->group(function () {
    Route::get('/faq', 'indexFAQ')->name('faq');
    Route::get('/about', 'indexABOUT')->name('about');
    Route::get('/contact', 'indexCONTACT')->name('contact');
});

// handle unauthenticated access
Route::get('/guest-view', function () {
    return view('guest-view'); 
})->name('guest.view');

// Orders
Route::controller(OrderController::class)->group(function () {
    Route::get('/orders', 'show')->name('orders.show');
    Route::put('/orders/{id}/edit', 'update')->name('orders.edit');
});

// navigation bar
Route::get('/navbar', function () {
    return view('navbar'); 
})->name('navbar');

// admin 
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/dashboard', 'dashboard')->name('admin.dashboard')->middleware(['auth', 'admin']);
    Route::get('/admin/products', 'showProducts')->name('admin.products')->middleware(['auth', 'admin']); 
    Route::get('/admin/users', 'showUsers')->name('admin.users')->middleware(['auth', 'admin']);
    Route::delete('/user/{id}', 'destroy')->name('user.delete')->middleware(['auth', 'admin']);
    Route::get('/admin/orders', 'showOrders')->name('admin.orders')->middleware(['auth', 'admin']);
});

// ensure unique product name
Route::get('/check-product-name', [ProductController::class, 'checkProductName']);

