<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCommentsController extends Controller
{
    public function index(Request $request): View
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        $comments = Comment::with(['listing', 'user'])
            ->latest()
            ->paginate(50);

        return view('admin.comments.index', ['comments' => $comments]);
    }
}
