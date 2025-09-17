<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/room/{room}', [ChatController::class, 'show'])->name('chat.room');
    Route::post('/chat/room', [ChatController::class, 'store'])->name('chat.room.store');
    Route::post('/chat/room/{room}/join', [ChatController::class, 'join'])->name('chat.room.join');
    
    // Message routes
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{room}', [MessageController::class, 'index'])->name('messages.index');
    
    // API routes for AJAX
    Route::get('/api/rooms', [ChatController::class, 'getRooms'])->name('api.rooms');
    Route::get('/api/messages/{room}', [MessageController::class, 'getMessages'])->name('api.messages');
});