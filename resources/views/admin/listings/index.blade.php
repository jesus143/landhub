@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Listings</h1>
        <a href="{{ route('admin.listings.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">New Listing</a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="text-left">
                <th class="p-2">ID</th>
                <th class="p-2">Title</th>
                <th class="p-2">Price</th>
                <th class="p-2">Status</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listings as $listing)
            <tr class="border-t">
                <td class="p-2">{{ $listing->id }}</td>
                <td class="p-2">{{ $listing->title }}</td>
                <td class="p-2">{{ number_format($listing->price, 2) }}</td>
                <td class="p-2">{{ $listing->status }}</td>
                <td class="p-2">
                    <a href="{{ route('admin.listings.edit', $listing) }}" class="text-blue-600 mr-2">Edit</a>
                    <form action="{{ route('admin.listings.updateStatus', $listing) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="border p-1">
                            <option value="for_sale" {{ $listing->status === 'for_sale' ? 'selected' : '' }}>for_sale</option>
                            <option value="pending" {{ $listing->status === 'pending' ? 'selected' : '' }}>pending</option>
                            <option value="sold" {{ $listing->status === 'sold' ? 'selected' : '' }}>sold</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $listings->links() }}
    </div>
</div>
@endsection
