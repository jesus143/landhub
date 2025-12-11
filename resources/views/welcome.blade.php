<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LandHub - Find Your Perfect Land</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
        <!-- Navigation -->
        <header class="w-full border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615]">
            <nav class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-[#f53003] dark:text-[#FF4433]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">LandHub</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="px-5 py-1.5 text-sm border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm transition-colors"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="px-5 py-1.5 text-sm border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm transition-colors"
                        >
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="px-5 py-1.5 text-sm bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white rounded-sm transition-colors"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-24">
            <div class="text-center mb-12">
                <h1 class="text-4xl lg:text-6xl font-bold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">
                    Find Your Perfect
                    <span class="text-[#f53003] dark:text-[#FF4433]">Land</span>
                </h1>
                <p class="text-lg lg:text-xl text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto mb-8">
                    Browse thousands of land listings. From residential plots to agricultural land, find the perfect property for your needs.
                </p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form action="#" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search by location, title, or description..."
                            class="flex-1 px-5 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#706f6c] dark:placeholder:text-[#A1A09A] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
                        />
                        <button
                            type="submit"
                            class="px-8 py-3 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white rounded-sm transition-colors font-medium"
                        >
                            Search
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="flex flex-wrap justify-center gap-3 mb-16">
                <a href="#" class="px-4 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm text-sm transition-colors">
                    Residential
                </a>
                <a href="#" class="px-4 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm text-sm transition-colors">
                    Agricultural
                </a>
                <a href="#" class="px-4 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm text-sm transition-colors">
                    Commercial
                </a>
                <a href="#" class="px-4 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm text-sm transition-colors">
                    All Listings
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-white dark:bg-[#161615] border-t border-b border-[#e3e3e0] dark:border-[#3E3E3A] py-16">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center mb-12 text-[#1b1b18] dark:text-[#EDEDEC]">
                    Why Choose LandHub?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#f53003] dark:text-[#FF4433]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">Advanced Search</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            Filter by location, price range, lot size, category, and status to find exactly what you're looking for.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#f53003] dark:text-[#FF4433]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">Verified Listings</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            All listings are verified with accurate information, photos, and location details.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#f53003] dark:text-[#FF4433]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">Map Integration</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            View properties on interactive maps with coordinates and nearby landmarks.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-16 text-center">
            <h2 class="text-3xl font-bold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">
                Ready to Find Your Land?
            </h2>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-8">
                Browse our extensive collection of land listings or create an account to get started.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                    href="#"
                    class="px-8 py-3 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white rounded-sm transition-colors font-medium"
                >
                    Browse Listings
                </a>
                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="px-8 py-3 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-sm transition-colors font-medium"
                    >
                        Create Account
                    </a>
                @endif
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] py-8">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#f53003] dark:text-[#FF4433]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">LandHub</span>
                    </div>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        © {{ date('Y') }} LandHub. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
