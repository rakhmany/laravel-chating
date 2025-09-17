# Laravel Chat Application

Aplikasi chat real-time menggunakan Laravel dan WebSocket dengan fitur-fitur lengkap untuk komunikasi instant.

## Features

- ✅ **Real-time Messaging** - Chat instant menggunakan WebSocket
- ✅ **Multiple Chat Rooms** - Dukungan untuk multiple room chat
- ✅ **User Authentication** - Sistem login dan registrasi
- ✅ **Online/Offline Status** - Tracking status user online/offline
- ✅ **Private & Public Rooms** - Room pribadi dan publik
- ✅ **Responsive Design** - Tampilan responsive untuk semua device
- ✅ **Message History** - Riwayat pesan tersimpan di database
- ✅ **Admin Panel** - Akses admin dengan username: admin, password: admin

## Requirements

- PHP >= 8.1
- MySQL >= 5.7 atau MariaDB >= 10.2
- Composer
- Node.js & NPM (untuk WebSocket server)

## Installation

### 1. Clone Repository
```bash
git clone https://github.com/rakhmany/laravel-chating.git
cd laravel-chating
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

#### Option A: Using SQLite (Recommended for testing)
Database SQLite sudah dikonfigurasi secara default. Jalankan migrations:
```bash
php artisan migrate --seed
```

#### Option B: Using MySQL
1. Buat database MySQL baru
2. Import file `database.sql` ke database MySQL:
```bash
mysql -u username -p database_name < database.sql
```
3. Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Application
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Demo

Untuk melihat demo aplikasi tanpa setup lengkap, buka file:
```
public/demo.html
```

## Login Credentials

**Admin Account:**
- Username: `admin`
- Password: `admin`

**Sample Users:**
- Username: `john`, Password: `password`
- Username: `jane`, Password: `password`
- Username: `bob`, Password: `password`

## Database Schema

### Tables Overview:

1. **users** - Data pengguna dan autentikasi
2. **chat_rooms** - Informasi room chat
3. **messages** - Pesan chat
4. **chat_room_users** - Relasi user dan room
5. **sessions** - Manajemen session
6. **cache** - Cache aplikasi
7. **jobs** - Queue management

### Key Features:

- **Foreign Key Constraints** untuk integritas data
- **Indexes** untuk performa optimal
- **Soft Deletes** untuk data preservation
- **Timestamps** untuk audit trail

## WebSocket Setup (Optional)

Untuk real-time messaging, install Laravel WebSockets:

```bash
composer require pusher/pusher-php-server
composer require laravel/reverb
```

Update broadcasting configuration:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

Start WebSocket server:
```bash
php artisan reverb:start
```

## API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

### Chat
- `GET /chat` - Main chat interface
- `GET /chat/room/{room}` - Specific room
- `POST /chat/room` - Create new room
- `POST /chat/room/{room}/join` - Join room

### Messages
- `POST /messages` - Send message
- `GET /api/messages/{room}` - Get room messages
- `GET /api/rooms` - Get user rooms

## File Structure

```
laravel-chating/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── ChatController.php
│   │   └── MessageController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── ChatRoom.php
│   │   └── Message.php
│   └── Events/
│       └── MessageSent.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── chat/
│   │   ├── index.blade.php
│   │   └── room.blade.php
│   └── layouts/
│       └── app.blade.php
├── routes/
│   ├── web.php
│   └── channels.php
├── public/
│   └── demo.html
└── database.sql
```

## Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Create Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Screenshots

### Login Page
Modern dan responsive login interface dengan demo credentials.

### Chat Interface
- Sidebar dengan daftar room chat
- Real-time messaging area
- Online users list
- Message composer

### Features Demo
- Multiple chat rooms
- Real-time message updates
- User online/offline status
- Room creation and management

## Support

Jika mengalami masalah atau memiliki pertanyaan:

1. Check [Issues](https://github.com/rakhmany/laravel-chating/issues)
2. Create new issue dengan detail lengkap
3. Sertakan environment information (PHP version, OS, etc.)

## Roadmap

- [ ] File upload support
- [ ] Emoji support
- [ ] Voice messages
- [ ] Video calls
- [ ] Message reactions
- [ ] User mentions
- [ ] Push notifications
- [ ] Mobile app (React Native/Flutter)
