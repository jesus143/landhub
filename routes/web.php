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
    $stats = [
        'total_listings' => \App\Models\Listing::count(),
        'active_listings' => \App\Models\Listing::where('status', 'for_sale')->count(),
        'pending_listings' => \App\Models\Listing::where('status', 'pending')->count(),
        'sold_listings' => \App\Models\Listing::where('status', 'sold')->count(),
        'total_users' => \App\Models\User::count(),
        'total_comments' => \App\Models\Comment::count(),
        'approved_comments' => \App\Models\Comment::where('approved', true)->count(),
        'pending_comments' => \App\Models\Comment::where('approved', false)->count(),
        'total_likes' => \App\Models\CommentLike::count(),
        'total_listing_value' => \App\Models\Listing::where('status', 'for_sale')->sum('price'),
        'average_price' => \App\Models\Listing::where('status', 'for_sale')->avg('price'),
        'total_area' => \App\Models\Listing::sum('area'),
    ];

    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Comments endpoints handled via listings routes (guests + auth)

    // Admin listing management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('listings', [\App\Http\Controllers\AdminListingController::class, 'index'])->name('listings.index');
        Route::get('listings/create', [\App\Http\Controllers\AdminListingController::class, 'create'])->name('listings.create');
        Route::post('listings', [\App\Http\Controllers\AdminListingController::class, 'store'])->name('listings.store');
        Route::get('listings/{listing}/edit', [\App\Http\Controllers\AdminListingController::class, 'edit'])->name('listings.edit');
        Route::put('listings/{listing}', [\App\Http\Controllers\AdminListingController::class, 'update'])->name('listings.update');
        Route::patch('listings/{listing}/status', [\App\Http\Controllers\AdminListingController::class, 'updateStatus'])->name('listings.updateStatus');
    });

    // Comment moderation (approve) - admin only check performed in controller
    Route::patch('/comments/{comment}/approve', [\App\Http\Controllers\CommentsController::class, 'approve'])->name('comments.approve');
    // Comment like/agree (toggle) - authenticated users
    Route::post('/comments/{comment}/agree', [\App\Http\Controllers\CommentsController::class, 'agree'])->name('comments.agree');
    // Comment update and delete - only comment owner
    Route::put('/comments/{comment}', [\App\Http\Controllers\CommentsController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentsController::class, 'destroy'])->name('comments.destroy');

    // Messaging routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MessagesController::class, 'index'])->name('inbox');
        Route::get('/create', [\App\Http\Controllers\MessagesController::class, 'create'])->name('create');
        Route::get('/create/listing/{listing}', [\App\Http\Controllers\MessagesController::class, 'create'])->name('create.listing');
        Route::post('/', [\App\Http\Controllers\MessagesController::class, 'store'])->name('store');
        Route::get('/user/{user}', [\App\Http\Controllers\MessagesController::class, 'show'])->name('show');
    });

    // Admin messages
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('messages', [\App\Http\Controllers\Admin\AdminMessagesController::class, 'index'])->name('messages.index');
    });
});

require __DIR__.'/auth.php';
