<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Listing;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Store a new comment. Allows guests and authenticated users.
     */
    public function store(StoreCommentRequest $request, Listing $listing, $slug): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        // Prevent accidental duplicate submissions: if an identical comment
        // was created within the last 10 seconds by the same user/guest, skip.
        $recentWindow = now()->subSeconds(10);
        $body = $data['body'];
        $existing = Comment::where('listing_id', $listing->id)
            ->where('body', $body)
            ->where('created_at', '>=', $recentWindow);

        if ($request->user()) {
            $existing = $existing->where('user_id', $request->user()->id);
        } else {
            $guestName = $data['guest_name'] ?? null;
            $guestEmail = $data['guest_email'] ?? null;
            $existing = $existing->where('guest_name', $guestName);
            if ($guestEmail) {
                $existing = $existing->where('guest_email', $guestEmail);
            }
        }

        if ($existing->exists()) {
            $comment = $existing->latest('id')->first();
            $comment->load('user');

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Duplicate comment ignored.',
                    'comment' => $comment,
                ]);
            }

            return redirect()->route('listings.show', ['listing' => $listing->id, 'slug' => $slug])->with('success', 'Comment already posted.');
        }

        $comment = new Comment();
        $comment->listing_id = $listing->id;
        $comment->body = $body;

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

    /**
     * Toggle approve state for a comment. Only admin users allowed.
     */
    public function approve(Request $request, Comment $comment): JsonResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->approved = ! (bool) $comment->approved;
        $comment->save();

        return response()->json([
            'success' => true,
            'approved' => (bool) $comment->approved,
            'comment_id' => $comment->id,
        ]);
    }

    /**
     * Toggle agree/like for a comment by authenticated user.
     */
    public function agree(Request $request, Comment $comment): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Authentication required'], 401);
        }

        // toggle like
        $liked = \App\Models\CommentLike::where('comment_id', $comment->id)->where('user_id', $user->id)->first();

        if ($liked) {
            // remove like
            $liked->delete();
            $comment->decrement('agree_count');
            $isLiked = false;
        } else {
            \App\Models\CommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
            ]);
            $comment->increment('agree_count');
            $isLiked = true;
        }

        return response()->json([
            'success' => true,
            'comment_id' => $comment->id,
            'agree_count' => $comment->agree_count,
            'liked' => $isLiked,
        ]);
    }
}
