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
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="{{ asset('tailwindcss.js') }}"></script>
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
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" style="display:none">
                                Log in
                            </a>
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
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Comments</h3>

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
                    @endif

                    <div class="space-y-4 mb-6">
                        @forelse($listing->comments as $comment)
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-sm font-semibold text-slate-700 dark:text-slate-300">{{ strtoupper(substr($comment->guest_name ?? optional($comment->user)->name ?? 'G', 0, 1)) }}</div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ optional($comment->user)->name ?? $comment->guest_name ?? 'Guest' }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-slate-700 dark:text-slate-300 mt-1">{{ $comment->body }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-600 dark:text-slate-400">No comments yet. Be the first to comment.</div>
                        @endforelse
                    </div>

                    @php
                        $commentSlug = \Illuminate\Support\Str::slug($listing->category) . '-' . \Illuminate\Support\Str::slug($listing->title) . '-' . \Illuminate\Support\Str::slug($listing->location);
                    @endphp

                    <form action="{{ route('listings.comments.store', ['listing' => $listing->id, 'slug' => $commentSlug]) }}" method="POST">
                        @csrf
                        @guest
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                                    <input name="guest_name" value="{{ old('guest_name') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-2">
                                    @error('guest_name') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email (optional)</label>
                                    <input name="guest_email" value="{{ old('guest_email') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-2">
                                    @error('guest_email') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        @endguest

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Comment</label>
                            <textarea name="body" rows="4" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-2">{{ old('body') }}</textarea>
                            @error('body') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg">Post Comment</button>
                            <div class="text-sm text-slate-500">You may comment as a guest or sign in first.</div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

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
        </script>
    </body>
</html>


