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

        // Keyword search (title, location, description)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->get('location')}%");
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
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

        return view('listings.index', [
            'listings' => $listings,
        ]);
    }

    /**
     * Display the specified listing.
     *.http://localhost:8000/lands/1-residential-beachfront-property-in-boracay-boracay-aklan
     */
    public function show(Listing $listing, $slug)
    {
        $expected = Str::slug($listing->category) . '-'.  Str::slug($listing->title) . '-' . Str::slug($listing->location);

        if ($slug !== $expected) {
            return redirect()->route('listings.show', ['listing' => $listing->id, 'slug' => $expected], 301);
        }

        return view('listings.show', [
            'listing' => $listing,
        ]);
    }
}
