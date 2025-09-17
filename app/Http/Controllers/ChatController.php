<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $rooms = Auth::user()->chatRooms()->with('latestMessage')->get();
        $allRooms = ChatRoom::where('is_private', false)->with('latestMessage')->get();
        
        return view('chat.index', compact('rooms', 'allRooms'));
    }

    public function show(ChatRoom $room)
    {
        // Check if user has access to this room
        if ($room->is_private && !$room->users()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this chat room.');
        }

        // If not private, auto-join user to room
        if (!$room->is_private && !$room->users()->where('user_id', Auth::id())->exists()) {
            $room->users()->attach(Auth::id(), ['joined_at' => now()]);
        }

        $messages = $room->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();

        $users = $room->users()->get();

        return view('chat.room', compact('room', 'messages', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);

        $room = ChatRoom::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_private' => $request->boolean('is_private'),
            'created_by' => Auth::id(),
        ]);

        // Add creator to the room
        $room->users()->attach(Auth::id(), ['joined_at' => now()]);

        return redirect()->route('chat.room', $room);
    }

    public function join(ChatRoom $room)
    {
        if (!$room->users()->where('user_id', Auth::id())->exists()) {
            $room->users()->attach(Auth::id(), ['joined_at' => now()]);
        }

        return redirect()->route('chat.room', $room);
    }

    public function getRooms()
    {
        $rooms = Auth::user()->chatRooms()->with('latestMessage')->get();
        return response()->json($rooms);
    }
}