@extends('layouts.app')

@section('title', $room->name . ' - Laravel Chat')

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card chat-container">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        {{ $room->name }}
                        @if($room->is_private)
                            <i class="fas fa-lock text-muted"></i>
                        @endif
                    </h5>
                    @if($room->description)
                        <small class="text-muted">{{ $room->description }}</small>
                    @endif
                </div>
                <a href="{{ route('chat') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Rooms
                </a>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                @foreach($messages as $message)
                    <div class="message @if($message->user_id == Auth::id()) own @endif" data-message-id="{{ $message->id }}">
                        <div class="message-header">
                            <strong>{{ $message->user->name }}</strong>
                            <span class="text-muted">{{ $message->created_at->format('H:i') }}</span>
                        </div>
                        <div class="message-content">{{ $message->message }}</div>
                    </div>
                @endforeach
            </div>
            
            <div class="card-footer">
                <form id="messageForm" class="d-flex">
                    @csrf
                    <input type="hidden" name="chat_room_id" value="{{ $room->id }}">
                    <input type="text" class="form-control me-2" id="messageInput" name="message" 
                           placeholder="Type your message..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary" id="sendButton">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-users"></i> Online Users ({{ $users->count() }})
                </h6>
            </div>
            <div class="card-body">
                @foreach($users as $user)
                    <div class="mb-2">
                        <span class="online-status @if($user->is_online) online @else offline @endif"></span>
                        {{ $user->name }}
                        @if($user->id == $room->created_by)
                            <i class="fas fa-crown text-warning" title="Room Creator"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const chatMessages = $('#chatMessages');
    const messageForm = $('#messageForm');
    const messageInput = $('#messageInput');
    const sendButton = $('#sendButton');
    const roomId = {{ $room->id }};

    // Auto-scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }

    // Initial scroll to bottom
    scrollToBottom();

    // Handle form submission
    messageForm.on('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.val().trim();
        if (!message) return;

        sendButton.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("messages.store") }}',
            method: 'POST',
            data: {
                message: message,
                chat_room_id: roomId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    messageInput.val('');
                    addMessage(response.message);
                }
            },
            error: function(xhr) {
                alert('Failed to send message. Please try again.');
            },
            complete: function() {
                sendButton.prop('disabled', false);
                messageInput.focus();
            }
        });
    });

    // Add message to chat
    function addMessage(message) {
        const isOwn = message.user_id == {{ Auth::id() }};
        const messageHtml = `
            <div class="message ${isOwn ? 'own' : ''}" data-message-id="${message.id}">
                <div class="message-header">
                    <strong>${message.user.name}</strong>
                    <span class="text-muted">${new Date(message.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'})}</span>
                </div>
                <div class="message-content">${message.message}</div>
            </div>
        `;
        
        chatMessages.append(messageHtml);
        scrollToBottom();
    }

    // Auto-refresh messages every 3 seconds (simple polling for demo)
    setInterval(function() {
        loadMessages();
    }, 3000);

    function loadMessages() {
        $.ajax({
            url: `/api/messages/${roomId}`,
            method: 'GET',
            success: function(messages) {
                const existingMessageIds = [];
                $('.message').each(function() {
                    existingMessageIds.push(parseInt($(this).data('message-id')));
                });

                messages.forEach(function(message) {
                    if (!existingMessageIds.includes(message.id)) {
                        addMessage(message);
                    }
                });
            },
            error: function(xhr) {
                console.log('Failed to load new messages');
            }
        });
    }

    // Focus on message input
    messageInput.focus();

    // Enter key to send message
    messageInput.on('keypress', function(e) {
        if (e.which == 13) {
            messageForm.submit();
        }
    });
});
</script>
@endpush