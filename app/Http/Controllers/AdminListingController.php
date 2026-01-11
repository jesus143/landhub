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
        // Check if file upload failed at PHP level
        if ($request->hasFile('featured_video')) {
            $file = $request->file('featured_video');
            if (! $file->isValid()) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds PHP upload_max_filesize limit.',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds form MAX_FILE_SIZE limit.',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                ];
                $errorCode = $file->getError();
                $errorMsg = $errorMessages[$errorCode] ?? 'Unknown upload error (code: '.$errorCode.')';
                \Log::error('PHP upload error', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMsg,
                    'file_name' => $file->getClientOriginalName(),
                ]);

                return back()->withErrors(['featured_video' => 'Upload failed: '.$errorMsg])->withInput();
            }
        }

        $rules = [
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
            'is_titled' => 'nullable|boolean',
            'trees_plants' => 'nullable|string',
            'terrain_type' => 'nullable|string|max:255',
            'vehicle_accessible' => 'nullable|boolean',
            'additional_features' => 'nullable|string',
            'property_type' => 'nullable|string|max:50',
            'frontage' => 'nullable|numeric|min:0',
            'road_type' => 'nullable|string|max:100',
            'num_rooms' => 'nullable|integer|min:0',
            'is_fenced' => 'nullable|boolean',
            'is_beachfront' => 'nullable|boolean',
            'beach_frontage' => 'nullable|numeric|min:0',
            'title_status' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'contact_fb_link' => 'nullable|url',
        ];

        $messages = [
            'featured_video.file' => 'The featured video must be a valid file.',
            'featured_video.max' => 'The featured video file size must not exceed 100MB. Your file is too large.',
        ];

        try {
            $data = $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'file_info' => $request->hasFile('featured_video') ? [
                    'name' => $request->file('featured_video')->getClientOriginalName(),
                    'size' => $request->file('featured_video')->getSize(),
                    'mime' => $request->file('featured_video')->getMimeType(),
                    'extension' => $request->file('featured_video')->getClientOriginalExtension(),
                ] : 'No file uploaded',
            ]);
            throw $e;
        }

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

        // Handle featured video upload
        if ($request->hasFile('featured_video')) {
            try {
                $file = $request->file('featured_video');

                // Check for upload errors
                if (! $file->isValid()) {
                    $errorMsg = $file->getErrorMessage();
                    \Log::error('Video upload failed', [
                        'error' => $errorMsg,
                        'error_code' => $file->getError(),
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                    ]);

                    return back()->withErrors(['featured_video' => 'Video upload failed: '.$errorMsg])->withInput();
                }

                // Validate file size (100MB = 102400 KB = 104857600 bytes)
                $maxSize = 104857600; // 100MB in bytes
                if ($file->getSize() > $maxSize) {
                    $fileSizeMB = round($file->getSize() / 1048576, 2);

                    return back()->withErrors(['featured_video' => "File size ({$fileSizeMB}MB) exceeds the maximum allowed size of 100MB."])->withInput();
                }

                // Validate file extension manually
                $allowedExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];
                $extension = strtolower($file->getClientOriginalExtension());

                if (! in_array($extension, $allowedExtensions)) {
                    return back()->withErrors(['featured_video' => 'Invalid video format. Allowed formats: '.implode(', ', $allowedExtensions)])->withInput();
                }

                $path = $file->store('listings/videos', 'public');
                $data['featured_video_url'] = Storage::disk('public')->url($path);
            } catch (\Exception $e) {
                \Log::error('Video upload exception', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return back()->withErrors(['featured_video' => 'Video upload failed: '.$e->getMessage()])->withInput();
            }
        }

        // Set user_id to the current authenticated user (listing creator)
        $data['user_id'] = $request->user()->id;

        Listing::create($data);

        return redirect()->route('admin.listings.index')->with('success', 'Listing created');
    }

    public function edit(Listing $listing)
    {
        return view('admin.listings.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        // Check if file upload failed at PHP level
        if ($request->hasFile('featured_video')) {
            $file = $request->file('featured_video');
            if (! $file->isValid()) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds PHP upload_max_filesize limit.',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds form MAX_FILE_SIZE limit.',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                ];
                $errorCode = $file->getError();
                $errorMsg = $errorMessages[$errorCode] ?? 'Unknown upload error (code: '.$errorCode.')';
                \Log::error('PHP upload error', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMsg,
                    'file_name' => $file->getClientOriginalName(),
                ]);

                return back()->withErrors(['featured_video' => 'Upload failed: '.$errorMsg])->withInput();
            }
        }

        $rules = [
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
            'is_titled' => 'nullable|boolean',
            'trees_plants' => 'nullable|string',
            'terrain_type' => 'nullable|string|max:255',
            'vehicle_accessible' => 'nullable|boolean',
            'additional_features' => 'nullable|string',
            'property_type' => 'nullable|string|max:50',
            'frontage' => 'nullable|numeric|min:0',
            'road_type' => 'nullable|string|max:100',
            'num_rooms' => 'nullable|integer|min:0',
            'is_fenced' => 'nullable|boolean',
            'is_beachfront' => 'nullable|boolean',
            'beach_frontage' => 'nullable|numeric|min:0',
            'title_status' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'contact_fb_link' => 'nullable|url',
        ];

        $messages = [
            'featured_video.file' => 'The featured video must be a valid file.',
            'featured_video.max' => 'The featured video file size must not exceed 100MB. Your file is too large.',
        ];

        try {
            $data = $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'file_info' => $request->hasFile('featured_video') ? [
                    'name' => $request->file('featured_video')->getClientOriginalName(),
                    'size' => $request->file('featured_video')->getSize(),
                    'mime' => $request->file('featured_video')->getMimeType(),
                    'extension' => $request->file('featured_video')->getClientOriginalExtension(),
                ] : 'No file uploaded',
            ]);
            throw $e;
        }

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

        // Handle featured video upload
        if ($request->hasFile('featured_video')) {
            try {
                $file = $request->file('featured_video');

                // Check for upload errors
                if (! $file->isValid()) {
                    $errorMsg = $file->getErrorMessage();
                    \Log::error('Video upload failed', [
                        'error' => $errorMsg,
                        'error_code' => $file->getError(),
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                    ]);

                    return back()->withErrors(['featured_video' => 'Video upload failed: '.$errorMsg])->withInput();
                }

                // Validate file size (100MB = 102400 KB = 104857600 bytes)
                $maxSize = 104857600; // 100MB in bytes
                if ($file->getSize() > $maxSize) {
                    $fileSizeMB = round($file->getSize() / 1048576, 2);

                    return back()->withErrors(['featured_video' => "File size ({$fileSizeMB}MB) exceeds the maximum allowed size of 100MB."])->withInput();
                }

                // Validate file extension manually
                $allowedExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];
                $extension = strtolower($file->getClientOriginalExtension());

                if (! in_array($extension, $allowedExtensions)) {
                    return back()->withErrors(['featured_video' => 'Invalid video format. Allowed formats: '.implode(', ', $allowedExtensions)])->withInput();
                }

                // Delete old video file if it exists and is a local file
                if ($listing->featured_video_url && str_contains($listing->featured_video_url, '/storage/listings/videos/')) {
                    $oldPath = str_replace('/storage/', '', parse_url($listing->featured_video_url, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $file->store('listings/videos', 'public');
                $data['featured_video_url'] = Storage::disk('public')->url($path);
            } catch (\Exception $e) {
                \Log::error('Video upload exception', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return back()->withErrors(['featured_video' => 'Video upload failed: '.$e->getMessage()])->withInput();
            }
        }

        // Preserve user_id (don't allow changing the original creator)
        // If user_id is null, set it to current user (for old listings without user_id)
        if (! $listing->user_id) {
            $data['user_id'] = $request->user()->id;
        } else {
            // Remove user_id from data to preserve original creator
            unset($data['user_id']);
        }

        $listing->update($data);

        // Redirect back to show page if coming from show page, otherwise to index
        if ($request->has('return_to') && $request->get('return_to') === 'show') {
            $slug = \Illuminate\Support\Str::slug($listing->category).'-'.\Illuminate\Support\Str::slug($listing->title).'-'.\Illuminate\Support\Str::slug($listing->location);

            return redirect()->route('listings.show', ['listing' => $listing->id, 'slug' => $slug])->with('success', 'Listing updated');
        }

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

    public function deleteFeaturedVideo(Request $request, Listing $listing)
    {
        if (! $listing->featured_video_url) {
            return response()->json(['error' => 'No featured video found'], 404);
        }

        // Delete file from storage if it's a local file
        $url = $listing->featured_video_url;
        if (str_contains($url, '/storage/listings/videos/')) {
            $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            Storage::disk('public')->delete($path);
        }

        // Remove featured_video_url from listing
        $listing->update(['featured_video_url' => null]);

        return response()->json(['success' => true, 'message' => 'Featured video deleted successfully']);
    }
}
