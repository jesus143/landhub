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
        'is_titled',
        'trees_plants',
        'terrain_type',
        'vehicle_accessible',
        'additional_features',
        'property_type',
        'frontage',
        'road_type',
        'num_rooms',
        'is_fenced',
        'is_beachfront',
        'beach_frontage',
        'title_status',
        'payment_terms',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'area' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'media' => 'array',
            'is_titled' => 'boolean',
            'vehicle_accessible' => 'boolean',
            'frontage' => 'decimal:2',
            'beach_frontage' => 'decimal:2',
            'is_fenced' => 'boolean',
            'is_beachfront' => 'boolean',
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
     * Check if featured video is a YouTube URL.
     */
    public function isFeaturedVideoYouTube(): bool
    {
        if (! $this->featured_video_url) {
            return false;
        }

        $url = $this->featured_video_url;

        return str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be');
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
