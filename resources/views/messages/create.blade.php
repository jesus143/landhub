<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('messages.inbox') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-slate-900 dark:text-white leading-tight">
                    {{ __('New Message') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($listing)
                        <div class="mb-6 p-4 bg-slate-100 dark:bg-slate-700 rounded-lg">
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Regarding listing:</p>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $listing->title }}</p>
                        </div>
                    @endif

                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf

                        @if($listing)
                            <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        @endif

                        <div class="mb-6">
                            <label for="receiver_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                To
                            </label>
                            @if($recipient)
                                <input type="hidden" name="receiver_id" value="{{ $recipient->id }}">
                                <div class="p-3 bg-slate-100 dark:bg-slate-700 rounded-lg">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $recipient->name }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $recipient->email }}</p>
                                </div>
                            @else
                                <select name="receiver_id" id="receiver_id" required class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                                    <option value="">Select a user...</option>
                                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->orderBy('name')->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('receiver_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="body" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Message
                            </label>
                            <textarea
                                name="body"
                                id="body"
                                rows="6"
                                required
                                class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm p-3"
                                placeholder="Type your message...">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('messages.inbox') }}" class="px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-colors">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

