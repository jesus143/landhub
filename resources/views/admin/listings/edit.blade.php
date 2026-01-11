@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-semibold mb-4">Edit Listing</h1>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.listings.update', $listing) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-2">
            <label class="block">Title</label>
            <input type="text" name="title" value="{{ old('title', $listing->title) }}" class="w-full border p-2" required>
        </div>

        <div class="mb-2">
            <label class="block">Description</label>
            <textarea name="description" class="w-full border p-2">{{ old('description', $listing->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-2">
                <label class="block">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $listing->price) }}" class="w-full border p-2" required>
            </div>
            <div class="mb-2">
                <label class="block">Area<small> (Total Square Meters)</small></label>
                <input type="number" step="0.01" name="area" value="{{ old('area', $listing->area) }}" class="w-full border p-2" required>
            </div>
        </div>

        <div class="mb-2">
            <label class="block">Location</label>
            <input type="text" name="location" value="{{ old('location', $listing->location) }}" class="w-full border p-2" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-2">
                <label class="block">Category</label>
                <select name="category" class="w-full border p-2" required>
                    <option value="residential" {{ old('category', $listing->category) === 'residential' ? 'selected' : '' }}>Residential</option>
                    <option value="agricultural" {{ old('category', $listing->category) === 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                    <option value="commercial" {{ old('category', $listing->category) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="block">Status</label>
                <select name="status" class="w-full border p-2">
                    <option value="for_sale" {{ old('status', $listing->status) === 'for_sale' ? 'selected' : '' }}>for_sale</option>
                    <option value="pending" {{ old('status', $listing->status) === 'pending' ? 'selected' : '' }}>pending</option>
                    <option value="sold" {{ old('status', $listing->status) === 'sold' ? 'selected' : '' }}>sold</option>
                </select>
            </div>
        </div>

        @if($listing->image_url)
            <div class="mb-2">
                <label class="block mb-2">Current Main Image</label>
                <div class="relative inline-block group">
                    <img src="{{ $listing->image_url }}" alt="Current main image" class="w-48 h-32 object-cover border rounded" id="main-image-preview">
                    <button
                        type="button"
                        onclick="deleteMainImage({{ $listing->id }})"
                        class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700"
                        title="Delete main image"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <div class="mb-2">
            <label class="block">Main Image (Upload)</label>
            <input type="file" name="main_image" accept="image/*" class="w-full border p-2">
            <p class="text-sm text-gray-600 mt-1">Or provide an image URL below</p>
        </div>

        <div class="mb-2">
            <label class="block">Main Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url', $listing->image_url) }}" class="w-full border p-2" placeholder="https://example.com/image.jpg">
            <p class="text-sm text-gray-600 mt-1">Leave empty if uploading an image above</p>
        </div>

        @php
            $existingMedia = $listing->media ?? [];
            $existingImages = array_filter($existingMedia, fn($item) => ($item['type'] ?? '') === 'image');
        @endphp

        @if(count($existingImages) > 0)
            <div class="mb-4">
                <label class="block mb-2">Existing Images</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="existing-images-grid">
                    @foreach($existingMedia as $index => $item)
                        @if(($item['type'] ?? '') === 'image')
                            <div class="relative group" data-media-index="{{ $index }}" id="media-item-{{ $index }}">
                                <img src="{{ $item['url'] }}" alt="Image {{ $index + 1 }}" class="w-full h-32 object-cover border rounded">
                                <button
                                    type="button"
                                    onclick="deleteMediaImage({{ $listing->id }}, {{ $index }})"
                                    class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700"
                                    title="Delete image"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mb-2">
            <label class="block">Additional Images (Upload)</label>
            <input type="file" name="media_images[]" accept="image/*" multiple class="w-full border p-2">
            <p class="text-sm text-gray-600 mt-1">You can select multiple images</p>
        </div>

        <div class="mb-2">
            <label class="block">Media JSON</label>
            <textarea name="media" id="media-json-textarea" class="w-full border p-2">{{ old('media', json_encode($listing->media ?? [])) }}</textarea>
            <p class="text-sm text-gray-600">Provide an array of media items as JSON (type: image|video, url). Uploaded images above will be added automatically.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-2">
                <label class="block">Latitude</label>
                <input type="text" name="latitude" value="{{ old('latitude', $listing->latitude) }}" class="w-full border p-2">
            </div>
            <div class="mb-2">
                <label class="block">Longitude</label>
                <input type="text" name="longitude" value="{{ old('longitude', $listing->longitude) }}" class="w-full border p-2">
            </div>
        </div>

        <div class="mb-2">
            <label class="block">Nearby Landmarks</label>
            <input type="text" name="nearby_landmarks" value="{{ old('nearby_landmarks', $listing->nearby_landmarks) }}" class="w-full border p-2">
        </div>

        <div class="mb-2">
            <label class="block">Map Link</label>
            <input type="url" name="map_link" value="{{ old('map_link', $listing->map_link) }}" class="w-full border p-2">
        </div>

        <div class="mb-2">
            <label class="block">Featured Video (YouTube URL)</label>
            <input type="url" name="featured_video_url" value="{{ old('featured_video_url', $listing->featured_video_url) }}" class="w-full border p-2" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
            <p class="text-sm text-gray-600 mt-1">Enter a YouTube video URL (full URL or short URL)</p>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $listing->contact_phone) }}" class="w-full border p-2">
            </div>
            <div>
                <label class="block">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $listing->contact_email) }}" class="w-full border p-2">
            </div>
            <div>
                <label class="block">Contact FB Link</label>
                <input type="url" name="contact_fb_link" value="{{ old('contact_fb_link', $listing->contact_fb_link) }}" class="w-full border p-2">
            </div>
        </div>

        <div class="mt-4">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
    </form>
</div>

<script>
    function deleteMediaImage(listingId, mediaIndex) {
        if (!confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                         document.querySelector('input[name="_token"]')?.value;

        fetch(`/admin/listings/${listingId}/media`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                media_index: mediaIndex
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to refresh the media list with correct indices
                window.location.reload();
            } else {
                alert(data.error || 'Error deleting image. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting image. Please try again.');
        });
    }

    function deleteMainImage(listingId) {
        if (!confirm('Are you sure you want to delete the main image? This action cannot be undone.')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                         document.querySelector('input[name="_token"]')?.value;

        fetch(`/admin/listings/${listingId}/main-image`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to refresh the main image display
                window.location.reload();
            } else {
                alert(data.error || 'Error deleting main image. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting main image. Please try again.');
        });
    }
</script>
@endsection
