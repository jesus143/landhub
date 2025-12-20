<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
                    {{ __('Manage Listings') }}
                </h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Create, edit, and manage all property listings
                </p>
            </div>
            <a href="{{ route('admin.listings.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Listing
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($listings as $listing)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">#{{ $listing->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ Str::limit($listing->title, 40) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">{{ $listing->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600 dark:text-emerald-400">₱{{ number_format($listing->price, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                        {{ ucfirst($listing->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.listings.updateStatus', $listing) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors
                                            {{ $listing->status === 'for_sale' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' : '' }}
                                            {{ $listing->status === 'pending' ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-300' : '' }}
                                            {{ $listing->status === 'sold' ? 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' : '' }}
                                        ">
                                            <option value="for_sale" {{ $listing->status === 'for_sale' ? 'selected' : '' }}>For Sale</option>
                                            <option value="pending" {{ $listing->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="sold" {{ $listing->status === 'sold' ? 'selected' : '' }}>Sold</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.listings.edit', $listing) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('listings.show', ['listing' => $listing->id, 'slug' => \Illuminate\Support\Str::slug($listing->title)]) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors" title="View listing">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <p class="text-slate-600 dark:text-slate-400 text-lg font-medium">No listings found</p>
                                    <p class="text-slate-500 dark:text-slate-500 text-sm mt-2">Get started by creating your first listing</p>
                                    <a href="{{ route('admin.listings.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create Listing
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($listings->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $listings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
