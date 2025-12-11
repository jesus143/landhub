<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LandHub - Find Your Perfect Land</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <!-- Local Tailwind CSS -->
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
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">LandHub</span>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors"
                            >
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="px-6 py-2 text-sm font-medium bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm"
                                >
                                    Sign Up
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <button
                        type="button"
                        id="mobile-menu-button"
                        class="md:hidden p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        aria-label="Toggle menu"
                    >
                        <svg id="menu-icon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-slate-200 dark:border-slate-700 mt-2 pt-4">
                    <div class="flex flex-col gap-3">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="px-4 py-2 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition-colors"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="px-4 py-2 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition-colors"
                            >
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="px-4 py-2 text-base font-medium bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm text-center"
                                >
                                    Sign Up
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <script>
            // Mobile menu toggle
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');

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

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    const isClickInside = mobileMenuButton.contains(event.target) || mobileMenu.contains(event.target);
                    if (!isClickInside && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });

                // Close menu on window resize if it becomes desktop size
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });
            });
        </script>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 dark:from-slate-800 dark:via-slate-800 dark:to-slate-900 py-20 lg:py-32 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle, #10b981 1px, transparent 1px); background-size: 50px 50px;"></div>
            </div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-4xl mx-auto">
                    <h1 class="text-5xl lg:text-7xl font-bold mb-6 text-slate-900 dark:text-white">
                        Discover Your Perfect
                        <span class="text-emerald-600 dark:text-emerald-400">Land</span>
                    </h1>
                    <p class="text-xl lg:text-2xl text-slate-600 dark:text-slate-300 mb-10 leading-relaxed">
                        Your trusted platform for finding the ideal land. Browse thousands of verified listings for residential, agricultural, and commercial properties.
                    </p>

                    <!-- Advanced Search & Filters -->
                    <div class="max-w-5xl mx-auto">
                        <form id="search-form" action="#" method="GET" class="shadow-xl rounded-2xl bg-white dark:bg-slate-800 p-4 sm:p-6">
                            <!-- Keyword Search -->
                            <div class="mb-4">
                                <label for="keyword-search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Search by keyword, location, title, or description
                                </label>
                                <div class="flex items-center gap-3 px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-900">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input
                                        type="text"
                                        id="keyword-search"
                                        name="search"
                                        placeholder="e.g., 'Manila', 'beachfront', '500 sqm'..."
                                        class="flex-1 bg-transparent text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <!-- Filters Row -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Location Filter -->
                                <div>
                                    <label for="location-filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Location
                                    </label>
                                    <select
                                        id="location-filter"
                                        name="location"
                                        class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    >
                                        <option value="">All Locations</option>
                                        <option value="manila">Manila</option>
                                        <option value="cebu">Cebu</option>
                                        <option value="davao">Davao</option>
                                        <option value="laguna">Laguna</option>
                                        <option value="cavite">Cavite</option>
                                        <option value="batangas">Batangas</option>
                                        <option value="pampanga">Pampanga</option>
                                        <option value="iloilo">Iloilo</option>
                                    </select>
                                </div>

                                <!-- Min Price -->
                                <div>
                                    <label for="min-price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Min Price (₱)
                                    </label>
                                    <input
                                        type="number"
                                        id="min-price"
                                        name="min_price"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    />
                                </div>

                                <!-- Max Price -->
                                <div>
                                    <label for="max-price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Max Price (₱)
                                    </label>
                                    <input
                                        type="number"
                                        id="max-price"
                                        name="max_price"
                                        placeholder="No limit"
                                        min="0"
                                        step="1000"
                                        class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    />
                                </div>
                            </div>

                            <!-- Search Button -->
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button
                                    type="submit"
                                    class="flex-1 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors shadow-lg"
                                >
                                    Search Listings
                                </button>
                                <button
                                    type="button"
                                    id="clear-filters"
                                    class="px-6 py-4 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium rounded-xl transition-colors"
                                >
                                    Clear Filters
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-3 gap-8 mt-12 max-w-2xl mx-auto">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">1,000+</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Active Listings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">50+</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Locations</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">24/7</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Filters -->
        <section class="py-12 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-wrap justify-center gap-4">
                    <button data-category="" class="category-filter px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg shadow-sm hover:bg-emerald-700 transition-colors">
                        All Listings
                    </button>
                    <button data-category="residential" class="category-filter px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        Residential
                    </button>
                    <button data-category="agricultural" class="category-filter px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        Agricultural
                    </button>
                    <button data-category="commercial" class="category-filter px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        Commercial
                    </button>
                </div>
            </div>
        </section>

        <!-- Search Results Section -->
        <section id="search-results" class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Results Header -->
                <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            Search Results
                        </h2>
                        <p id="results-count" class="text-slate-600 dark:text-slate-400">
                            Showing <span id="results-number">0</span> listings
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <select id="sort-by" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="newest">Newest First</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="size-large">Size: Largest First</option>
                        </select>
                    </div>
                </div>

                <!-- Results Grid -->
                <div id="listings-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Sample listings will be populated here by JavaScript -->
                </div>

                <!-- No Results Message -->
                <div id="no-results" class="hidden text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">No listings found</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-6">Try adjusting your search criteria or filters</p>
                    <button id="clear-all-filters" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                        Clear All Filters
                    </button>
                </div>

                <!-- Loading State -->
                <div id="loading" class="hidden text-center py-16">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
                    <p class="mt-4 text-slate-600 dark:text-slate-400">Loading listings...</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white dark:bg-slate-800">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4 text-slate-900 dark:text-white">
                        Why Choose LandHub?
                    </h2>
                    <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                        Everything you need to find and purchase your perfect piece of land
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                    <!-- Feature 1 -->
                    <div class="text-center p-8 rounded-2xl bg-slate-50 dark:bg-slate-900 hover:shadow-xl transition-shadow">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-slate-900 dark:text-white">Advanced Search</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Filter by location, price range, lot size, category, and status. Find exactly what you're looking for with our powerful search tools.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center p-8 rounded-2xl bg-slate-50 dark:bg-slate-900 hover:shadow-xl transition-shadow">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-slate-900 dark:text-white">Verified Listings</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            All listings are verified with accurate information, high-quality photos, detailed descriptions, and precise location data.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center p-8 rounded-2xl bg-slate-50 dark:bg-slate-900 hover:shadow-xl transition-shadow">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-slate-900 dark:text-white">Interactive Maps</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            View properties on detailed maps with coordinates, nearby landmarks, roads, and surrounding areas for better decision-making.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50 dark:from-slate-900 dark:to-slate-800">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4 text-slate-900 dark:text-white">
                        How It Works
                    </h2>
                    <p class="text-xl text-slate-600 dark:text-slate-400">
                        Find your perfect land in three simple steps
                    </p>
        </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold">
                            1
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-white">Search & Filter</h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            Use our advanced search to find land by location, size, price, and category
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold">
                            2
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-white">View Details</h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            Explore photos, maps, descriptions, and contact information for each listing
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold">
                            3
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-white">Contact & Purchase</h3>
                        <p class="text-slate-600 dark:text-slate-400">
                            Reach out to sellers directly through provided contact information
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-emerald-600 dark:bg-emerald-700">
            <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6 text-white">
                    Ready to Find Your Perfect Land?
                </h2>
                <p class="text-xl text-emerald-50 mb-10">
                    Join thousands of satisfied customers who found their ideal property through LandHub
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a
                        href="#"
                        class="px-8 py-4 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-slate-50 transition-colors shadow-lg"
                    >
                        Browse All Listings
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="px-8 py-4 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl transition-colors shadow-lg border-2 border-white/20"
                        >
                            Create Free Account
                        </a>
        @endif
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 dark:bg-black text-slate-300 py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-white">LandHub</span>
                        </div>
                        <p class="text-slate-400 text-sm">
                            Your trusted platform for finding the perfect land. Browse, search, and discover your ideal property.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Browse</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">All Listings</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Residential</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Agricultural</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Commercial</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Company</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">About Us</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Contact</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Help Center</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">FAQs</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Seller Guide</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors">Buyer Guide</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-slate-400 text-sm">
                        © {{ date('Y') }} LandHub. All rights reserved.
                    </p>
                    <div class="flex gap-4 mt-4 md:mt-0">
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Search & Filter JavaScript -->
        <script>
            // Sample listings data (replace with API call later)
            const sampleListings = [
                {
                    id: 1,
                    title: "Beachfront Property in Boracay",
                    location: "Boracay, Aklan",
                    price: 15000000,
                    area: 500,
                    category: "residential",
                    description: "Stunning beachfront land with white sand beach access. Perfect for resort development or private villa.",
                    image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop",
                    status: "For Sale"
                },
                {
                    id: 2,
                    title: "Agricultural Land in Laguna",
                    location: "Laguna",
                    price: 3500000,
                    area: 2000,
                    category: "agricultural",
                    description: "Fertile agricultural land suitable for farming. Has irrigation system and road access.",
                    image: "https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=800&h=600&fit=crop",
                    status: "For Sale"
                },
                {
                    id: 3,
                    title: "Commercial Lot in Makati",
                    location: "Makati, Manila",
                    price: 25000000,
                    area: 300,
                    category: "commercial",
                    description: "Prime commercial location in business district. High foot traffic area, perfect for retail or office building.",
                    image: "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop",
                    status: "For Sale"
                },
                {
                    id: 4,
                    title: "Mountain View Property in Baguio",
                    location: "Baguio",
                    price: 8500000,
                    area: 800,
                    category: "residential",
                    description: "Cool climate property with panoramic mountain views. Ideal for vacation home or retirement.",
                    image: "https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop",
                    status: "For Sale"
                },
                {
                    id: 5,
                    title: "Rice Farm in Nueva Ecija",
                    location: "Nueva Ecija",
                    price: 4200000,
                    area: 5000,
                    category: "agricultural",
                    description: "Large rice farm with established irrigation. Includes farmhouse and storage facilities.",
                    image: "https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=600&fit=crop",
                    status: "For Sale"
                },
                {
                    id: 6,
                    title: "Residential Lot in Cebu",
                    location: "Cebu",
                    price: 5500000,
                    area: 400,
                    category: "residential",
                    description: "Corner lot in gated subdivision. Near schools and shopping centers. Ready for construction.",
                    image: "https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&h=600&fit=crop",
                    status: "For Sale"
                }
            ];

            let currentListings = [...sampleListings];
            let currentCategory = '';
            let currentSort = 'newest';

            // Format price
            function formatPrice(price) {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 0
                }).format(price);
            }

            // Render listings
            function renderListings(listings) {
                const grid = document.getElementById('listings-grid');
                const noResults = document.getElementById('no-results');
                const resultsNumber = document.getElementById('results-number');
                const resultsCount = document.getElementById('results-count');

                resultsNumber.textContent = listings.length;
                resultsCount.textContent = `Showing ${listings.length} listing${listings.length !== 1 ? 's' : ''}`;

                if (listings.length === 0) {
                    grid.classList.add('hidden');
                    noResults.classList.remove('hidden');
                    return;
                }

                grid.classList.remove('hidden');
                noResults.classList.add('hidden');

                grid.innerHTML = listings.map(listing => `
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="relative h-48 bg-slate-200 dark:bg-slate-700">
                            <img src="${listing.image}" alt="${listing.title}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/800x600?text=Land+Listing'">
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                    ${listing.status}
                                </span>
                            </div>
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1 bg-black/50 text-white text-xs font-semibold rounded-full backdrop-blur-sm">
                                    ${listing.category.charAt(0).toUpperCase() + listing.category.slice(1)}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 line-clamp-2">
                                ${listing.title}
                            </h3>
                            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400 mb-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm">${listing.location}</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2">
                                ${listing.description}
                            </p>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                        ${formatPrice(listing.price)}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        ${listing.area} sqm
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="block w-full text-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                `).join('');
            }

            // Filter listings
            function filterListings() {
                const searchInput = document.getElementById('keyword-search').value.toLowerCase();
                const locationFilter = document.getElementById('location-filter').value.toLowerCase();
                const minPrice = parseFloat(document.getElementById('min-price').value) || 0;
                const maxPrice = parseFloat(document.getElementById('max-price').value) || Infinity;

                let filtered = sampleListings.filter(listing => {
                    // Category filter
                    if (currentCategory && listing.category !== currentCategory) return false;

                    // Keyword search (title, location, description)
                    if (searchInput) {
                        const matchesSearch = 
                            listing.title.toLowerCase().includes(searchInput) ||
                            listing.location.toLowerCase().includes(searchInput) ||
                            listing.description.toLowerCase().includes(searchInput) ||
                            listing.price.toString().includes(searchInput);
                        if (!matchesSearch) return false;
                    }

                    // Location filter
                    if (locationFilter) {
                        if (!listing.location.toLowerCase().includes(locationFilter)) return false;
                    }

                    // Price range
                    if (listing.price < minPrice || listing.price > maxPrice) return false;

                    return true;
                });

                // Sort listings
                filtered.sort((a, b) => {
                    switch (currentSort) {
                        case 'price-low':
                            return a.price - b.price;
                        case 'price-high':
                            return b.price - a.price;
                        case 'size-large':
                            return b.area - a.area;
                        default:
                            return b.id - a.id; // newest first
                    }
                });

                currentListings = filtered;
                renderListings(filtered);
            }

            // Event listeners
            document.addEventListener('DOMContentLoaded', function() {
                // Initial render
                renderListings(sampleListings);

                // Search form submit
                document.getElementById('search-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    filterListings();
                });

                // Filter inputs
                document.getElementById('location-filter').addEventListener('change', filterListings);
                document.getElementById('min-price').addEventListener('input', filterListings);
                document.getElementById('max-price').addEventListener('input', filterListings);
                document.getElementById('keyword-search').addEventListener('input', filterListings);

                // Sort
                document.getElementById('sort-by').addEventListener('change', function(e) {
                    currentSort = e.target.value;
                    filterListings();
                });

                // Category filters
                document.querySelectorAll('.category-filter').forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Update active state
                        document.querySelectorAll('.category-filter').forEach(b => {
                            b.classList.remove('bg-emerald-600', 'text-white');
                            b.classList.add('bg-slate-100', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
                        });
                        this.classList.remove('bg-slate-100', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
                        this.classList.add('bg-emerald-600', 'text-white');

                        currentCategory = this.dataset.category;
                        filterListings();
                    });
                });

                // Clear filters
                document.getElementById('clear-filters').addEventListener('click', function() {
                    document.getElementById('keyword-search').value = '';
                    document.getElementById('location-filter').value = '';
                    document.getElementById('min-price').value = '';
                    document.getElementById('max-price').value = '';
                    currentCategory = '';
                    document.querySelectorAll('.category-filter').forEach(b => {
                        b.classList.remove('bg-emerald-600', 'text-white');
                        b.classList.add('bg-slate-100', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
                    });
                    document.querySelector('.category-filter[data-category=""]').classList.remove('bg-slate-100', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
                    document.querySelector('.category-filter[data-category=""]').classList.add('bg-emerald-600', 'text-white');
                    filterListings();
                });

                document.getElementById('clear-all-filters').addEventListener('click', function() {
                    document.getElementById('clear-filters').click();
                });
            });
        </script>
    </body>
</html>
