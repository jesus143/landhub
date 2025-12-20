<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'listing_id',
        'user_id',
        'guest_name',
        'guest_email',
        'body',
        'approved',
        'agree_count',
    ];

    protected function casts(): array
    {
        return [
            'approved' => 'boolean',
            'agree_count' => 'integer',
        ];
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function isLikedBy($user)
    {
        if (! $user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
