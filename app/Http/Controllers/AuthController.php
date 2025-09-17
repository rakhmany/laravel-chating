<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('chat');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            // Update user online status
            Auth::user()->update(['is_online' => true]);
            
            return redirect()->route('chat');
        }

        throw ValidationException::withMessages([
            'username' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        // Update user offline status
        if (Auth::check()) {
            Auth::user()->update([
                'is_online' => false,
                'last_seen' => now()
            ]);
        }

        Auth::logout();
        return redirect()->route('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('chat');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'is_online' => true,
        ]);

        Auth::login($user);

        return redirect()->route('chat');
    }
}