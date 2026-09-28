# Couples Private Website — Modern PHP Application

A luxury, minimal, romantic private website for couples to chat, share photos, track relationship timelines, save scrapbook memories, and manage their private digital space, complete with a comprehensive Admin Management Dashboard.

---

## 🌟 Key Features

### For Couples:
- **Private Space**: Isolated couple digital home for two people.
- **Real-Time AJAX Chat**: Send text & photo messages, emoji picker, reply snippets, message deletion, read status, auto-scroll.
- **Disappearing Messages**: Message retention policies configured by system settings & automated cron cleanup.
- **Shared Photo Gallery**: Grid gallery with upload lightbox modal, captions, uploader info, and deletion options.
- **Digital Scrapbook Memories**: Memory cards with date, location, cover photos, description, and photo galleries.
- **Relationship Timeline**: Chronological milestone timeline with custom icons and date badges.
- **Relationship Counter**: Displays duration together ("Together for 2 years, 4 months").
- **Couple Profile**: Customize names, birthdays, couple motto/bio, and cover images.
- **Mobile First Design**: Fixed mobile bottom navigation bar (Home, Chat, Photos, Memories, Profile).

### For Admins:
- **Admin Dashboard**: Comprehensive stats (Couples, Users, Messages, Photos, Memories) and system overview.
- **Couple Management**: "Create Couple" wizard form with automated partner credential generation & success summary.
- **User Management**: Search users, toggle enable/disable, reset partner passwords.
- **Chat History Monitoring**: Audit chat logs with warning banner, search keywords, date filters, and content removal tools.
- **Photo Moderation**: Visual photo gallery preview across couples with moderation deletion tools.
- **System Settings**: Configure site name, timezone, maintenance mode, retention rules, upload limits, rate limiting.
- **Activity Logging**: Full audit trail of admin and user security events.

---

## 🛠️ System Requirements

- **PHP**: 8.2 or higher (with `pdo`, `pdo_mysql`, `fileinfo`, `mbstring` extensions)
- **MySQL**: 8.0+ / MariaDB 10.4+
- **Web Server**: Apache (`mod_rewrite` enabled) or Nginx
- **Node.js**: **Not required** (pure Vanilla JS, Bootstrap 5 & FontAwesome CDN)

---

## 🚀 Quick Setup & Installation Guide

### 1. Database Creation
Create a new MySQL database named `couples_db`:
```sql
CREATE DATABASE couples_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Environment Configuration
Copy `.env.example` to `.env` and configure your database credentials and URL:
```ini
APP_NAME="Couples Private Website"
APP_ENV=local
APP_URL="http://localhost/love/public"

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=couples_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Database Migration & Seeder
Run the CLI seeder script to initialize tables and populate demo data:
```bash
php database/seeder.php
```

---

## 🔐 Default Demo Credentials

### System Administrator
- **URL**: `http://localhost/love/public/admin/login`
- **Username / Email**: `admin`
- **Password**: `padmin`

### Couple User Login (Partner 1)
- **URL**: `http://localhost/love/public/login`
- **Username / Email**: `user1`
- **Password**: `puser1`

### Couple User Login (Partner 2)
- **URL**: `http://localhost/love/public/login`
- **Username / Email**: `user2`
- **Password**: `puser1`

---

## ⏰ Scheduled Cron Job (Disappearing Messages Cleanup)

To enforce automated message deletion according to retention settings, add a cron job on your server to execute every hour:

```bash
0 * * * * php /path/to/love/cron/cleanup.php >/dev/null 2>&1
```

---

## 📂 Project Structure

```
love/
├── app/
│   ├── Controllers/       # Auth, Dashboard, Chat, Photo, Memory, Timeline, Profile, Admin, Api
│   ├── Models/            # Base PDO Model & entity models
│   ├── Middleware/        # Auth, Admin, CSRF middlewares
│   ├── Services/          # UploadService
│   ├── Helpers/           # Functions & Security helpers
│   └── Views/             # Romantic UI & Admin blade-like views
├── config/                # app.php & database.php
├── public/                # Document Root (index.php, .htaccess, assets, uploads)
├── routes/                # web.php & api.php
├── database/              # schema.sql & seeder.php
├── cron/                  # cleanup.php
└── .env                   # Environment variables
```
