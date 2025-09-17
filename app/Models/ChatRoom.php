<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_private',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_private' => 'boolean',
        ];
    }

    /**
     * Get the user who created this chat room.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the users in this chat room.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_room_users')
                    ->withPivot('joined_at', 'last_read_at')
                    ->withTimestamps();
    }

    /**
     * Get the messages in this chat room.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the latest message in this chat room.
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }
}