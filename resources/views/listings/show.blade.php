<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ \Illuminate\Support\Str::limit($listing->description ?? $listing->title . ' - ' . $listing->location, 160) }}">
        @php
            $allMedia = $listing->getAllMedia();
            $firstMedia = collect($allMedia)->first();
        @endphp
        <meta property="og:title" content="{{ $listing->title }}">
        <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($listing->description ?? $listing->title . ' - ' . $listing->location, 160) }}">
        @if($firstMedia && $firstMedia['type'] === 'image')
            <meta property="og:image" content="{{ $firstMedia['url'] }}">
        @elseif($listing->image_url)
            <meta property="og:image" content="{{ $listing->image_url }}">
        @endif
        <title>{{ $listing->title }} - LandHub</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="{{ asset('tailwindcss.js') }}"></script>
        @auth
            @vite(['resources/js/app.js'])
        @endauth
        <style type="text/tailwindcss">
            @theme {
                --color-clifford: #da373d;
            }

            #map {
                height: 400px;
                width: 100%;
                border-radius: 0.75rem;
                z-index: 1;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen">
        <!-- Navigation -->
        @auth
            @include('layouts.navigation')
        @else
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
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                Log in
                            </a>
                        </div>
                    </div>
                </nav>
            </header>
        @endauth

        <!-- Breadcrumbs -->
        <section class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center gap-2 text-sm">
                    <a href="{{ route('welcome') }}" class="text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400">Home</a>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('listings.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400">Listings</a>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 dark:text-white font-medium line-clamp-1">{{ \Illuminate\Support\Str::limit($listing->title, 40) }}</span>
                </nav>
            </div>
        </section>



        <!-- Listing Details -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Media Gallery -->
                    <div>
                        @if(count($allMedia) > 0)
                            <div class="space-y-4">
                                <!-- Main Media Display -->
                                <div class="relative group">
                                    <div class="w-full h-96 bg-slate-200 dark:bg-slate-700 rounded-xl overflow-hidden shadow-lg">
                                        @foreach($allMedia as $index => $item)
                                            <div id="media-main-{{ $index }}" class="media-main-item {{ $index === 0 ? '' : 'hidden' }}">
                                                @if($item['type'] === 'video')
                                                    <video

                                                        src="{{ $item['url'] }}"
                                                        class="w-full h-full object-cover hidden"
                                                        controls
                                                        preload="metadata"
                                                        onclick="openLightbox({{ $index }})"
                                                    ></video>
                                                     <iframe width="100%" height="315" src="https://www.youtube.com/embed/MLATROF5KMk?si=8tzWrBQqcSO5kU04" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                                                @else
                                                    <img
                                                        src="{{ $item['url'] }}"
                                                        alt="{{ $listing->title }} - Media {{ $index + 1 }}"
                                                        class="w-full h-full object-cover cursor-pointer transition-transform hover:scale-105"
                                                        onclick="openLightbox({{ $index }})"
                                                        onerror="this.src='https://via.placeholder.com/800x600?text=Land+Listing'"
                                                    >
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Navigation Arrows -->
                                    @if(count($allMedia) > 1)
                                        <button
                                            onclick="changeMedia(-1)"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 dark:bg-slate-800/90 hover:bg-white dark:hover:bg-slate-800 text-slate-900 dark:text-white p-2 rounded-full shadow-lg transition-all opacity-0 group-hover:opacity-100"
                                            aria-label="Previous media"
                                        >
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button
                                            onclick="changeMedia(1)"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 dark:bg-slate-800/90 hover:bg-white dark:hover:bg-slate-800 text-slate-900 dark:text-white p-2 rounded-full shadow-lg transition-all opacity-0 group-hover:opacity-100"
                                            aria-label="Next media"
                                        >
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- Media Type Badge -->
                                    <div class="absolute top-4 left-4">
                                        <span id="media-type-badge" class="px-3 py-1 bg-black/50 text-white text-xs font-semibold rounded-full backdrop-blur-sm">
                                            {{ $allMedia[0]['type'] === 'video' ? 'Video' : 'Image' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Thumbnail Strip -->
                                @if(count($allMedia) > 1)
                                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                                        @foreach($allMedia as $index => $item)
                                            <button
                                                onclick="selectMedia({{ $index }})"
                                                id="thumbnail-{{ $index }}"
                                                class="thumbnail-item flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-emerald-600 dark:border-emerald-400' : 'border-transparent hover:border-slate-300 dark:hover:border-slate-600' }} transition-all"
                                                aria-label="View media {{ $index + 1 }}"
                                            >
                                                @if($item['type'] === 'video')
                                                    <div class="relative w-full h-full bg-slate-300 dark:bg-slate-600">
                                                        <video
                                                            src="{{ $item['url'] }}"
                                                            class="w-full h-full object-cover"
                                                            preload="metadata"
                                                            muted
                                                        ></video>
                                                        <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M8 5v14l11-7z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @else
                                                    <img
                                                        src="{{ $item['url'] }}"
                                                        alt="Thumbnail {{ $index + 1 }}"
                                                        class="w-full h-full object-cover"
                                                        onerror="this.src='https://via.placeholder.com/80x80?text=Image'"
                                                    >
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                            @if($listing->featured_video_url)
                                <h3 class="text-xl font-semibold mb-4">Featured Video</h3>
                                <div class="w-full rounded-lg overflow-hidden shadow-lg">
                                    <iframe width="100%" height="315" src="{{ $listing->getFeaturedVideoEmbedUrl() }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen class="w-full"></iframe>
                                </div>
                            @endif


                            </div>

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

                            @if(!empty($listing->price_history) && is_array($listing->price_history))
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Price History</h3>
                                    <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                        @foreach($listing->price_history as $ph)
                                            <li class="flex items-center justify-between">
                                                <span>{{ \Illuminate\Support\Carbon::parse($ph['date'])->format('Y-m-d') }}</span>
                                                <span class="font-medium">₱{{ number_format($ph['price'], 0) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

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
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Location Map</h3>
                                    <div class="mb-3">
                                        <iframe
                                            width="100%"
                                            height="400"
                                            style="border:0; border-radius:.75rem;"
                                            loading="lazy"
                                            allowfullscreen
                                            referrerpolicy="no-referrer-when-downgrade"
                                            src="https://www.google.com/maps?q={{ $listing->latitude }},{{ $listing->longitude }}&z=15&output=embed">
                                        </iframe>
                                    </div>
                                    <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
                                        <span>Coordinates: {{ $listing->latitude }}, {{ $listing->longitude }}</span>
                                        @if($listing->map_link)
                                            <a href="{{ $listing->map_link }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Open in Google Maps
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>



                        <!-- Contact Information -->
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Contact Information</h3>


                            @auth


                                @php

                                    $sellerEmail = $listing->contact_email;
                                    $seller = $sellerEmail ? \App\Models\User::where('email', $sellerEmail)->first() : null;

                                    // dd($seller);
                                @endphp


                                @if($seller && $seller->id !== auth()->id())
                                    <div class="mb-6">
                                        <a href="{{ route('messages.create.listing', $listing) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Contact via platform
                                        </a>
                                    </div>
                                @else

                                @endif
                            @else
                                <div class="mb-6">
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        Contact via platform
                                    </a>
                                </div>
                            @endauth

                            <div class="space-y-3 mb-6">
                                @if($listing->contact_fb_link)
                                    <button onclick="openMessenger('{{ $listing->contact_fb_link }}')" class="flex-1 text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2" title="Contact via Messenger">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0C5.373 0 0 4.925 0 11c0 1.906.52 3.7 1.42 5.24L0 24l7.8-4.05C9.4 20.55 10.65 20.8 12 20.8c6.627 0 12-4.925 12-11S18.627 0 12 0zm0 18.8c-1.15 0-2.25-.2-3.3-.55l-.45-.15-4.65 2.4 1.05-4.5-.3-.45C3.7 14.3 3.2 12.7 3.2 11c0-4.4 3.9-8 8.8-8s8.8 3.6 8.8 8-3.9 8-8.8 8z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Contact via Messenger</span>
                                    </button>
                                @endif
                            </div>

                            <!-- Share Buttons -->
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Share this listing</h4>
                                <div class="flex gap-2">
                                    <button onclick="shareOnFacebook()" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                        </svg>
                                        Facebook
                                    </button>
                                    <button onclick="shareOnTwitter()" class="flex-1 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                                        </svg>
                                        Twitter
                                    </button>
                                    <button onclick="copyLink()" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors" title="Copy link">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- Comments Section -->
        <section class="py-12 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="px-8 pt-8 pb-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Comments & Questions</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Share your thoughts or ask about this property</p>
                    </div>

                    <!-- Comment Form -->
                    <div class="p-8">
                        @php
                            $commentSlug = \Illuminate\Support\Str::slug($listing->category) . '-' . \Illuminate\Support\Str::slug($listing->title) . '-' . \Illuminate\Support\Str::slug($listing->location);
                        @endphp

                        @if(session('success'))
                            <div class="mb-6 p-4 rounded-lg {{ session('comment_pending') ? 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800' : 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800' }}">
                                <div class="flex items-start gap-3">
                                    @if(session('comment_pending'))
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium {{ session('comment_pending') ? 'text-yellow-800 dark:text-yellow-300' : 'text-emerald-800 dark:text-emerald-300' }}">
                                            {{ session('success') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form id="comment-form" method="POST" action="{{ route('listings.comments.store', ['listing' => $listing->id, 'slug' => $commentSlug]) }}" class="mb-8">
                            @csrf
                            <div class="space-y-4">
                                @guest
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="guest_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Your Name <span class="text-red-500">*</span></label>
                                            <input
                                                id="guest_name"
                                                name="guest_name"
                                                value="{{ old('guest_name') }}"
                                                class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                                                placeholder="Enter your name"
                                                required
                                            >
                                            @error('guest_name')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="guest_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email (optional)</label>
                                            <input
                                                id="guest_email"
                                                name="guest_email"
                                                type="email"
                                                value="{{ old('guest_email') }}"
                                                class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                                                placeholder="your@email.com"
                                            >
                                            @error('guest_email')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endguest

                                <div>
                                    <label for="body" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Your Comment <span class="text-red-500">*</span></label>
                                    <textarea
                                        id="body"
                                        name="body"
                                        rows="5"
                                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none transition-colors"
                                        placeholder="Share your thoughts, ask questions, or provide feedback about this property..."
                                        maxlength="2000"
                                        required
                                    >{{ old('body') }}</textarea>
                                    <div class="mt-1 flex items-center justify-between">
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Maximum 2000 characters</p>
                                        @error('body')
                                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                        @guest
                                            You're commenting as a guest. <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Sign in</a> for a better experience.
                                        @else
                                            Commenting as <span class="font-medium text-slate-900 dark:text-white">{{ auth()->user()->name }}</span>
                                        @endguest
                                    </div>
                                    <button
                                        type="submit"
                                        id="submit-comment"
                                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-medium rounded-lg transition-colors shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md"
                                    >
                                        <span id="submit-text">Post Comment</span>
                                        <span id="loading-text" class="hidden">Posting...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Comments Display -->
                    <div id="comments-section" class="px-8 pb-8">
                        @if($listing->comments->count() > 0)
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">
                                    {{ $listing->comments->count() }} Comment{{ $listing->comments->count() !== 1 ? 's' : '' }}
                                </h3>

                                <div class="space-y-4">
                                    @foreach($listing->comments as $comment)
                                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-5 bg-slate-50 dark:bg-slate-700/30 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors" data-comment-id="{{ $comment->id }}">
                                            <div class="flex items-start gap-4">
                                                <!-- Avatar -->
                                                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ strtoupper(substr(optional($comment->user)->name ?? $comment->guest_name ?? 'G', 0, 1)) }}
                                                    </span>
                                                </div>

                                                <!-- Comment Content -->
                                                <div class="flex-1 min-w-0 text-left">
                                                    <!-- Header -->
                                                    <div class="flex items-start justify-between mb-2">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="font-semibold text-slate-900 dark:text-white">
                                                                {{ ucwords( optional($comment->user)->name ?? $comment->guest_name ?? 'Guest' )}}
                                                            </span>
                                                            @if($comment->user)
                                                                <span class="px-2 py-0.5 text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full">
                                                                    Verified User
                                                                </span>
                                                            @endif
                                                            <span class="text-xs text-slate-500 dark:text-slate-400" title="{{ $comment->created_at->format('F j, Y \a\t g:i A') }}">
                                                                {{ $comment->created_at->diffForHumans() }}
                                                            </span>
                                                            @if(!$comment->approved)
                                                                <span class="px-2 py-0.5 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full font-medium">
                                                                    ⏳ Waiting for Approval
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <!-- Action Buttons -->
                                                        <div class="flex items-center gap-2 ml-2">
                                                            @if(auth()->check() && auth()->id() === $comment->user_id)
                                                                <button
                                                                    onclick="editComment({{ $comment->id }}, {{ json_encode($comment->body) }})"
                                                                    class="p-1.5 text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded transition-colors"
                                                                    title="Edit comment"
                                                                >
                                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                </button>
                                                                <button
                                                                    onclick="deleteComment({{ $comment->id }})"
                                                                    class="p-1.5 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                                                                    title="Delete comment"
                                                                >
                                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            @endif

                                                            @if(auth()->check() && auth()->user()->is_admin)
                                                                <button
                                                                    onclick="approveComment({{ $comment->id }})"
                                                                    class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $comment->approved ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-900/40' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 hover:text-emerald-700 dark:hover:text-emerald-400' }}"
                                                                    data-approved="{{ $comment->approved ? '1' : '0' }}"
                                                                    id="approve-btn-{{ $comment->id }}"
                                                                >
                                                                    @if($comment->approved)
                                                                        ✓ Approved
                                                                    @else
                                                                        Approve
                                                                    @endif
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start justify-start">
                                                        <p class=" text-left">
                                                            {{ ucfirst($comment->body) }}
                                                        </p>
                                                    </div>

                                                    <!-- Like Button -->
                                                    <div class="flex items-center " style="display:none">
                                                        <button
                                                            onclick="handleAgree({{ $comment->id }})"
                                                            class="agree-btn inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors"
                                                        >
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V2.75a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.145 1.166.846 1.166h4.94c1.147 0 2.077.96 2.037 2.106-.033 1.223-.81 2.372-2.01 3.133-.453.29-.964.47-1.487.498-.2.015-.4.02-.603.022a7.78 7.78 0 01-.798-.005 3.05 3.05 0 00-.462-.006c-.204.002-.403.007-.603.022-.523.028-1.034.208-1.487.498-1.2.761-1.977 1.91-2.01 3.133-.04 1.146.89 2.106 2.037 2.106h4.94c.701 0 1.112-.608.846-1.166-.463-.975-.723-2.066-.723-3.218a2.25 2.25 0 012.25-2.25A.75.75 0 0021 9.25v-.464c0-1.021-.11-2.021-.322-2.96a4.498 4.498 0 00-.322-1.672V2.75z" />
                                                            </svg>
                                                            <span class="agree-count" data-agree="{{ $comment->agree_count ?? 0 }}">{{ $comment->agree_count ?? 0 }}</span>
                                                            @if(auth()->check())
                                                                <input type="hidden" class="agree-user-liked" value="{{ $comment->isLikedBy(auth()->user()) ? 1 : 0 }}">
                                                            @endif
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-8 text-center py-12">
                                <svg class="w-20 h-20 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                </svg>
                                <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">No comments yet</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-6">Be the first to share your thoughts or ask a question about this property!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Edit Comment Modal -->
        <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white dark:bg-slate-800 rounded-lg max-w-lg w-full p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Edit Comment</h3>
                    <form id="edit-form">
                        @csrf
                        @method('PUT')
                        <textarea
                            id="edit-content"
                            name="content"
                            rows="4"
                            class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"
                            required
                        ></textarea>
                        <div class="flex justify-end space-x-3 mt-4">
                            <button
                                type="button"
                                onclick="closeEditModal()"
                                class="px-4 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                id="update-comment"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg disabled:opacity-50"
                            >
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div id="lightbox" class="fixed inset-0 bg-black/90 dark:bg-black/95 z-50 hidden items-center justify-center p-4">
            <button
                onclick="closeLightbox()"
                class="absolute top-4 right-4 text-white hover:text-slate-300 transition-colors z-10"
                aria-label="Close lightbox"
            >
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            @if(count($allMedia) > 1)
                <button
                    onclick="changeLightboxMedia(-1)"
                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white p-3 rounded-full transition-all"
                    aria-label="Previous media"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    onclick="changeLightboxMedia(1)"
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white p-3 rounded-full transition-all"
                    aria-label="Next media"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endif

            <div class="max-w-7xl w-full h-full flex items-center justify-center">
                <div id="lightbox-content" class="max-w-full max-h-full"></div>
            </div>
        </div>

        <script>
            const mediaItems = @json($allMedia);
            const listingId = {{ $listing->id }};
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            let currentMediaIndex = 0;
            let lightboxOpen = false;


            function openMessenger(fbLink) {
                const userId = fbLink.split('/').pop();
                const message = encodeURIComponent("Hello, I'm interested in your listing!");
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                if (isMobile) {
                    const messengerAppUrl = `fb-messenger://user/${userId}?text=${message}`;
                    const messengerWebUrl = `https://m.facebook.com/messages/t/${userId}?text=${message}`;
                    window.location.href = messengerAppUrl;
                    setTimeout(function() {
                        window.open(messengerWebUrl, '_blank');
                    }, 1000);
                } else {
                    const separator = fbLink.includes('?') ? '&' : '?';
                    window.open(`${fbLink}${separator}text=${message}`, '_blank');
                }
            }


            function shareOnFacebook() {
                const url = encodeURIComponent(window.location.href);
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
            }

            function shareOnTwitter() {
                const url = encodeURIComponent(window.location.href);
                const text = encodeURIComponent('{{ $listing->title }}');
                window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
            }

            function copyLink() {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('Link copied to clipboard!');
                }, function() {
                    alert('Failed to copy link');
                });
            }

            // Media Gallery Functions
            function selectMedia(index) {
                currentMediaIndex = index;
                updateMainMedia();
                updateThumbnails();
            }

            function changeMedia(direction) {
                currentMediaIndex += direction;
                if (currentMediaIndex < 0) {
                    currentMediaIndex = mediaItems.length - 1;
                } else if (currentMediaIndex >= mediaItems.length) {
                    currentMediaIndex = 0;
                }
                updateMainMedia();
                updateThumbnails();
            }

            function updateMainMedia() {
                // Hide all main media items
                document.querySelectorAll('.media-main-item').forEach(item => {
                    item.classList.add('hidden');
                });

                // Show current media item
                const currentItem = document.getElementById(`media-main-${currentMediaIndex}`);
                if (currentItem) {
                    currentItem.classList.remove('hidden');
                }

                // Update media type badge
                const badge = document.getElementById('media-type-badge');
                if (badge && mediaItems[currentMediaIndex]) {
                    badge.textContent = mediaItems[currentMediaIndex].type === 'video' ? 'Video' : 'Image';
                }
            }

            function updateThumbnails() {
                document.querySelectorAll('.thumbnail-item').forEach((thumb, index) => {
                    if (index === currentMediaIndex) {
                        thumb.classList.remove('border-transparent', 'hover:border-slate-300', 'dark:hover:border-slate-600');
                        thumb.classList.add('border-emerald-600', 'dark:border-emerald-400');
                    } else {
                        thumb.classList.remove('border-emerald-600', 'dark:border-emerald-400');
                        thumb.classList.add('border-transparent', 'hover:border-slate-300', 'dark:hover:border-slate-600');
                    }
                });
            }

            function openLightbox(index) {
                currentMediaIndex = index;
                lightboxOpen = true;
                const lightbox = document.getElementById('lightbox');
                const lightboxContent = document.getElementById('lightbox-content');

                if (lightbox && lightboxContent && mediaItems[index]) {
                    const item = mediaItems[index];
                    if (item.type === 'video') {
                        lightboxContent.innerHTML = `
                            <video
                                src="${item.url}"
                                class="max-w-full max-h-[90vh] rounded-lg"
                                controls
                                autoplay
                            ></video>
                        `;
                    } else {
                        lightboxContent.innerHTML = `
                            <img
                                src="${item.url}"
                                alt="{{ $listing->title }} - Media ${index + 1}"
                                class="max-w-full max-h-[90vh] object-contain rounded-lg"
                            >
                        `;
                    }
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeLightbox() {
                const lightbox = document.getElementById('lightbox');
                if (lightbox) {
                    lightbox.classList.add('hidden');
                    lightbox.classList.remove('flex');
                    document.body.style.overflow = '';
                    // Stop any playing videos
                    const video = lightbox.querySelector('video');
                    if (video) {
                        video.pause();
                    }
                }
                lightboxOpen = false;
            }

            function changeLightboxMedia(direction) {
                currentMediaIndex += direction;
                if (currentMediaIndex < 0) {
                    currentMediaIndex = mediaItems.length - 1;
                } else if (currentMediaIndex >= mediaItems.length) {
                    currentMediaIndex = 0;
                }
                openLightbox(currentMediaIndex);
            }

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (lightboxOpen) {
                    if (e.key === 'Escape') {
                        closeLightbox();
                    } else if (e.key === 'ArrowLeft') {
                        changeLightboxMedia(-1);
                    } else if (e.key === 'ArrowRight') {
                        changeLightboxMedia(1);
                    }
                } else if (mediaItems.length > 1) {
                    if (e.key === 'ArrowLeft') {
                        changeMedia(-1);
                    } else if (e.key === 'ArrowRight') {
                        changeMedia(1);
                    }
                }
            });

            // Close lightbox on background click
            document.getElementById('lightbox')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeLightbox();
                }
            });

            // Close edit modal on background click
            document.getElementById('edit-modal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });

            // (Comment submission handled by single AJAX handler lower in the file.)

            // Edit comment functions
            function editComment(commentId, content) {
                const textarea = document.getElementById('edit-content');
                textarea.value = content;
                document.getElementById('edit-form').action = `/comments/${commentId}`;
                document.getElementById('edit-modal').classList.remove('hidden');
                document.getElementById('edit-modal').classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                document.getElementById('edit-modal').classList.add('hidden');
                document.getElementById('edit-modal').classList.remove('flex');
                document.body.style.overflow = '';
            }

            // Update comment via AJAX
            document.getElementById('edit-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const updateBtn = document.getElementById('update-comment');

                updateBtn.disabled = true;
                document.getElementById('update-text').classList.add('hidden');
                document.getElementById('update-loading').classList.remove('hidden');

                const formData = new FormData(form);
                formData.append('body', document.getElementById('edit-content').value);
                formData.append('_method', 'PUT');

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to update comment');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update the comment in the DOM instead of reloading
                        const commentElement = document.querySelector(`[data-comment-id="${data.comment.id}"]`);
                        if (commentElement) {
                            const contentElement = commentElement.querySelector('.comment-content, p');
                            if (contentElement) {
                                // Capitalize first letter and ensure left alignment
                                const capitalizedBody = data.comment.body.charAt(0).toUpperCase() + data.comment.body.slice(1);
                                contentElement.textContent = capitalizedBody;
                                contentElement.classList.add('text-left');
                            }
                        }
                        closeEditModal();
                    } else {
                        alert(data.message || 'Error updating comment. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Error updating comment. Please try again.');
                })
                .finally(() => {
                    updateBtn.disabled = false;
                    document.getElementById('update-text').classList.remove('hidden');
                    document.getElementById('update-loading').classList.add('hidden');
                });
            });

            // Delete comment
            function deleteComment(commentId) {
                if (!confirm('Are you sure you want to delete this comment? This action cannot be undone.')) {
                    return;
                }

                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to delete comment');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Remove comment from DOM
                        const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                        if (commentElement) {
                            commentElement.style.transition = 'opacity 0.3s';
                            commentElement.style.opacity = '0';
                            setTimeout(() => {
                                commentElement.remove();

                                // Check if no comments left
                                const commentsSection = document.getElementById('comments-section');
                                const remainingComments = commentsSection.querySelectorAll('[data-comment-id]');
                                if (remainingComments.length === 0) {
                                    location.reload();
                                }
                            }, 300);
                        } else {
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'Error deleting comment. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Error deleting comment. Please try again.');
                });
            }

            // AJAX submit new comment
            const commentForm = document.getElementById('comment-form');
            if (commentForm) {
                commentForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('submit-comment');
                    const submitText = document.getElementById('submit-text');
                    const loadingText = document.getElementById('loading-text');

                    submitBtn.disabled = true;
                    submitText.classList.add('hidden');
                    loadingText.classList.remove('hidden');

                    const url = this.action;
                    const formData = new FormData(this);

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) {
                            return res.json().then(data => {
                                throw new Error(data.message || 'Failed to post comment');
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            if (data.approved === false) {
                                // Show pending approval message
                                alert('Your comment has been posted and is waiting for approval. It will be visible to others once approved.');
                            }
                            // Reload page to show new comment properly with all features
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to post comment');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error posting comment.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitText.classList.remove('hidden');
                        loadingText.classList.add('hidden');
                    });
                });
            }

            // Helper: format Date to Y-m-d H:i
            function formatDateTime(d) {
                const pad = (n) => n.toString().padStart(2, '0');
                const Y = d.getFullYear();
                const M = pad(d.getMonth() + 1);
                const D = pad(d.getDate());
                const h = pad(d.getHours());
                const m = pad(d.getMinutes());
                return `${Y}-${M}-${D} ${h}:${m}`;
            }

            // Agree button handling: server-backed for authenticated users, localStorage fallback for guests
            function handleAgree(commentId) {
                const el = document.querySelector(`[data-comment-id="${commentId}"] .agree-count`);
                if (!el) return;

                const btn = el.closest('.agree-btn');

                if (isAuthenticated) {
                    // call server to toggle like
                    fetch(`/comments/${commentId}/agree`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            alert(data.message || 'Failed to update like');
                            return;
                        }
                        // update count and UI
                        el.textContent = data.agree_count ?? el.textContent;
                        if (data.liked) {
                            btn.classList.add('text-emerald-600', 'dark:text-emerald-400');
                            btn.querySelector('svg').classList.add('fill-emerald-600', 'dark:fill-emerald-400');
                        } else {
                            btn.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                            btn.querySelector('svg').classList.remove('fill-emerald-600', 'dark:fill-emerald-400');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error updating like');
                    });
                } else {
                    // guest: fallback to localStorage
                    const countKey = `agree_count_${listingId}_${commentId}`;
                    const stateKey = `agree_state_${listingId}_${commentId}`;
                    const currentCount = parseInt(localStorage.getItem(countKey) ?? el.getAttribute('data-agree') ?? '0', 10);
                    const currentState = localStorage.getItem(stateKey) === '1';

                    let newCount = currentCount;
                    let newState = !currentState;
                    if (newState) newCount = currentCount + 1; else newCount = Math.max(0, currentCount - 1);

                    localStorage.setItem(countKey, String(newCount));
                    localStorage.setItem(stateKey, newState ? '1' : '0');

                    el.textContent = newCount;
                    if (newState) {
                        btn.classList.add('text-emerald-600', 'dark:text-emerald-400');
                        btn.querySelector('svg').classList.add('fill-emerald-600', 'dark:fill-emerald-400');
                    } else {
                        btn.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                        btn.querySelector('svg').classList.remove('fill-emerald-600', 'dark:fill-emerald-400');
                    }
                }
            }

            function initAgreeFor(commentId) {
                const countKey = `agree_count_${listingId}_${commentId}`;
                const stateKey = `agree_state_${listingId}_${commentId}`;
                const el = document.querySelector(`[data-comment-id="${commentId}"] .agree-count`);
                if (!el) return;
                const btn = el.closest('.agree-btn');
                // If authenticated, prefer server-provided liked state embedded in DOM
                if (isAuthenticated) {
                    const likedInput = el.closest('[data-comment-id]').querySelector('.agree-user-liked');
                    if (likedInput) {
                        const liked = likedInput.value === '1';
                        if (liked) {
                            btn.classList.add('text-emerald-600', 'dark:text-emerald-400');
                            const svg = btn.querySelector('svg');
                            if (svg) svg.classList.add('fill-emerald-600', 'dark:fill-emerald-400');
                        } else {
                            btn.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                            const svg = btn.querySelector('svg');
                            if (svg) svg.classList.remove('fill-emerald-600', 'dark:fill-emerald-400');
                        }
                    }
                    // set count from data attribute unless overridden by localStorage
                    const savedCount = localStorage.getItem(countKey);
                    if (savedCount !== null) {
                        el.textContent = savedCount;
                    } else {
                        el.textContent = el.getAttribute('data-agree') ?? '0';
                    }
                } else {
                    const savedCount = localStorage.getItem(countKey);
                    const savedState = localStorage.getItem(stateKey) === '1';
                    if (savedCount !== null) {
                        el.textContent = savedCount;
                    }
                    if (btn) {
                        if (savedState) {
                            btn.classList.add('text-emerald-600', 'dark:text-emerald-400');
                            const svg = btn.querySelector('svg');
                            if (svg) svg.classList.add('fill-emerald-600', 'dark:fill-emerald-400');
                        } else {
                            btn.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                            const svg = btn.querySelector('svg');
                            if (svg) svg.classList.remove('fill-emerald-600', 'dark:fill-emerald-400');
                        }
                    }
                }
            }

            function initAgreeState() {
                document.querySelectorAll('.agree-count').forEach(el => {
                    const wrapper = el.closest('[data-comment-id]');
                    if (!wrapper) return;
                    const commentId = wrapper.getAttribute('data-comment-id');
                    // default from markup
                    const defaultCount = el.getAttribute('data-agree') ?? '0';
                    const countKey = `agree_count_${listingId}_${commentId}`;
                    if (localStorage.getItem(countKey) === null) {
                        localStorage.setItem(countKey, defaultCount);
                    }
                    initAgreeFor(commentId);
                });
            }

            // Initialize agree state on page load
            document.addEventListener('DOMContentLoaded', function() {
                initAgreeState();
                // Initialize approve button states for admins
                document.querySelectorAll('[id^="approve-btn-"]').forEach(btn => {
                    const approved = btn.getAttribute('data-approved') === '1';
                    if (approved) btn.classList.add('bg-emerald-100', 'text-emerald-700');
                });
            });

            // Approve comment (admin)
            function approveComment(commentId) {
                if (!confirm('Toggle approve for this comment?')) return;
                fetch(`/comments/${commentId}/approve`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Failed to update approval');
                        return;
                    }
                    const btn = document.getElementById(`approve-btn-${commentId}`);
                    if (!btn) return;
                    if (data.approved) {
                        btn.innerText = '✓ Approved';
                        btn.classList.add('bg-emerald-100', 'text-emerald-700');
                    } else {
                        btn.innerText = '○ Approve';
                        btn.classList.remove('bg-emerald-100', 'text-emerald-700');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error updating approval');
                });
            }

        </script>
    </body>
</html>



