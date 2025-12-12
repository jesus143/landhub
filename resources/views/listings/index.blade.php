<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Search and filter land listings by location, price, category, and more. Find your perfect property with LandHub.">
        <title>Search Listings - LandHub</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="{{ asset('tailwindcss.js') }}"></script>
        <style type="text/tailwindcss">
            @theme {
                --color-clifford: #da373d;
            }
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen">
        <!-- Navigation -->
        <header class="w-full bg-white dark:bg-slate-800 shadow-sm sticky top-0 z-50">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">LandHub</span>
                    </a>
                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm">
                                    Sign Up
                                </a>
                            @endif
                        @endauth
                    </div>
                    <button type="button" id="mobile-menu-button" class="md:hidden p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500" aria-label="Toggle menu">
                        <svg id="menu-icon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-slate-200 dark:border-slate-700 mt-2 pt-4">
                    <div class="flex flex-col gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 text-base font-medium bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm text-center">Sign Up</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <!-- Breadcrumbs -->
        <section class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center gap-2 text-sm">
                    <a href="{{ route('welcome') }}" class="text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400">Home</a>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 dark:text-white font-medium">Search Listings</span>
                </nav>
            </div>
        </section>

        <!-- Search & Filters Section -->
        <section class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form method="GET" action="{{ route('listings.index') }}" class="space-y-4">
                    <!-- Keyword Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Search by keyword, location, title, or description
                        </label>
                        <div class="flex items-center gap-3 px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-900">
                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="e.g., 'Manila', 'beachfront', '500 sqm'..."
                                class="flex-1 bg-transparent text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none"
                            />
                        </div>
                    </div>

                    <!-- Filters Row -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Location Filter -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Location
                            </label>
                            <input
                                type="text"
                                id="location"
                                name="location"
                                value="{{ request('location') }}"
                                placeholder="Enter location..."
                                class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            />
                        </div>

                        <!-- Min Price -->
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Min Price (₱)
                            </label>
                            <input
                                type="number"
                                id="min_price"
                                name="min_price"
                                value="{{ request('min_price') }}"
                                placeholder="0"
                                min="0"
                                step="1000"
                                class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            />
                        </div>

                        <!-- Max Price -->
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Max Price (₱)
                            </label>
                            <input
                                type="number"
                                id="max_price"
                                name="max_price"
                                value="{{ request('max_price') }}"
                                placeholder="No limit"
                                min="0"
                                step="1000"
                                class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            />
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Category
                            </label>
                            <select
                                id="category"
                                name="category"
                                class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            >
                                <option value="">All Categories</option>
                                <option value="residential" {{ request('category') === 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="agricultural" {{ request('category') === 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                <option value="commercial" {{ request('category') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="flex-1 px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
                            Search Listings
                        </button>
                        <a href="{{ route('listings.index') }}" class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium rounded-lg transition-colors text-center">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </section>

        <!-- Results Section -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Active Filters -->
                @if(request()->anyFilled(['search', 'location', 'min_price', 'max_price', 'category']))
                    <div class="mb-6 flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 rounded-full text-sm">
                                Search: "{{ request('search') }}"
                                <a href="{{ route('listings.index', request()->except('search')) }}" class="hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            </span>
                        @endif
                        @if(request('location'))
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 rounded-full text-sm">
                                Location: {{ request('location') }}
                                <a href="{{ route('listings.index', request()->except('location')) }}" class="hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            </span>
                        @endif
                        @if(request('category'))
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 rounded-full text-sm">
                                Category: {{ ucfirst(request('category')) }}
                                <a href="{{ route('listings.index', request()->except('category')) }}" class="hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            </span>
                        @endif
                        @if(request('min_price') || request('max_price'))
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 rounded-full text-sm">
                                Price: ₱{{ number_format(request('min_price', 0), 0) }} - {{ request('max_price') ? '₱' . number_format(request('max_price'), 0) : '∞' }}
                                <a href="{{ route('listings.index', request()->except('min_price', 'max_price')) }}" class="hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            </span>
                        @endif
                    </div>
                @endif

                <!-- Results Header -->
                <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            Search Results
                        </h2>
                        <p class="text-slate-600 dark:text-slate-400">
                            Showing {{ $listings->count() }} of {{ $listings->total() }} listing{{ $listings->total() !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <form method="GET" action="{{ route('listings.index') }}" class="flex gap-3">
                        @foreach(request()->except('sort') as $key => $value)
                            @if($value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="sort" onchange="this.form.submit()" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price-low" {{ request('sort') === 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price-high" {{ request('sort') === 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="size-large" {{ request('sort') === 'size-large' ? 'selected' : '' }}>Size: Largest First</option>
                        </select>
                    </form>
                </div>

                <!-- Results Grid -->
                @if($listings->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($listings as $listing)
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                                <div class="relative h-48 bg-slate-200 dark:bg-slate-700">
                                    @if($listing->image_url)
                                        <img src="{{ $listing->image_url }}" alt="{{ $listing->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.src='https://via.placeholder.com/800x600?text=Land+Listing'">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 right-3">
                                        <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                            {{ ucfirst(str_replace('_', ' ', $listing->status)) }}
                                        </span>
                                    </div>
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1 bg-black/50 text-white text-xs font-semibold rounded-full backdrop-blur-sm">
                                            {{ ucfirst($listing->category) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 line-clamp-2">
                                        {{ $listing->title }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400 mb-3">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-sm">{{ $listing->location }}</span>
                                    </div>
                                    @if($listing->description)
                                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2">
                                            {{ $listing->description }}
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                                ₱{{ number_format($listing->price, 0) }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                {{ number_format($listing->area, 0) }} sqm
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <a href="{{ route('listings.show', $listing) }}" class="flex-1 text-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                            View Details
                                        </a>
                                        @if($listing->contact_fb_link)
                                            <button onclick="openMessenger('{{ $listing->contact_fb_link }}')" class="flex-1 text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2" title="Contact via Messenger">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 0C5.373 0 0 4.925 0 11c0 1.906.52 3.7 1.42 5.24L0 24l7.8-4.05C9.4 20.55 10.65 20.8 12 20.8c6.627 0 12-4.925 12-11S18.627 0 12 0zm0 18.8c-1.15 0-2.25-.2-3.3-.55l-.45-.15-4.65 2.4 1.05-4.5-.3-.45C3.7 14.3 3.2 12.7 3.2 11c0-4.4 3.9-8 8.8-8s8.8 3.6 8.8 8-3.9 8-8.8 8z"/>
                                                </svg>
                                                <span class="hidden sm:inline">Messenger</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($listings->hasPages())
                        <div class="mt-8 flex justify-center">
                            <div class="flex items-center gap-2">
                                {{ $listings->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">No listings found</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Try adjusting your search criteria or filters</p>
                        <a href="{{ route('listings.index') }}" class="inline-block px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                            Clear All Filters
                        </a>
                    </div>
                @endif
            </div>
        </section>

        <script>
            function openMessenger(fbLink) {
                // Extract user ID from Facebook link (format: https://www.facebook.com/messages/t/USER_ID)
                const userId = fbLink.split('/').pop();
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                if (isMobile) {
                    // Try to open Messenger app on mobile, fallback to web if app not installed
                    const messengerAppUrl = `fb-messenger://user/${userId}`;
                    const messengerWebUrl = `https://m.facebook.com/messages/t/${userId}`;

                    // Try opening the app
                    window.location.href = messengerAppUrl;

                    // Fallback to web version after a short delay
                    setTimeout(function() {
                        window.open(messengerWebUrl, '_blank');
                    }, 1000);
                } else {
                    // Desktop: open in new tab
                    window.open(fbLink, '_blank');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');

                if (mobileMenuButton) {
                    mobileMenuButton.addEventListener('click', function() {
                        const isHidden = mobileMenu.classList.contains('hidden');
                        if (isHidden) {
                            mobileMenu.classList.remove('hidden');
                            menuIcon.classList.add('hidden');
                            closeIcon.classList.remove('hidden');
                        } else {
                            mobileMenu.classList.add('hidden');
                            menuIcon.classList.remove('hidden');
                            closeIcon.classList.add('hidden');
                        }
                    });
                }
            });
        </script>
    </body>
</html>


clear
