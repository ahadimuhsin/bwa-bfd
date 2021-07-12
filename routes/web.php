<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MyTransactionController;
use App\Http\Controllers\ProductGalleryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Front End
Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('detail/{slug}', [FrontendController::class, 'detail'])->name('detail');

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
    Route::post('cart/{id}', [FrontendController::class, 'addToCart'])->name('cart-add');
    Route::delete('cart/{id}', [FrontendController::class, 'removeFromCart'])->name('cart-delete');
    Route::get('/success', [FrontendController::class, 'success'])->name('success');

    // Checkout
    Route::post('checkout', [FrontendController::class, 'checkout'])->name('checkout');
});


Route::middleware('auth')->name('dashboard.')->prefix('dashboard')->group( function () {
    Route::get('/', [DashboardController::class, 'index'])
    ->name('index');

    // My Transaction
    Route::resource('my-transaction', MyTransactionController::class)
    ->only('index', 'show');

    //for Admin Only
    Route::middleware('isAdmin')->group(function(){
        Route::resource('product', ProductController::class)
        ->parameters([
            'product' => 'product:slug',
        ]);
        Route::resource('product.gallery', ProductGalleryController::class)
        ->except('edit', 'update')
        ->parameters([
            'product' => 'product:slug',
        ])
        ->shallow();

        Route::resource('transaction', TransactionController::class)
        ->except('destroy', 'create', 'store');
        Route::resource('user', UserController::class)
        ->except( 'create', 'store');
    });
});
