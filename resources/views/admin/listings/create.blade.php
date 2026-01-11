@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-semibold mb-4">Create Listing</h1>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.listings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-2">
            <label class="block">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border p-2" required>
        </div>

        <div class="mb-2">
            <label class="block">Description</label>
            <textarea name="description" class="w-full border p-2">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-2">
                <label class="block">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full border p-2" required>
            </div>
            <div class="mb-2">
                <label class="block">Area <small> (Total Square Meters)</small></label>
                <input type="number" step="0.01" name="area" value="{{ old('area') }}" class="w-full border p-2" required>
            </div>
        </div>

        <div class="mb-2">
            <label class="block">Location</label>
            <input type="text" name="location" value="{{ old('location') }}" class="w-full border p-2" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-2">
                <label class="block">Category</label>
                <select name="category" class="w-full border p-2" required>
                    <option value="residential" {{ old('category', 'residential') === 'residential' ? 'selected' : '' }}>Residential</option>
                    <option value="agricultural" {{ old('category') === 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                    <option value="commercial" {{ old('category') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="block">Status</label>
                <select name="status" class="w-full border p-2">
                    <option value="for_sale">for_sale</option>
                    <option value="pending">pending</option>
                    <option value="sold">sold</option>
                </select>
            </div>
        </div>

        <div class="mb-2">
            <label class="block">Main Image (Upload)</label>
            <input type="file" name="main_image" accept="image/*" class="w-full border p-2">
            <p class="text-sm text-gray-600 mt-1">Or provide an image URL below</p>
        </div>

        <div class="mb-2">
            <label class="block">Main Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url') }}" class="w-full border p-2" placeholder="https://example.com/image.jpg">
            <p class="text-sm text-gray-600 mt-1">Leave empty if uploading an image above</p>
        </div>

        <div class="mb-2">
            <label class="block">Additional Images (Upload)</label>
            <input type="file" name="media_images[]" accept="image/*" multiple class="w-full border p-2">
            <p class="text-sm text-gray-600 mt-1">You can select multiple images</p>
        </div>

        <div class="mb-2">
            <label class="block">Media JSON</label>
            <textarea name="media" class="w-full border p-2" placeholder='[{"type":"image","url":"https://..."},{"type":"video","url":"https://..."}]'>{{ old('media') }}</textarea>
            <p class="text-sm text-gray-600">Provide an array of media items as JSON (type: image|video, url). Uploaded images above will be added automatically.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-2">
                <label class="block">Latitude</label>
                <input type="text" name="latitude" value="{{ old('latitude') }}" class="w-full border p-2">
            </div>
            <div class="mb-2">
                <label class="block">Longitude</label>
                <input type="text" name="longitude" value="{{ old('longitude') }}" class="w-full border p-2">
            </div>
        </div>

        <div class="mb-2">
            <label class="block">Nearby Landmarks</label>
            <input type="text" name="nearby_landmarks" value="{{ old('nearby_landmarks') }}" class="w-full border p-2">
        </div>

        <div class="mb-2">
            <label class="block">Map Link</label>
            <input type="url" name="map_link" value="{{ old('map_link') }}" class="w-full border p-2">
        </div>

        <div class="mb-4 p-4 bg-gray-50 dark:bg-slate-800 rounded-lg border">
            <h3 class="text-lg font-semibold mb-4">Property Details</h3>

            <div class="mb-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_titled" value="1" {{ old('is_titled') ? 'checked' : '' }} class="border p-2">
                    <span>Titled Property</span>
                </label>
            </div>

            <div class="mb-2">
                <label class="block">Trees & Plants on Property</label>
                <textarea name="trees_plants" rows="4" class="w-full border p-2" placeholder="e.g., 70 ka punuan sa Lubi, Hardwoods, Saging, Star Apple, Kawayan">{{ old('trees_plants') }}</textarea>
                <p class="text-sm text-gray-600 mt-1">List all trees, plants, and crops on the property</p>
            </div>

            <div class="mb-2">
                <label class="block">Terrain Type</label>
                <input type="text" name="terrain_type" value="{{ old('terrain_type') }}" class="w-full border p-2" placeholder="e.g., FLAT to Rolling, Hilly, Mountainous">
                <p class="text-sm text-gray-600 mt-1">Describe the terrain/landscape</p>
            </div>

            <div class="mb-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="vehicle_accessible" value="1" {{ old('vehicle_accessible') ? 'checked' : '' }} class="border p-2">
                    <span>Vehicle Accessible</span>
                </label>
                <p class="text-sm text-gray-600 mt-1">Whether vehicles can access the property (Sulod sakyanan)</p>
            </div>

            <div class="mb-2">
                <label class="block">Additional Features & Amenities</label>
                <textarea name="additional_features" rows="3" class="w-full border p-2" placeholder="e.g., Overlooking view, Source of Sand and Gravel, Water source, Electricity, etc.">{{ old('additional_features') }}</textarea>
                <p class="text-sm text-gray-600 mt-1">List any additional features, amenities, or special characteristics</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="mb-2">
                    <label class="block">Property Type</label>
                    <select name="property_type" class="w-full border p-2">
                        <option value="">Select...</option>
                        <option value="LOT" {{ old('property_type') === 'LOT' ? 'selected' : '' }}>LOT</option>
                        <option value="HOUSE" {{ old('property_type') === 'HOUSE' ? 'selected' : '' }}>HOUSE</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="block">Title Status</label>
                    <input type="text" name="title_status" value="{{ old('title_status') }}" class="w-full border p-2" placeholder="e.g., Clean Title, Solo title">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-2">
                    <label class="block">Frontage (meters)</label>
                    <input type="number" step="0.01" name="frontage" value="{{ old('frontage') }}" class="w-full border p-2" placeholder="e.g., 20">
                </div>

                <div class="mb-2">
                    <label class="block">Road Type</label>
                    <input type="text" name="road_type" value="{{ old('road_type') }}" class="w-full border p-2" placeholder="e.g., Cemented, Dirt, Gravel">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-2">
                    <label class="block">Number of Rooms</label>
                    <input type="number" name="num_rooms" value="{{ old('num_rooms') }}" class="w-full border p-2" placeholder="For houses only" min="0">
                </div>

                <div class="mb-2">
                    <label class="block">Beach Frontage (meters)</label>
                    <input type="number" step="0.01" name="beach_frontage" value="{{ old('beach_frontage') }}" class="w-full border p-2" placeholder="e.g., 100">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_fenced" value="1" {{ old('is_fenced') ? 'checked' : '' }} class="border p-2">
                        <span>Fenced</span>
                    </label>
                </div>

                <div class="mb-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_beachfront" value="1" {{ old('is_beachfront') ? 'checked' : '' }} class="border p-2">
                        <span>Beachfront</span>
                    </label>
                </div>
            </div>

            <div class="mb-2">
                <label class="block">Payment Terms</label>
                <textarea name="payment_terms" rows="2" class="w-full border p-2" placeholder="e.g., Cash basis, pwede 2 gives, Installment available">{{ old('payment_terms') }}</textarea>
            </div>
        </div>

        <div class="mb-2">
            <label class="block">Featured Video (Upload)</label>
            <input type="file" name="featured_video" accept="video/*" class="w-full border p-2 {{ $errors->has('featured_video') ? 'border-red-500' : '' }}">
            @error('featured_video')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            <p class="text-sm text-gray-600 mt-1">Or provide a video URL below (YouTube or direct link). Max file size: 100MB. Supported formats: MP4, WebM, OGG, MOV, AVI, MKV</p>
        </div>

        <div class="mb-2">
            <label class="block">Featured Video URL</label>
            <input type="url" name="featured_video_url" value="{{ old('featured_video_url') }}" class="w-full border p-2" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
            <p class="text-sm text-gray-600 mt-1">Enter a YouTube video URL (full URL or short URL) or any direct video link. Leave empty if uploading a video above.</p>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full border p-2">
            </div>
            <div>
                <label class="block">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="w-full border p-2">
            </div>
            <div>
                <label class="block">Contact FB Link</label>
                <input type="url" name="contact_fb_link" value="{{ old('contact_fb_link') }}" class="w-full border p-2">
            </div>
        </div>

        <div class="mt-4">
            <button class="px-4 py-2 bg-green-600 text-white rounded">Create</button>
        </div>
    </form>
</div>
@endsection
