<?php

use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);
    Route::resource('borrowings', BorrowingController::class)->only(['index', 'store', 'show']);
    Route::resource('payments', App\Http\Controllers\PaymentController::class)->only(['index']);

    // Borrowing wizard
    Route::get('/borrow/{book}', [BorrowController::class, 'create'])->name('borrow.create');

    // Extension wizard
    Route::get('/extend/{borrowing}', [ExtendController::class, 'create'])->name('extend.create');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
