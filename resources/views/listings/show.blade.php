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

                            <h3> Featured Video </h3>
                            <div class=" w-full border-2">
                                <iframe width="100%" height="315" src="https://www.youtube.com/embed/MLATROF5KMk?si=8tzWrBQqcSO5kU04" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>


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
                            <div class="space-y-3 mb-6">
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
                                            <path d="M12 0C5.373 0 0 4.925 0 11c0 1.906.52 3.7 1.42 5.24L0 24l7.8-4.05C9.4 20.55 10.65 20.8 12 20.8c6.627 0 12-4.925 12-11S18.627 0 12 0zm0 18.8c-1.15 0-2.25-.2-3.3-.55l-.45-.15-4.65 2.4 1.05-4.5-.3-.45C3.7 14.3 3.2 12.7 3.2 11c0-4.4 3.9-8 8.8-8s8.8 3.6 8.8 8-3.9 8-8.8 8z"/>
                                        </svg>
                                        <button onclick="openMessenger('{{ $listing->contact_fb_link }}')" class="text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 text-left">
                                            Contact via Messenger
                                        </button>
                                    </div>
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
        <section class="py-12 bg-slate-50 dark:bg-slate-900">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Comments & Questions</h2>

                    <!-- Comment Form (guests and authenticated users) -->
                    @php
                        $commentSlug = \Illuminate\Support\Str::slug($listing->category) . '-' . \Illuminate\Support\Str::slug($listing->title) . '-' . \Illuminate\Support\Str::slug($listing->location);
                    @endphp
                    <form id="comment-form" method="POST" action="{{ route('listings.comments.store', ['listing' => $listing->id, 'slug' => $commentSlug]) }}" class="mb-8">
                        @csrf
                        <div class="space-y-4">
                            @guest
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label for="guest_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Name</label>
                                        <input id="guest_name" name="guest_name" value="{{ old('guest_name') }}" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100" required>
                                    </div>
                                    <div>
                                        <label for="guest_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email (optional)</label>
                                        <input id="guest_email" name="guest_email" value="{{ old('guest_email') }}" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">
                                    </div>
                                </div>
                            @endguest

                            <div>
                                <label for="body" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Comment</label>
                                <textarea id="body" name="body" rows="4" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none" placeholder="Share your thoughts or ask about this property..." required>{{ old('body') }}</textarea>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-slate-500">You may comment as a guest or <a href="{{ route('login') }}" class="text-emerald-600">sign in</a> for a richer experience.</div>
                                <div>
                                    <button type="submit" id="submit-comment" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span id="submit-text">Post Comment</span>
                                        <span id="loading-text" class="hidden">Posting...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Comments Display -->
                    <div id="comments-section">
                        @if($listing->comments->count() > 0)
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ $listing->comments->count() }} Comment{{ $listing->comments->count() !== 1 ? 's' : '' }}
                                </h3>

                                @foreach($listing->comments as $comment)
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 bg-slate-50 dark:bg-slate-700/50" data-comment-id="{{ $comment->id }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm">
                                                        {{ strtoupper(substr(optional($comment->user)->name ?? $comment->guest_name ?? 'G', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-slate-900 dark:text-white">
                                                                {{ optional($comment->user)->name ?? $comment->guest_name ?? 'Guest' }}
                                                            </span>
                                                            <span class="text-sm text-slate-500 dark:text-slate-400" title="{{ $comment->created_at->format('Y-m-d H:i:s') }}">
                                                                {{ $comment->created_at->diffForHumans() }} · {{ $comment->created_at->format('Y-m-d H:i') }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <button onclick="handleAgree({{ $comment->id }})" class="agree-btn flex items-center gap-2 text-sm text-slate-600 hover:text-emerald-600">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9l-3 6h4l3-6h-4zM5 13h4l3-6H8L5 13z"/></svg>
                                                                <span class="agree-count" data-agree="{{ $comment->agree_count ?? 0 }}">{{ $comment->agree_count ?? 0 }}</span>
                                                                @if(auth()->check())
                                                                    <input type="hidden" class="agree-user-liked" value="{{ $comment->isLikedBy(auth()->user()) ? 1 : 0 }}">
                                                                @endif
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap comment-content text-left self-start mt-1">
                                                        {{ $comment->body }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2 ml-4">
                                                @if(auth()->check() && auth()->id() === $comment->user_id)
                                                    <button
                                                        onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content ?? $comment->body) }}')"
                                                        class="text-sm text-emerald-600 hover:text-emerald-700 font-medium"
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        onclick="deleteComment({{ $comment->id }})"
                                                        class="text-sm text-red-600 hover:text-red-700 font-medium"
                                                    >
                                                        Delete
                                                    </button>
                                                @endif

                                                @if(auth()->check() && auth()->user()->is_admin)
                                                    <button
                                                        onclick="approveComment({{ $comment->id }})"
                                                        class="text-sm px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded-full text-slate-700 dark:text-slate-200 hover:bg-emerald-50 hover:text-emerald-700"
                                                        data-approved="{{ $comment->approved ? '1' : '0' }}"
                                                        id="approve-btn-{{ $comment->id }}"
                                                    >
                                                        @if($comment->approved)
                                                            ✓ Approved
                                                        @else
                                                            ○ Approve
                                                        @endif
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9 8s9 3.582 9 8z" />
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No comments yet</h3>
                                <p class="text-slate-600 dark:text-slate-400">Be the first to ask a question or leave a comment!</p>
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
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                if (isMobile) {
                    const messengerAppUrl = `fb-messenger://user/${userId}`;
                    const messengerWebUrl = `https://m.facebook.com/messages/t/${userId}`;
                    window.location.href = messengerAppUrl;
                    setTimeout(function() {
                        window.open(messengerWebUrl, '_blank');
                    }, 1000);
                } else {
                    window.open(fbLink, '_blank');
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

            // (Comment submission handled by single AJAX handler lower in the file.)

            // Edit comment functions
            function editComment(commentId, content) {
                document.getElementById('edit-content').value = content;
                document.getElementById('edit-form').action = `/comments/${commentId}`;
                document.getElementById('edit-modal').classList.remove('hidden');
                document.getElementById('edit-modal').classList.add('flex');
            }

            function closeEditModal() {
                document.getElementById('edit-modal').classList.add('hidden');
                document.getElementById('edit-modal').classList.remove('flex');
            }

            // Update comment via AJAX
            document.getElementById('edit-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const updateBtn = document.getElementById('update-comment');

                updateBtn.disabled = true;
                updateBtn.textContent = 'Updating...';

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating comment. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating comment. Please try again.');
                })
                .finally(() => {
                    updateBtn.disabled = false;
                    updateBtn.textContent = 'Update';
                    closeEditModal();
                });
            });

            // Delete comment
            function deleteComment(commentId) {
                if (!confirm('Are you sure you want to delete this comment?')) {
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting comment. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting comment. Please try again.');
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
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // prepend new comment to comments-section
                            const commentsSection = document.getElementById('comments-section');
                            const container = document.createElement('div');
                            container.className = 'border border-slate-200 dark:border-slate-700 rounded-lg p-4 bg-slate-50 dark:bg-slate-700/50 mb-4';
                            const author = data.comment.user ? (data.comment.user.name) : (data.comment.guest_name || 'Guest');
                            const time = `${formatDateTime(new Date())}`;
                            const relative = 'just now';
                            const body = data.comment.body;
                            const commentId = data.comment.id || Math.floor(Math.random() * 1000000);
                            const userLiked = isAuthenticated ? (data.comment.user && data.comment.user.id === {{ auth()->id() ?? 'null' }}) : false;
                            container.innerHTML = `
                                <div class="flex items-start justify-between" data-comment-id="${commentId}">
                                    <div class="flex items-start space-x-3 w-full">
                                        <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-medium text-sm">${(author || 'G').charAt(0).toUpperCase()}</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-slate-900 dark:text-white">${author}</span>
                                                    <span class="text-sm text-slate-500 dark:text-slate-400">${relative} · ${time}</span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <button onclick="handleAgree(${commentId})" class="agree-btn flex items-center gap-2 text-sm text-slate-600 hover:text-emerald-600">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9l-3 6h4l3-6h-4zM5 13h4l3-6H8L5 13z"/></svg>
                                                        <span class="agree-count" data-agree="0">0</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap comment-content">${body}</p>
                                        </div>
                                    </div>
                                </div>
                            `;

                            // if there is a 'No comments yet' block, remove it
                            const noComments = commentsSection.querySelector('.text-center.py-8');
                            if (noComments) noComments.remove();

                            // Prepend
                            commentsSection.prepend(container);

                            // initialize agree state for new item
                            initAgreeFor(commentId);
                            // if authenticated and server returned comment, set server like state if any
                            if (isAuthenticated && data.comment.id) {
                                // fetch liked state via DOM marker (server didn't return liked in store)
                                // no-op: newly created comment cannot be liked yet by this user
                            }

                            // clear textarea
                            document.getElementById('body').value = '';
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
                        if (data.liked) btn.classList.add('text-emerald-600'); else btn.classList.remove('text-emerald-600');
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
                    if (newState) btn.classList.add('text-emerald-600'); else btn.classList.remove('text-emerald-600');
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
                        if (liked) btn.classList.add('text-emerald-600'); else btn.classList.remove('text-emerald-600');
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
                        if (savedState) btn.classList.add('text-emerald-600'); else btn.classList.remove('text-emerald-600');
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



