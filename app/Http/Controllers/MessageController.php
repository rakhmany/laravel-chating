<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'chat_room_id' => 'required|exists:chat_rooms,id',
        ]);

        $room = ChatRoom::findOrFail($request->chat_room_id);

        // Check if user has access to this room
        if ($room->is_private && !$room->users()->where('user_id', Auth::id())->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'user_id' => Auth::id(),
            'chat_room_id' => $request->chat_room_id,
            'message' => $request->message,
            'type' => 'text',
        ]);

        $message->load('user');

        // Broadcast message to room (for websocket implementation)
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function index(ChatRoom $room)
    {
        // Check if user has access to this room
        if ($room->is_private && !$room->users()->where('user_id', Auth::id())->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $room->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }

    public function getMessages(ChatRoom $room)
    {
        return $this->index($room);
    }
}