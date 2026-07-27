# User & Admin Instructions: Twins on Ice Community Forum

Welcome to the **Twins on Ice Community Forum** documentation! This guide explains how to operate, configure, and maintain the forum application.

---

## 🛠️ 1. Environment & API Credentials Configuration (`.env`)

All environment configuration is stored in `/var/www/twinsoniceforum/.env`.

### 🔑 Multi-Provider OAuth (Laravel Socialite)
To enable social login buttons on the login & register pages, add your credentials from each provider:

```env
# Google OAuth (https://console.cloud.google.com/apis/credentials)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://twinsoniceforum.dnbx.de/auth/google/callback

# GitHub OAuth (https://github.com/settings/developers)
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI=https://twinsoniceforum.dnbx.de/auth/github/callback

# Discord OAuth (https://discord.com/developers/applications)
DISCORD_CLIENT_ID=your_discord_client_id
DISCORD_CLIENT_SECRET=your_discord_secret
DISCORD_REDIRECT_URI=https://twinsoniceforum.dnbx.de/auth/discord/callback

# Twitch OAuth (https://dev.twitch.tv/console/apps)
TWITCH_CLIENT_ID=your_twitch_client_id
TWITCH_CLIENT_SECRET=your_twitch_secret
TWITCH_REDIRECT_URI=https://twinsoniceforum.dnbx.de/auth/twitch/callback

# Twitter / X OAuth 2.0 (https://developer.twitter.com/en/portal/dashboard)
TWITTER_CLIENT_ID=your_twitter_client_id
TWITTER_CLIENT_SECRET=your_twitter_secret
TWITTER_REDIRECT_URI=https://twinsoniceforum.dnbx.de/auth/twitter-oauth-2/callback

# Microsoft OAuth (https://portal.azure.com/#blade/Microsoft_AAD_RegisteredApps)
MICROSOFT_CLIENT_ID=your_microsoft_client_id
MICROSOFT_CLIENT_SECRET=your_microsoft_secret
MICROSOFT_REDIRECT_URI=https://twinsoniceforum.dnbx.de/auth/microsoft/callback
```

---

### 🛡️ Cloudflare Turnstile CAPTCHA Setup
Turnstile protects forms against bots without annoying image puzzles.

1. Create a site at [Cloudflare Turnstile Dashboard](https://dash.cloudflare.com/?to=/:account/turnstile).
2. Set your domains: `twinsoniceforum.de` & `twinsoniceforum.dnbx.de`.
3. Add your keys to `.env`:
```env
TURNSTILE_SITE_KEY=0x4AAAAAA...
TURNSTILE_SECRET_KEY=0x4AAAAAA...
```

---

### ☁️ Cloudflare R2 (S3-Compatible Object Storage)
Cloudflare R2 provides zero-egress fee object storage for user avatars and attachments.

1. Go to [Cloudflare Dashboard &rarr; R2](https://dash.cloudflare.com/?to=/:account/r2).
2. Create a Bucket named `twinsoniceforum-storage`.
3. Generate an R2 API Token with **Edit** access.
4. Update `.env`:
```env
FILESYSTEM_DISK=r2
CLOUDFLARE_R2_ACCESS_KEY_ID=your_r2_access_key
CLOUDFLARE_R2_SECRET_ACCESS_KEY=your_r2_secret_key
CLOUDFLARE_R2_BUCKET=twinsoniceforum-storage
CLOUDFLARE_R2_ENDPOINT=https://<your_account_id>.r2.cloudflarestorage.com
CLOUDFLARE_R2_PUBLIC_URL=https://pub-<hash>.r2.dev
```

---

## 👑 2. Administration & Security Features

### Default Administrator Account
- **URL**: `https://twinsoniceforum.dnbx.de/login`
- **Email**: `admin@twinsoniceforum.de`
- **Username**: `Admin`
- **Password**: `TwinsOnIce2026!`

### 📜 Spatie Activity Log & Audit Inspector
- **URL**: `https://twinsoniceforum.dnbx.de/admin/audit`
- Logs all user registrations, logins, OAuth events, topic creations, category edits, and soft deletions.

### 🗑️ SoftDeletes & Trash Recovery
- **URL**: `https://twinsoniceforum.dnbx.de/admin/trash`
- Allows administrators to inspect soft-deleted topics, restore them with a single click (`$topic->restore()`), or permanently purge them (`$topic->forceDelete()`).

---

## 🖥️ 3. Server Maintenance Commands

Run from project root `/var/www/twinsoniceforum`:

```bash
# Clear application cache
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Re-run migrations and seeders (CAUTION: Resets database)
php artisan migrate:fresh --seed

# View registered routes
php artisan route:list
```
