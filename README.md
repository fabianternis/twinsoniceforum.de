# Twins on Ice Community Forum ⛸️✨

Modern enterprise **Laravel 13** web application & community forum dedicated to **Twins on Ice** (Emilia & Letizia Macula).

---

## 🔒 Security & OAuth Integration

### 1. Multi-Provider OAuth Setup (Laravel Socialite)
This application supports multi-provider OAuth login for **Google**, **GitHub**, **Discord**, **Twitch**, **Twitter/X**, and **Microsoft**.

To configure OAuth credentials in `.env`:

#### **Google OAuth**
1. Go to [Google Cloud Console](https://console.cloud.google.com/apis/credentials).
2. Create an **OAuth 2.0 Client ID** (Web application).
3. Set **Authorized redirect URIs**: `https://twinsoniceforum.dnbx.de/auth/google/callback`
4. Copy Client ID and Secret to `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   ```

#### **GitHub OAuth**
1. Go to [GitHub Developer Settings](https://github.com/settings/developers).
2. Click **New OAuth App**.
3. Set **Authorization callback URL**: `https://twinsoniceforum.dnbx.de/auth/github/callback`
4. Copy Client ID and Secret to `.env`:
   ```env
   GITHUB_CLIENT_ID=your_client_id
   GITHUB_CLIENT_SECRET=your_client_secret
   ```

#### **Discord OAuth**
1. Go to [Discord Developer Portal](https://discord.com/developers/applications).
2. Create an Application & navigate to **OAuth2**.
3. Add Redirect URI: `https://twinsoniceforum.dnbx.de/auth/discord/callback`
4. Copy Client ID and Secret to `.env`:
   ```env
   DISCORD_CLIENT_ID=your_client_id
   DISCORD_CLIENT_SECRET=your_client_secret
   ```

#### **Twitch OAuth**
1. Go to [Twitch Developer Console](https://dev.twitch.tv/console/apps).
2. Register an Application with OAuth Redirect: `https://twinsoniceforum.dnbx.de/auth/twitch/callback`
3. Copy Client ID and Secret to `.env`:
   ```env
   TWITCH_CLIENT_ID=your_client_id
   TWITCH_CLIENT_SECRET=your_client_secret
   ```

#### **Twitter / X OAuth 2.0**
1. Go to [Twitter Developer Portal](https://developer.twitter.com/en/portal/dashboard).
2. Enable OAuth 2.0 and set Callback URL: `https://twinsoniceforum.dnbx.de/auth/twitter-oauth-2/callback`
3. Copy Client ID and Secret to `.env`:
   ```env
   TWITTER_CLIENT_ID=your_client_id
   TWITTER_CLIENT_SECRET=your_client_secret
   ```

#### **Microsoft OAuth**
1. Go to [Azure Portal](https://portal.azure.com/#blade/Microsoft_AAD_RegisteredApps/ApplicationsListBlade).
2. Register an Application and set Redirect URI: `https://twinsoniceforum.dnbx.de/auth/microsoft/callback`
3. Copy Client ID and Secret to `.env`:
   ```env
   MICROSOFT_CLIENT_ID=your_client_id
   MICROSOFT_CLIENT_SECRET=your_client_secret
   ```

---

### 2. Cloudflare Turnstile CAPTCHA Setup
Privacy-friendly CAPTCHA protection for login, registration, and form submissions.

1. Go to [Cloudflare Dashboard &rarr; Turnstile](https://dash.cloudflare.com/?to=/:account/turnstile).
2. Add your domain (`twinsoniceforum.de` & `twinsoniceforum.dnbx.de`).
3. Set Site Key & Secret Key in `.env`:
   ```env
   TURNSTILE_SITE_KEY=0x4AAAAAA...
   TURNSTILE_SECRET_KEY=0x4AAAAAA...
   ```
*(Note: Testing mode uses default Cloudflare keys `1x00000000000000000000AA` which always pass).*

---

### 3. Cloudflare R2 (S3-Compatible Storage) Setup
Cloudflare R2 provides zero-egress object storage for user avatars, topic attachments, and uploaded media.

1. Go to [Cloudflare Dashboard &rarr; R2](https://dash.cloudflare.com/?to=/:account/r2).
2. Create a Bucket named `twinsoniceforum-storage`.
3. Generate an R2 API Token with **Edit** permissions.
4. Copy Endpoint URL, Access Key ID, and Secret Access Key to `.env`:
   ```env
   FILESYSTEM_DISK=r2
   CLOUDFLARE_R2_ACCESS_KEY_ID=your_access_key_id
   CLOUDFLARE_R2_SECRET_ACCESS_KEY=your_secret_access_key
   CLOUDFLARE_R2_BUCKET=twinsoniceforum-storage
   CLOUDFLARE_R2_ENDPOINT=https://<account_id>.r2.cloudflarestorage.com
   CLOUDFLARE_R2_PUBLIC_URL=https://pub-<hash>.r2.dev
   ```

---

## 📦 Installed Packages

- `laravel/socialite` - Multi-Provider OAuth authentication.
- `league/flysystem-aws-s3-v3` - Cloudflare R2 S3 storage driver.
- `spatie/laravel-permission` - Role & Permission management (`admin`, `moderator`, `member`).
- `spatie/laravel-activitylog` - Audit logging & activity trail inspector (`/admin/audit`).
- `spatie/laravel-sluggable` - Automatic SEO slug generation.
- `SoftDeletes` - Soft delete & trash recovery manager (`/admin/trash`).

## 🌐 Server Deployment

- **Live URL**: `https://twinsoniceforum.dnbx.de`
- **GitHub Repository**: [fabianternis/twinsoniceforum.de](https://github.com/fabianternis/twinsoniceforum.de)
