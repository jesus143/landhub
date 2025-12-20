<nav x-data="{ open: false }" class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white hidden sm:block">LandHub</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.*')">
                        {{ __('Browse Listings') }}
                    </x-nav-link>
                    <div class="relative inline-flex items-center">
                        <x-nav-link :href="route('messages.inbox')" :active="request()->routeIs('messages.*')">
                            {{ __('Messages') }}
                        </x-nav-link>
                        @php
                            $unreadCount = Auth::user()->unreadMessagesCount();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadCount }}</span>
                        @endif
                    </div>
                    @if(Auth::check() && Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.listings.index')" :active="request()->routeIs('admin.*')">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 text-sm leading-4 font-medium rounded-lg text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('messages.inbox')" class="relative">
                            {{ __('Messages') }}
                            @php
                                $unreadCount = Auth::user()->unreadMessagesCount();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unreadCount }}</span>
                            @endif
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:bg-slate-100 dark:focus:bg-slate-700 focus:text-slate-900 dark:focus:text-slate-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.*')">
                {{ __('Browse Listings') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('messages.inbox')" :active="request()->routeIs('messages.*')" class="relative">
                {{ __('Messages') }}
                @php
                    $unreadCount = Auth::user()->unreadMessagesCount();
                @endphp
                @if($unreadCount > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unreadCount }}</span>
                @endif
            </x-responsive-nav-link>
            @if(Auth::check() && Auth::user()->is_admin)
                <x-responsive-nav-link :href="route('admin.listings.index')" :active="request()->routeIs('admin.*')">
                    {{ __('Admin') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200 dark:border-slate-700">
            <div class="px-4">
                <div class="font-medium text-base text-slate-900 dark:text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('messages.inbox')" class="relative">
                    {{ __('Messages') }}
                    @php
                        $unreadCount = Auth::user()->unreadMessagesCount();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unreadCount }}</span>
                    @endif
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
