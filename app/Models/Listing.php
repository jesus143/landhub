<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'area',
        'location',
        'category',
        'status',
        'image_url',
        'media',
        'latitude',
        'longitude',
        'nearby_landmarks',
        'map_link',
        'featured_video_url',
        'contact_phone',
        'contact_email',
        'contact_fb_link',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'area' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'media' => 'array',
        ];
    }

    /**
     * Get all media items including backward compatibility with image_url.
     */
    public function getAllMedia(): array
    {
        $media = $this->media ?? [];

        // Backward compatibility: if image_url exists and not in media, add it
        if ($this->image_url && ! collect($media)->contains('url', $this->image_url)) {
            array_unshift($media, [
                'type' => 'image',
                'url' => $this->image_url,
            ]);
        }

        return $media;
    }

    /**
     * Convert YouTube URL to embed format.
     */
    public function getFeaturedVideoEmbedUrl(): ?string
    {
        if (! $this->featured_video_url) {
            return null;
        }

        $url = $this->featured_video_url;

        // Extract video ID from various YouTube URL formats
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return 'https://www.youtube.com/embed/'.$matches[1];
            }
        }

        // If already an embed URL, return as is
        if (str_contains($url, 'youtube.com/embed/')) {
            return $url;
        }

        // If no match, return original URL (might be invalid)
        return $url;
    }

    /**
     * Comments for this listing.
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email');
    }
}
