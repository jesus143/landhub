<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 dark:text-white leading-tight">
            {{ __('All Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($messages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            From
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            To
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Message
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Listing
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($messages as $message)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $message->sender->name }}
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $message->sender->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $message->receiver->name }}
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $message->receiver->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-slate-900 dark:text-white">
                                                    {{ Str::limit($message->body, 100) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($message->listing)
                                                    <span class="text-sm text-slate-900 dark:text-white">
                                                        {{ Str::limit($message->listing->title, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-slate-400 dark:text-slate-500">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                {{ $message->created_at->format('M j, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($message->read_at)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                        Read
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                                        Unread
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No messages</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">There are no messages in the system yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

