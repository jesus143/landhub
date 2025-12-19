<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
// SEO-friendly show route: /lands/{id}-{slug}
Route::get('/lands/{listing}-{slug}', [ListingController::class, 'show'])->name('listings.show');
// Store comments (guests and authenticated users)
Route::post('/lands/{listing}-{slug}/comments', [\App\Http\Controllers\CommentsController::class, 'store'])->name('listings.comments.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin listing management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('listings', [\App\Http\Controllers\AdminListingController::class, 'index'])->name('listings.index');
        Route::get('listings/create', [\App\Http\Controllers\AdminListingController::class, 'create'])->name('listings.create');
        Route::post('listings', [\App\Http\Controllers\AdminListingController::class, 'store'])->name('listings.store');
        Route::get('listings/{listing}/edit', [\App\Http\Controllers\AdminListingController::class, 'edit'])->name('listings.edit');
        Route::put('listings/{listing}', [\App\Http\Controllers\AdminListingController::class, 'update'])->name('listings.update');
        Route::patch('listings/{listing}/status', [\App\Http\Controllers\AdminListingController::class, 'updateStatus'])->name('listings.updateStatus');
    });
});

require __DIR__.'/auth.php';
