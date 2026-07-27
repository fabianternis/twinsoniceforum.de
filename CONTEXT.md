# AGENT & CONTEXT DOCUMENTATION: Twins on Ice Community Forum

> **For Future AI Agents & Developers**: This document provides the complete architecture context, design rules, package implementations, database schemas, and codebase layout for `twinsoniceforum.de` / `twinsoniceforum.dnbx.de`.

---

## 📌 1. Project Overview & Requirements

- **Project**: Twins on Ice Community Forum (Emilia & Letizia Macula figure skating, music "CHECK DAS", vlogs, fan lounge).
- **Domains**:
  - Production: `twinsoniceforum.de` & `*.twinsoniceforum.de`
  - Staging / Current Active Deployment: `twinsoniceforum.dnbx.de`
- **Git Repository**: `https://github.com/fabianternis/twinsoniceforum.de` (branch: `main`)
- **Git Author Email**: `git-agent@fabian.ternismail.de`
- **Framework**: Laravel 13 (PHP 8.3)

---

## 🎨 2. Strict Design Rules (Mandatory)

1. **NO EMOJIS**: Under no circumstances use emoji characters in views or code outputs.
2. **NO GRADIENTS & NO PURPLE**:
   - Zero gradients on buttons, backgrounds, or cards.
   - Zero purple accent colors.
   - Primary palette: Slate dark (`#0b1120`, `#151e32`, `#1e293b`), Primary Cyan (`#0284c7`), Sky Blue (`#38bdf8`), Accent Muted Slate (`#334155`).
3. **SVG ICON SYSTEM**: All icons must be rendered as vector SVGs via `App\Icon::render('icon-name')`.

---

## 📦 3. Package Architecture & Spatie Integration

### `spatie/laravel-permission`
- **Roles**: `admin`, `moderator`, `member`.
- **User Model**: Uses `HasRoles` trait (`App\Models\User`).
- **Seeder**: Creates default roles & permissions in `DatabaseSeeder.php`.

### `spatie/laravel-activitylog`
- **Models Monitored**: `User`, `Category`, `Topic`, `Post`, `Shout`.
- **Logs Captured**: User registrations, logins, OAuth logins, topic creation, category creation, profile updates, and soft deletions.
- **Audit View**: `App\Http\Controllers\AdminController@auditLogs` rendering `resources/views/admin_audit.blade.php`.

### `spatie/laravel-sluggable`
- **Models**: `Category` (generates `slug` from `name`), `Topic` (generates `slug` from `title`).

### `SoftDeletes`
- **Models**: `User`, `Category`, `Topic`, `Post`, `Poll`.
- **Trash Manager**: `AdminController@trash` rendering `resources/views/admin_trash.blade.php`. Supports restore (`$topic->restore()`) and permanent removal (`$topic->forceDelete()`).

### `laravel/socialite` (Multi-Provider OAuth)
- **Supported Providers**: `google`, `github`, `discord`, `twitch`, `twitter-oauth-2`, `microsoft`.
- **Controller**: `App\Http\Controllers\SocialAuthController`.
- **Routes**: `/auth/{provider}` (redirect) and `/auth/{provider}/callback`.

### `Cloudflare Turnstile` & `Cloudflare R2`
- **Turnstile Captcha**: Validates tokens via `App\Rules\TurnstileRule` against `https://challenges.cloudflare.com/turnstile/v0/siteverify`.
- **R2 Storage**: `league/flysystem-aws-s3-v3` configured as `r2` disk in `config/filesystems.php`.

---

## 📁 4. Directory & Codebase Map

```
/var/www/twinsoniceforum/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php       # Dashboard, Spatie Audit Inspector & Trash Manager
│   │   ├── AuthController.php        # Traditional Auth with Turnstile protection
│   │   ├── CategoryController.php    # Category topics listing
│   │   ├── ChatController.php        # AJAX Fan Shoutbox API
│   │   ├── HomeController.php        # Forum dashboard & subdomain handling
│   │   ├── ProfileController.php     # User profile view & update
│   │   ├── SocialAuthController.php  # Multi-Provider OAuth controller
│   │   └── TopicController.php       # Topic CRUD, replies, reactions, polls, soft deletes
│   ├── Models/                       # User, Category, Topic, Post, Reaction, Poll, PollOption, PollVote, Shout
│   ├── Rules/
│   │   └── TurnstileRule.php         # Cloudflare Turnstile token validation rule
│   ├── Icon.php                      # Inline SVG vector icon renderer helper
│   └── SubdomainHandler.php          # Subdomain detection helper
├── config/
│   ├── activitylog.php               # Spatie Activitylog configuration
│   ├── filesystems.php               # Cloudflare R2 S3 storage disk setup
│   ├── permission.php                # Spatie Permission configuration
│   └── services.php                  # OAuth providers & Turnstile key config
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_07_27_000001_create_forum_tables.php
│   │   ├── 2026_07_27_000002_add_oauth_columns_to_users_table.php
│   │   ├── 2026_07_27_230832_create_activity_log_table.php
│   │   └── 2026_07_27_230832_create_permission_tables.php
│   └── seeders/
│       └── DatabaseSeeder.php        # Roles, permissions, admin user & demo content
├── public/
│   ├── assets/css/app.css            # Dark slate flat CSS (No gradients, no purple)
│   ├── assets/js/app.js             # Live Shoutbox AJAX & modal controller
│   └── index.php
├── resources/views/
│   ├── admin.blade.php               # Admin overview panel
│   ├── admin_audit.blade.php         # Spatie Activity Log viewer
│   ├── admin_trash.blade.php         # SoftDeletes Trash manager
│   ├── category.blade.php            # Category topics list
│   ├── home.blade.php                # Main dashboard view
│   ├── login.blade.php               # Login form with OAuth & Turnstile
│   ├── profile.blade.php             # User profile view
│   ├── register.blade.php            # Registration form with OAuth & Turnstile
│   ├── topic.blade.php              # Topic detail view, replies, polls, reactions
│   └── topic_create.blade.php       # Create topic form
├── routes/
│   └── web.php                       # Web routes definition
├── USER_INSTRUCTIONS.md               # User setup guide
└── CONTEXT.md                        # Project context & architecture guide (this file)
```

---

## 🌐 5. Web Server Configuration (Caddy)

Caddyfile located at `/etc/caddy/Caddyfile`:

```caddy
twinsoniceforum.dnbx.de {
    tls internal
    root * /var/www/twinsoniceforum/public
    encode zstd gzip
    try_files {path} {path}/ /index.php?{query}
    php_fastcgi unix//run/php/php8.3-fpm.sock
    file_server
}

twinsoniceforum.de, *.twinsoniceforum.de {
    tls internal
    root * /var/www/twinsoniceforum/public
    encode zstd gzip
    try_files {path} {path}/ /index.php?{query}
    php_fastcgi unix//run/php/php8.3-fpm.sock
    file_server
}
```

---

## 💡 6. Guidelines for Future AI Agents

1. **Do Not Introduce Gradients or Purple**: Always maintain the dark slate flat design tokens defined in `public/assets/css/app.css`.
2. **Do Not Use Emojis**: Use `App\Icon::render('icon_name')` for all UI icons.
3. **Log Everything with Spatie Activitylog**: When building new features or modifying entities, call `activity()->log(...)` to maintain the audit trail.
4. **Preserve SoftDeletes**: Never call `$model->forceDelete()` directly in standard user flows; only call `$model->delete()` so items are soft deleted and recoverable in the Trash Manager.
