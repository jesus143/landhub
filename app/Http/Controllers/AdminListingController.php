<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'main_image' => 'nullable|image|max:10240',
            'image_url' => 'nullable|url',
            'media_images' => 'nullable|array',
            'media_images.*' => 'image|max:10240',
            'media' => 'nullable|string', // JSON string expected
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'nearby_landmarks' => 'nullable|string',
            'map_link' => 'nullable|url',
            'featured_video_url' => 'nullable|url',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'contact_fb_link' => 'nullable|url',
        ]);

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('listings', 'public');
            $data['image_url'] = Storage::disk('public')->url($path);
        }

        // Handle additional media images upload
        $uploadedMedia = [];
        if ($request->hasFile('media_images')) {
            foreach ($request->file('media_images') as $file) {
                $path = $file->store('listings', 'public');
                $uploadedMedia[] = [
                    'type' => 'image',
                    'url' => Storage::disk('public')->url($path),
                ];
            }
        }

        // Decode media JSON if provided and merge with uploaded images
        $mediaItems = $uploadedMedia;
        if (! empty($data['media'])) {
            $decoded = json_decode($data['media'], true);
            if (is_array($decoded)) {
                $mediaItems = array_merge($uploadedMedia, $decoded);
            }
        }
        $data['media'] = ! empty($mediaItems) ? $mediaItems : null;

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
            'main_image' => 'nullable|image|max:10240',
            'image_url' => 'nullable|url',
            'media_images' => 'nullable|array',
            'media_images.*' => 'image|max:10240',
            'media' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'nearby_landmarks' => 'nullable|string',
            'map_link' => 'nullable|url',
            'featured_video_url' => 'nullable|url',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'contact_fb_link' => 'nullable|url',
        ]);

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('listings', 'public');
            $data['image_url'] = Storage::disk('public')->url($path);
        }

        // Handle additional media images upload
        $uploadedMedia = [];
        if ($request->hasFile('media_images')) {
            foreach ($request->file('media_images') as $file) {
                $path = $file->store('listings', 'public');
                $uploadedMedia[] = [
                    'type' => 'image',
                    'url' => Storage::disk('public')->url($path),
                ];
            }
        }

        // Decode media JSON if provided and merge with uploaded images
        $mediaItems = $uploadedMedia;
        if (! empty($data['media'])) {
            $decoded = json_decode($data['media'], true);
            if (is_array($decoded)) {
                $mediaItems = array_merge($uploadedMedia, $decoded);
            }
        } else {
            // If no new media JSON provided, keep existing media and add uploaded images
            $existingMedia = $listing->media ?? [];
            $mediaItems = array_merge($existingMedia, $uploadedMedia);
        }
        $data['media'] = ! empty($mediaItems) ? $mediaItems : ($listing->media ?? null);

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

    public function deleteMedia(Request $request, Listing $listing)
    {
        $request->validate([
            'media_index' => 'required|integer|min:0',
        ]);

        $media = $listing->media ?? [];

        if (! isset($media[$request->media_index])) {
            return response()->json(['error' => 'Media item not found'], 404);
        }

        $mediaItem = $media[$request->media_index];

        // Delete file from storage if it's a local file
        if (isset($mediaItem['url'])) {
            $url = $mediaItem['url'];
            if (str_contains($url, '/storage/listings/')) {
                $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
                Storage::disk('public')->delete($path);
            }
        }

        // Remove item from array
        unset($media[$request->media_index]);
        $media = array_values($media); // Re-index array

        $listing->update(['media' => ! empty($media) ? $media : null]);

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    public function deleteMainImage(Request $request, Listing $listing)
    {
        if (! $listing->image_url) {
            return response()->json(['error' => 'No main image found'], 404);
        }

        // Delete file from storage if it's a local file
        $url = $listing->image_url;
        if (str_contains($url, '/storage/listings/')) {
            $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            Storage::disk('public')->delete($path);
        }

        // Remove image_url from listing
        $listing->update(['image_url' => null]);

        return response()->json(['success' => true, 'message' => 'Main image deleted successfully']);
    }
}
