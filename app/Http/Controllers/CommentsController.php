<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Listing;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CommentsController extends Controller
{
    /**
     * Store a new comment. Allows guests and authenticated users.
     */
    public function store(StoreCommentRequest $request, Listing $listing, $slug): JsonResponse|RedirectResponse
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

        $comment->load('user');

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully.',
                'comment' => $comment,
            ]);
        }

        return redirect()->route('listings.show', ['listing' => $listing->id, 'slug' => $slug])->with('success', 'Comment posted successfully.');
    }

    // Keep update/delete behavior to authenticated and authorized users only.
    public function update($request, Comment $comment)
    {
        // left as-is or implement later
        abort(404);
    }

    public function destroy($request, Comment $comment)
    {
        abort(404);
    }
}
