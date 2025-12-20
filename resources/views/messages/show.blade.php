<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('messages.inbox') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="font-semibold text-xl text-slate-900 dark:text-white leading-tight">
                            {{ $otherUser->name }}
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $otherUser->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Messages -->
                <div class="p-6 space-y-4" style="max-height: 600px; overflow-y: auto;">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white' }}">
                                @if($message->listing)
                                    <div class="text-xs opacity-75 mb-1">
                                        Regarding: {{ $message->listing->title }}
                                    </div>
                                @endif
                                <div class="text-xs opacity-75 mb-1 font-semibold">
                                    {{ $message->sender_id === auth()->id() ? 'You' : $message->sender->name }}
                                </div>
                                <p class="text-sm">{{ $message->body }}</p>
                                <p class="text-xs opacity-75 mt-1">
                                    {{ $message->created_at->format('M j, g:i A') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Form -->
                <div class="border-t border-slate-200 dark:border-slate-700 p-6">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                        
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <textarea 
                                    name="body" 
                                    rows="3" 
                                    required
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    placeholder="Type your message..."></textarea>
                                @error('body')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-colors">
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom on load
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.querySelector('[style*="max-height"]');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    </script>
</x-app-layout>

