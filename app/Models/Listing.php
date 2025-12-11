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
        ];
    }
}
