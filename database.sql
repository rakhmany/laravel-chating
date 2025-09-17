-- Laravel Chat Application Database Schema
-- This SQL file creates the database structure for a Laravel chat application with WebSocket support

-- Create database (uncomment if needed)
-- CREATE DATABASE laravel_chat;
-- USE laravel_chat;

-- Users table - stores user information and authentication data
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `last_seen` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password reset tokens table
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions table - for user session management
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache table - for application caching
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache locks table
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs table - for queue management
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Job batches table
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Failed jobs table
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat rooms table - stores chat room information
CREATE TABLE `chat_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_rooms_created_by_foreign` (`created_by`),
  CONSTRAINT `chat_rooms_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Messages table - stores all chat messages
CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `chat_room_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `type` enum('text','image','file') NOT NULL DEFAULT 'text',
  `is_edited` tinyint(1) NOT NULL DEFAULT 0,
  `edited_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_user_id_foreign` (`user_id`),
  KEY `messages_chat_room_id_foreign` (`chat_room_id`),
  KEY `messages_chat_room_id_created_at_index` (`chat_room_id`,`created_at`),
  CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_chat_room_id_foreign` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat room users table - junction table for users and chat rooms
CREATE TABLE `chat_room_users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `chat_room_id` bigint(20) UNSIGNED NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_room_users_user_id_chat_room_id_unique` (`user_id`,`chat_room_id`),
  KEY `chat_room_users_chat_room_id_foreign` (`chat_room_id`),
  CONSTRAINT `chat_room_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chat_room_users_chat_room_id_foreign` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert initial data

-- Insert admin user (username: admin, password: admin)
-- Password hash for 'admin' using Laravel's Hash::make('admin')
INSERT INTO `users` (`id`, `name`, `username`, `password`, `is_online`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NOW(), NOW());

-- Insert sample users
INSERT INTO `users` (`id`, `name`, `username`, `password`, `is_online`, `created_at`, `updated_at`) VALUES
(2, 'John Doe', 'john', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NOW(), NOW()),
(3, 'Jane Smith', 'jane', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NOW(), NOW()),
(4, 'Bob Wilson', 'bob', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NOW(), NOW());

-- Insert sample chat rooms
INSERT INTO `chat_rooms` (`id`, `name`, `description`, `is_private`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'General Chat', 'General discussion room for all users', 0, 1, NOW(), NOW()),
(2, 'Tech Discussion', 'Technology and programming discussions', 0, 1, NOW(), NOW()),
(3, 'Random Chat', 'Random discussions and off-topic conversations', 0, 1, NOW(), NOW()),
(4, 'Admin Only', 'Private room for administrators', 1, 1, NOW(), NOW());

-- Add users to chat rooms
INSERT INTO `chat_room_users` (`user_id`, `chat_room_id`, `joined_at`, `created_at`, `updated_at`) VALUES
(1, 1, NOW(), NOW(), NOW()),
(1, 2, NOW(), NOW(), NOW()),
(1, 3, NOW(), NOW(), NOW()),
(1, 4, NOW(), NOW(), NOW()),
(2, 1, NOW(), NOW(), NOW()),
(2, 2, NOW(), NOW(), NOW()),
(3, 1, NOW(), NOW(), NOW()),
(3, 3, NOW(), NOW(), NOW()),
(4, 1, NOW(), NOW(), NOW());

-- Insert sample messages
INSERT INTO `messages` (`user_id`, `chat_room_id`, `message`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, 'Welcome to the General Chat room! This is a Laravel-based chat application with WebSocket support.', 'text', NOW(), NOW()),
(1, 1, 'You can create new rooms, send real-time messages, and see who''s online.', 'text', NOW(), NOW()),
(2, 1, 'Thanks for setting this up! This is really cool.', 'text', NOW(), NOW()),
(3, 1, 'I love the real-time messaging feature!', 'text', NOW(), NOW()),
(1, 2, 'This room is for technology discussions. Feel free to share your thoughts on programming, frameworks, and tools.', 'text', NOW(), NOW()),
(2, 2, 'Laravel is such a powerful framework for building web applications.', 'text', NOW(), NOW()),
(1, 3, 'This is the random chat room. Feel free to discuss anything here!', 'text', NOW(), NOW());

-- Update AUTO_INCREMENT values
ALTER TABLE `users` AUTO_INCREMENT = 5;
ALTER TABLE `chat_rooms` AUTO_INCREMENT = 5;
ALTER TABLE `messages` AUTO_INCREMENT = 8;
ALTER TABLE `chat_room_users` AUTO_INCREMENT = 10;

-- Create indexes for better performance
CREATE INDEX idx_users_online ON users(is_online);
CREATE INDEX idx_messages_room_created ON messages(chat_room_id, created_at);
CREATE INDEX idx_chat_room_users_user ON chat_room_users(user_id);
CREATE INDEX idx_chat_room_users_room ON chat_room_users(chat_room_id);