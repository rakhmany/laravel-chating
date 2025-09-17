<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'is_online' => false,
        ]);

        // Create a general chat room
        $generalRoom = ChatRoom::create([
            'name' => 'General Chat',
            'description' => 'General discussion room for all users',
            'is_private' => false,
            'created_by' => $admin->id,
        ]);

        // Add admin to the general room
        $generalRoom->users()->attach($admin->id, [
            'joined_at' => now(),
        ]);

        // Create some sample users
        $users = [
            ['name' => 'John Doe', 'username' => 'john'],
            ['name' => 'Jane Smith', 'username' => 'jane'],
            ['name' => 'Bob Wilson', 'username' => 'bob'],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'username' => $userData['username'],
                'password' => Hash::make('password'),
                'is_online' => false,
            ]);

            // Add each user to the general room
            $generalRoom->users()->attach($user->id, [
                'joined_at' => now(),
            ]);
        }
    }
}