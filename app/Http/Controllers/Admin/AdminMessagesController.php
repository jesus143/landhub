<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMessagesController extends Controller
{
    /**
     * Display all messages (admin view).
     */
    public function index(Request $request): View
    {
        // Check if user is admin
        if (!$request->user() || !$request->user()->is_admin) {
            abort(403);
        }

        $messages = Message::with(['sender', 'receiver', 'listing'])
            ->latest()
            ->paginate(50);

        return view('admin.messages.index', [
            'messages' => $messages,
        ]);
    }
}
