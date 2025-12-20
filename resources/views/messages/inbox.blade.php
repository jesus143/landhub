<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 dark:text-white leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900 dark:text-white">
                    <!-- Tabs -->
                    <div class="border-b border-slate-200 dark:border-slate-700 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a href="{{ route('messages.inbox', ['tab' => 'all']) }}" 
                               class="@if($activeTab === 'all') border-emerald-500 text-emerald-600 dark:text-emerald-400 @else border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                All Messages
                            </a>
                            <a href="{{ route('messages.inbox', ['tab' => 'unread']) }}" 
                               class="@if($activeTab === 'unread') border-emerald-500 text-emerald-600 dark:text-emerald-400 @else border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm relative">
                                Unread
                                @if($unreadCount > 0)
                                    <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unreadCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('messages.inbox', ['tab' => 'read']) }}" 
                               class="@if($activeTab === 'read') border-emerald-500 text-emerald-600 dark:text-emerald-400 @else border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Read
                            </a>
                        </nav>
                    </div>

                    <!-- Conversations List -->
                    @if($conversations->count() > 0)
                        <div class="space-y-4">
                            @foreach($conversations as $conversation)
                                <a href="{{ route('messages.show', $conversation->other_user_id) }}" 
                                   class="block p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-semibold">
                                                    {{ strtoupper(substr($conversation->other_user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                                        {{ $conversation->other_user->name }}
                                                    </p>
                                                    @if($conversation->unread_count > 0)
                                                        <span class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                                                            {{ $conversation->unread_count }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                    {{ $conversation->message_count }} message{{ $conversation->message_count !== 1 ? 's' : '' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ \Carbon\Carbon::parse($conversation->last_message_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($messages->hasPages())
                            <div class="mt-6">
                                {{ $messages->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No messages</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Get started by messaging someone.</p>
                            <div class="mt-6">
                                <a href="{{ route('messages.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition ease-in-out duration-150">
                                    New Message
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

