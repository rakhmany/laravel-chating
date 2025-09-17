<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'is_online',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'last_seen' => 'datetime',
        ];
    }

    /**
     * Get the chat rooms created by this user.
     */
    public function createdRooms()
    {
        return $this->hasMany(ChatRoom::class, 'created_by');
    }

    /**
     * Get the chat rooms this user belongs to.
     */
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_users')
                    ->withPivot('joined_at', 'last_read_at')
                    ->withTimestamps();
    }

    /**
     * Get the messages sent by this user.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}