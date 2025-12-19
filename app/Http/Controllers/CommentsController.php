<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Listing;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentsController extends Controller
{
    public function store(StoreCommentRequest $request, Listing $listing, $slug): RedirectResponse
    {
        $data = $request->validated();

        $comment = new Comment();
        $comment->listing_id = $listing->id;
        $comment->body = $data['body'];

        if ($request->user()) {
            $comment->user_id = $request->user()->id;
            $comment->guest_name = $request->user()->name;
            $comment->guest_email = $request->user()->email;
        } else {
            $comment->guest_name = $data['guest_name'] ?? null;
            $comment->guest_email = $data['guest_email'] ?? null;
        }

        $comment->save();

        return redirect()->route('listings.show', ['listing' => $listing->id, 'slug' => $slug])
            ->with('success', 'Comment posted successfully.');
    }
}
