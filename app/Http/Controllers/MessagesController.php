<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessagesController extends Controller
{
    /**
     * Display inbox with tabs (unread, read, all).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $tab = $request->get('tab', 'all'); // unread, read, all

        // Get all messages where user is sender or receiver (for pagination display)
        $allMessagesQuery = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })->with(['sender', 'receiver', 'listing'])->latest();

        // For "read" tab, include messages sent by user (they're read) and received messages that are read
        // For "unread" tab, only received messages that are unread
        // For "all" tab, show everything
        if ($tab === 'unread') {
            $allMessagesQuery->where('receiver_id', $user->id)->whereNull('read_at');
        } elseif ($tab === 'read') {
            $allMessagesQuery->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id) // Messages sent by user are considered read
                    ->orWhere(function ($q) use ($user) {
                        $q->where('receiver_id', $user->id)->whereNotNull('read_at');
                    });
            });
        }

        $messages = $allMessagesQuery->paginate(20);

        // Get unique conversations - get all people the user has messaged with (as sender or receiver)
        $sentConversations = $user->sentMessages()
            ->selectRaw('receiver_id as other_user_id, MAX(created_at) as last_message_at, COUNT(*) as message_count, 0 as unread_count')
            ->groupBy('receiver_id')
            ->get();

        $receivedConversations = $user->receivedMessages()
            ->selectRaw('sender_id as other_user_id, MAX(created_at) as last_message_at, COUNT(*) as message_count, SUM(CASE WHEN read_at IS NULL THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('sender_id')
            ->get();

        // Merge and group by other_user_id, keeping the latest message and combining counts
        $conversationsMap = [];

        foreach ($sentConversations as $conv) {
            $otherUserId = $conv->other_user_id;
            if (! isset($conversationsMap[$otherUserId])) {
                $conversationsMap[$otherUserId] = (object) [
                    'other_user_id' => $otherUserId,
                    'last_message_at' => $conv->last_message_at,
                    'message_count' => $conv->message_count,
                    'unread_count' => $conv->unread_count,
                ];
            } else {
                // Keep the latest message
                if ($conv->last_message_at > $conversationsMap[$otherUserId]->last_message_at) {
                    $conversationsMap[$otherUserId]->last_message_at = $conv->last_message_at;
                }
                $conversationsMap[$otherUserId]->message_count += $conv->message_count;
            }
        }

        foreach ($receivedConversations as $conv) {
            $otherUserId = $conv->other_user_id;
            if (! isset($conversationsMap[$otherUserId])) {
                $conversationsMap[$otherUserId] = (object) [
                    'other_user_id' => $otherUserId,
                    'last_message_at' => $conv->last_message_at,
                    'message_count' => $conv->message_count,
                    'unread_count' => $conv->unread_count,
                ];
            } else {
                // Keep the latest message
                if ($conv->last_message_at > $conversationsMap[$otherUserId]->last_message_at) {
                    $conversationsMap[$otherUserId]->last_message_at = $conv->last_message_at;
                }
                $conversationsMap[$otherUserId]->message_count += $conv->message_count;
                $conversationsMap[$otherUserId]->unread_count += $conv->unread_count;
            }
        }

        // Convert to collection, load the other user, and sort by last message
        $conversations = collect($conversationsMap)
            ->map(function ($conv) {
                $conv->other_user = \App\Models\User::find($conv->other_user_id);

                return $conv;
            })
            ->sortByDesc('last_message_at')
            ->values();

        // Filter conversations based on tab
        if ($tab === 'unread') {
            // Only show conversations with unread messages (received unread messages)
            $conversations = $conversations->filter(function ($conv) {
                return $conv->unread_count > 0;
            });
        } elseif ($tab === 'read') {
            // Show conversations with no unread messages (all read) OR where user has sent messages
            // Check if user has sent messages to this person
            $conversations = $conversations->filter(function ($conv) use ($user) {
                // If no unread messages, show it (all received messages are read)
                if ($conv->unread_count == 0) {
                    return true;
                }
                // Otherwise, check if user has sent any messages to this person
                // (if they sent messages, those should appear in "read" tab)
                $hasSentMessages = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $conv->other_user_id)
                    ->exists();

                return $hasSentMessages;
            });
        }
        // For "all" tab, show all conversations

        return view('messages.inbox', [
            'messages' => $messages,
            'conversations' => $conversations,
            'activeTab' => $tab,
            'unreadCount' => $user->unreadMessagesCount(),
        ]);
    }

    /**
     * Show conversation with a specific user.
     */
    public function show(Request $request, User $user): View|RedirectResponse
    {
        $currentUser = $request->user();

        if ($currentUser->id === $user->id) {
            return redirect()->route('messages.inbox')->with('error', 'Cannot message yourself.');
        }

        // Get all messages between current user and the other user
        $messages = Message::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $currentUser->id);
        })->with(['sender', 'receiver', 'listing'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read where current user is receiver
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', [
            'otherUser' => $user,
            'messages' => $messages,
        ]);
    }

    /**
     * Create a new message (form to select recipient).
     */
    public function create(Request $request, ?Listing $listing = null): View
    {
        $user = $request->user();
        $recipient = null;

        // If listing is provided, find owner: priority 1 = user_id, priority 2 = contact_email
        if ($listing) {
            $recipient = $listing->getOwner();
        }

        return view('messages.create', [
            'listing' => $listing,
            'recipient' => $recipient,
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'body' => ['required', 'string', 'max:5000'],
            'listing_id' => ['nullable', 'exists:listings,id'],
        ]);

        $sender = $request->user();

        if ($sender->id === $request->receiver_id) {
            return back()->with('error', 'Cannot send message to yourself.');
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $request->receiver_id,
            'listing_id' => $request->listing_id,
            'body' => $request->body,
        ]);

        return redirect()->route('messages.show', $request->receiver_id)
            ->with('success', 'Message sent successfully.');
    }
}
