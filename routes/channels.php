<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    // Check if user is in the chat room
    return $user->chatRooms()->where('chat_rooms.id', $roomId)->exists();
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});