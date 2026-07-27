<?php
use App\Icon;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Twins on Ice Community Forum') ?></title>
    <meta name="description" content="Das offizielle Community-Forum fuer Twins on Ice (Emilia & Letizia Macula) – Eiskunstlauf, Musik, CHECK DAS, Vlogs, TikTok & Fan Lounge.">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

    <!-- Top Navigation Bar -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="/" class="brand-logo">
                <div class="brand-icon"><?= Icon::render('skate') ?></div>
                <div style="display: flex; flex-direction: column;">
                    <span>Twins <span class="brand-accent">on Ice</span></span>
                    <span style="font-size: 0.7rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em; margin-top: -4px;">Community Forum</span>
                </div>
            </a>

            <div class="nav-links">
                <button class="search-trigger-btn" id="searchOpenBtn">
                    <?= Icon::render('search') ?> <span>Suchen...</span>
                    <kbd style="background: #0f172a; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">Ctrl+K</kbd>
                </button>

                <a href="/" class="nav-link"><?= Icon::render('home') ?> Forum</a>
                <a href="/category/musik-check-das" class="nav-link"><?= Icon::render('music') ?> CHECK DAS</a>
                <a href="/category/eiskunstlauf-training" class="nav-link"><?= Icon::render('skate') ?> Eiskunstlauf</a>

                <?php if (isset($user) && $user): ?>
                    <a href="/topic/create" class="btn btn-primary"><?= Icon::render('plus') ?> Neues Thema</a>
                    
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-left: 0.5rem;">
                        <a href="/user/<?= urlencode($user['username']) ?>" style="display: flex; align-items: center; gap: 0.5rem;">
                            <img src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="avatar-sm">
                            <span style="font-weight: 600; color: #fff; font-size: 0.9rem;"><?= htmlspecialchars($user['username']) ?></span>
                        </a>
                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="/admin" class="badge badge-amber">Admin</a>
                        <?php endif; ?>
                        <a href="/logout" class="nav-link" style="color: #ef4444;" title="Abmelden"><?= Icon::render('logout') ?></a>
                    </div>
                <?php else: ?>
                    <a href="/login" class="btn btn-secondary">Anmelden</a>
                    <a href="/register" class="btn btn-accent">Registrieren</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Search Modal -->
    <div class="modal-overlay" id="searchModal">
        <div class="modal-box">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #fff; font-weight: 700;">
                    <?= Icon::render('search') ?>
                    <span>Thema & Beitraege suchen</span>
                </div>
                <button id="searchCloseBtn" style="background: none; border: none; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 4px;">
                    <?= Icon::render('close') ?>
                </button>
            </div>
            <div style="padding: 1.5rem;">
                <input type="text" id="searchInput" class="input-field" placeholder="Suchbegriff eingeben (z.B. Eiskunstlauf, CHECK DAS, Vlogs)...">
                <div id="searchResults" style="margin-top: 1rem; max-height: 350px; overflow-y: auto;">
                    <p style="color: var(--text-dim); text-align: center; padding: 1rem;">Tippe etwas ein, um Themen zu suchen...</p>
                </div>
            </div>
        </div>
    </div>
