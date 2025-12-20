<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'listing_id',
        'body',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * User who sent the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * User who received the message.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Listing this message is related to (if any).
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Check if message is read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}
