<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource with search and filters.
     */
    public function index(Request $request)
    {
        $query = Listing::query();

        // Keyword search (title, location, description) - exclude empty/whitespace
        $search = trim($request->get('search', ''));
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Location filter - exclude empty/whitespace
        $location = trim($request->get('location', ''));
        if (! empty($location)) {
            $query->where('location', 'like', "%{$location}%");
        }

        // Price range filter - exclude empty/zero values
        $minPrice = $request->get('min_price');
        if (! empty($minPrice) && $minPrice > 0) {
            $query->where('price', '>=', $minPrice);
        }
        $maxPrice = $request->get('max_price');
        if (! empty($maxPrice) && $maxPrice > 0) {
            $query->where('price', '<=', $maxPrice);
        }

        // Category filter - exclude empty values
        $category = $request->get('category');
        if (! empty($category)) {
            $query->where('category', $category);
        }

        // Status filter - exclude empty values
        $status = $request->get('status');
        if (! empty($status)) {
            $query->where('status', $status);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price-low' => $query->orderBy('price', 'asc'),
            'price-high' => $query->orderBy('price', 'desc'),
            'size-large' => $query->orderBy('area', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $listings = $query->paginate(12)->withQueryString();

        // Get all unique locations for the dropdown (from all listings, not just current page)
        $locations = Listing::distinct()->orderBy('location', 'asc')->pluck('location')->filter()->values();

        return view('listings.index', [
            'listings' => $listings,
            'locations' => $locations,
        ]);
    }

    /**
     * Display the specified listing.
     *.http://localhost:8000/lands/1-residential-beachfront-property-in-boracay-boracay-aklan
     */
    public function show(Listing $listing, $slug)
    {
        $expected = Str::slug($listing->category).'-'.Str::slug($listing->title).'-'.Str::slug($listing->location);

        if ($slug !== $expected) {
            return redirect()->route('listings.show', ['listing' => $listing->id, 'slug' => $expected], 301);
        }

        // eager load comments and commenter user
        $listing->load(['comments' => function ($q) {
            $q->latest();
        }, 'comments.user']);

        return view('listings.show', [
            'listing' => $listing,
        ]);
    }
}
