<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $listing->title }} - LandHub</title>
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
                    <div class="flex items-center gap-4">
                        <a href="{{ route('listings.index') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                            Back to Search
                        </a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                Log in
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <!-- Listing Details -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Image -->
                    <div>
                        @if($listing->image_url)
                            <img src="{{ $listing->image_url }}" alt="{{ $listing->title }}" class="w-full h-96 object-cover rounded-xl shadow-lg" onerror="this.src='https://via.placeholder.com/800x600?text=Land+Listing'">
                        @else
                            <div class="w-full h-96 bg-slate-200 dark:bg-slate-700 rounded-xl flex items-center justify-center">
                                <svg class="w-24 h-24 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-3 py-1 bg-emerald-600 text-white text-sm font-semibold rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $listing->status)) }}
                                </span>
                                <span class="px-3 py-1 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-full">
                                    {{ ucfirst($listing->category) }}
                                </span>
                            </div>
                            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">
                                {{ $listing->title }}
                            </h1>
                            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400 mb-6">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-lg">{{ $listing->location }}</span>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg">
                            <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">
                                ₱{{ number_format($listing->price, 0) }}
                            </div>
                            <div class="text-lg text-slate-600 dark:text-slate-400 mb-6">
                                {{ number_format($listing->area, 0) }} sqm
                            </div>

                            @if($listing->description)
                                <div class="mb-6">
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Description</h3>
                                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                        {{ $listing->description }}
                                    </p>
                                </div>
                            @endif

                            @if($listing->nearby_landmarks)
                                <div class="mb-6">
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Nearby Landmarks</h3>
                                    <p class="text-slate-600 dark:text-slate-400">
                                        {{ $listing->nearby_landmarks }}
                                    </p>
                                </div>
                            @endif

                            @if($listing->latitude && $listing->longitude)
                                <div class="mb-6">
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Coordinates</h3>
                                    <p class="text-slate-600 dark:text-slate-400">
                                        {{ $listing->latitude }}, {{ $listing->longitude }}
                                    </p>
                                    @if($listing->map_link)
                                        <a href="{{ $listing->map_link }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:underline mt-2 inline-block">
                                            View on Map →
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Contact Information</h3>
                            <div class="space-y-3">
                                @if($listing->contact_phone)
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <a href="tel:{{ $listing->contact_phone }}" class="text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400">
                                            {{ $listing->contact_phone }}
                                        </a>
                                    </div>
                                @endif
                                @if($listing->contact_email)
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a href="mailto:{{ $listing->contact_email }}" class="text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400">
                                            {{ $listing->contact_email }}
                                        </a>
                                    </div>
                                @endif
                                @if($listing->contact_fb_link)
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                        </svg>
                                        <a href="{{ $listing->contact_fb_link }}" target="_blank" class="text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400">
                                            Facebook Page
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

