<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class AdminListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Inline admin check middleware to avoid editing Kernel
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            if (! $user || ! ($user->is_admin ?? false)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $listings = Listing::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.listings.index', compact('listings'));
    }

    public function create()
    {
        return view('admin.listings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'area' => 'required|numeric',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'status' => 'required|string|max:50',
            'image_url' => 'nullable|url',
            'media' => 'nullable|string', // JSON string expected
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'nearby_landmarks' => 'nullable|string',
            'map_link' => 'nullable|url',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'contact_fb_link' => 'nullable|url',
        ]);

        // Decode media JSON if provided
        if (! empty($data['media'])) {
            $decoded = json_decode($data['media'], true);
            $data['media'] = is_array($decoded) ? $decoded : null;
        }

        Listing::create($data);

        return redirect()->route('admin.listings.index')->with('success', 'Listing created');
    }

    public function edit(Listing $listing)
    {
        return view('admin.listings.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'area' => 'required|numeric',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'status' => 'required|string|max:50',
            'image_url' => 'nullable|url',
            'media' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'nearby_landmarks' => 'nullable|string',
            'map_link' => 'nullable|url',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'contact_fb_link' => 'nullable|url',
        ]);

        if (! empty($data['media'])) {
            $decoded = json_decode($data['media'], true);
            $data['media'] = is_array($decoded) ? $decoded : null;
        }

        $listing->update($data);

        return redirect()->route('admin.listings.index')->with('success', 'Listing updated');
    }

    public function updateStatus(Request $request, Listing $listing)
    {
        $data = $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $listing->update(['status' => $data['status']]);

        return redirect()->route('admin.listings.index')->with('success', 'Status updated');
    }
}
