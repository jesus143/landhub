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
}
