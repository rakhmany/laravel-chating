@extends('layouts.app')

@section('title', 'Chat - Laravel Chat')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chat Rooms</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newRoomModal">
                    <i class="fas fa-plus"></i> New Room
                </button>
            </div>
            <div class="card-body p-0 sidebar">
                @foreach($rooms as $room)
                    <a href="{{ route('chat.room', $room) }}" class="room-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $room->name }}</strong>
                                @if($room->latestMessage)
                                    <div class="text-muted small">
                                        {{ Str::limit($room->latestMessage->message, 30) }}
                                    </div>
                                @endif
                            </div>
                            @if($room->is_private)
                                <i class="fas fa-lock text-muted"></i>
                            @endif
                        </div>
                    </a>
                @endforeach

                @if($allRooms->count() > 0)
                    <div class="px-3 py-2 border-top">
                        <small class="text-muted"><strong>Public Rooms</strong></small>
                    </div>
                    @foreach($allRooms as $room)
                        @if(!$rooms->contains($room))
                            <a href="{{ route('chat.room.join', $room) }}" class="room-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $room->name }}</strong>
                                        <div class="text-muted small">
                                            Click to join
                                        </div>
                                    </div>
                                    <i class="fas fa-users text-muted"></i>
                                </div>
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h4>Welcome to Laravel Chat!</h4>
                <p class="text-muted">Select a chat room from the sidebar to start messaging.</p>
            </div>
        </div>
    </div>
</div>

<!-- New Room Modal -->
<div class="modal fade" id="newRoomModal" tabindex="-1" aria-labelledby="newRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('chat.room.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newRoomModalLabel">Create New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roomName" class="form-label">Room Name</label>
                        <input type="text" class="form-control" id="roomName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="roomDescription" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="roomDescription" name="description" rows="2"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="isPrivate" name="is_private" value="1">
                        <label class="form-check-label" for="isPrivate">
                            Private Room
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection