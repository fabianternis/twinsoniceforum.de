# Twins on Ice Community Forum ⛸️✨

Modern PHP-based web application & community forum dedicated to **Twins on Ice** (Emilia & Letizia Macula) – German figure skaters, influencers & content creators.

## 🚀 Features

- **Modern Icy Aesthetics**: Ultra-sleek dark glassmorphism UI built with CSS variables, custom Google Fonts (`Outfit` & `Inter`), frosted glass cards, and responsive layouts.
- **Dynamic Subdomain Routing**: Supports `twinsoniceforum.de` and wildcards (`music.twinsoniceforum.de`, `skating.twinsoniceforum.de`, `vip.twinsoniceforum.de`, `vlog.twinsoniceforum.de`) to serve targeted hubs dynamically.
- **Core Forum Capabilities**:
  - Categories & Boards (Figure Skating & Training, Music & "CHECK DAS", Vlogs & Social Media, Fashion, Meet & Greets, Fan Lounge).
  - Pinned & Locked topics, reaction system (❤️, ⛸️, 🔥), best answer indicators.
  - Interactive Polls with live vote count and animated progress bars.
  - Threaded post replies & view counter.
- **Real-Time Fan Shoutbox**: Instant live-chat widget with AJAX polling for active fans.
- **User System & Role-Based Permissions**:
  - Secure registration & authentication with password hashing.
  - User profiles with custom rank badges (`Ice Queen 👑 VIP`, `Gold Skater ⛸️`), avatars, bio, and post counters.
  - Admin Control Panel (`/admin`) for managing categories and inspecting user stats.
- **Instant Search Modal**: Press `Ctrl+K` to launch instant client-side / AJAX search.
- **Zero-Config Database Migration**: Auto-initializes MySQL tables & seeds initial sample data on first launch.

## 🛠️ Stack & Requirements

- **PHP**: 8.3+ (NTS/FPM)
- **Database**: MySQL / MariaDB (PDO UTF8MB4)
- **Webserver**: Caddy / Apache / Nginx
- **Frontend**: Vanilla HTML5, CSS3 Glassmorphism, Vanilla JS (ES6+)

## 🌐 Server Deployment

Deployed at: `https://twinsoniceforum.dnbx.de` and configured for `twinsoniceforum.de` / `*.twinsoniceforum.de`.
