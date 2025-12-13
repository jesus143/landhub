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

    <form action="{{ route('admin.listings.update', $listing) }}" method="POST">
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
                <label class="block">Area</label>
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
                <input type="text" name="category" value="{{ old('category', $listing->category) }}" class="w-full border p-2">
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

        <div class="mb-2">
            <label class="block">Main Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url', $listing->image_url) }}" class="w-full border p-2">
        </div>

        <div class="mb-2">
            <label class="block">Media JSON</label>
            <textarea name="media" class="w-full border p-2">{{ old('media', json_encode($listing->media ?? [])) }}</textarea>
            <p class="text-sm text-gray-600">Provide an array of media items as JSON (type: image|video, url).</p>
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
@endsection
