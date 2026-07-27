<?php
use App\Icon;

$pageTitle = ($subdomainConfig['name'] ?? 'Twins on Ice') . ' – Community Forum';
require __DIR__ . '/layout/header.php';
?>

<!-- Hero Banner Section -->
<section class="hero-banner">
    <div class="container">
        <div class="hero-card glass-card">
            <div class="hero-content">
                <div class="hero-badge">
                    <?= Icon::render('sparkle') ?>
                    <span><?= htmlspecialchars($subdomainConfig['badge'] ?? 'Offizielle Fansite & Community Hub') ?></span>
                </div>
                <h1 class="hero-title">
                    <?= htmlspecialchars($subdomainConfig['title'] ?? 'Willkommen im Twins on Ice Forum') ?>
                </h1>
                <p class="hero-subtitle">
                    <?= htmlspecialchars($subdomainConfig['tagline'] ?? 'Die ultimative Community fuer Eiskunstlauf-Fans, Kuer-Choreografien, Vlogs, Outfits & die Single "CHECK DAS" von Emilia & Letizia Macula.') ?>
                </p>
                
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="/topic/create" class="btn btn-primary"><?= Icon::render('plus') ?> Neues Thema erstellen</a>
                    <a href="/category/musik-check-das" class="btn btn-accent"><?= Icon::render('music') ?> "CHECK DAS" Hub</a>
                    <a href="https://twinsonice.shop" target="_blank" class="btn btn-secondary"><?= Icon::render('external') ?> Fan-Shop</a>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem; min-width: 260px; background: #0f172a; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid #1e293b;" class="desktop-only">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="color: var(--primary-cyan);"><?= Icon::render('music') ?></div>
                    <div>
                        <div style="font-size: 0.75rem; color: var(--primary-cyan); font-weight: 700; text-transform: uppercase;">Single Release</div>
                        <div style="font-weight: 800; font-size: 1.1rem; color: #fff;">CHECK DAS</div>
                    </div>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted);">Offizielles Musikvideo & Single von Emilia & Letizia Macula online!</p>
                <a href="/topic/1" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Zum Diskussions-Thread &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Main Forum Grid -->
<main class="container">
    <div class="forum-grid">
        
        <!-- Left Main Column -->
        <div>
            <!-- Categories Grid -->
            <div class="categories-header">
                <h2 style="font-size: 1.4rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                    <?= Icon::render('pin') ?> Forum Kategorien
                </h2>
                <span style="font-size: 0.85rem; color: var(--text-muted);"><?= count($categories) ?> Themen-Bereiche</span>
            </div>

            <div style="margin-bottom: 2.5rem;">
                <?php foreach ($categories as $cat): ?>
                    <a href="/category/<?= $cat['slug'] ?>" class="category-card glass-card">
                        <div class="category-main">
                            <div class="category-icon-box">
                                <?= Icon::render($cat['icon']) ?>
                            </div>
                            <div class="category-info">
                                <h3><?= htmlspecialchars($cat['name']) ?></h3>
                                <p><?= htmlspecialchars($cat['description']) ?></p>
                            </div>
                        </div>
                        <div class="category-meta">
                            <span class="badge badge-<?= $cat['badge_color'] ?>"><?= $cat['topic_count'] ?> Themen</span>
                            <span style="font-size: 0.8rem; color: var(--text-dim);"><?= $cat['total_replies'] ?> Antworten</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Latest Topics List -->
            <div class="categories-header">
                <h2 style="font-size: 1.4rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                    <?= Icon::render('chat') ?> Aktuelle Themen & Diskussionen
                </h2>
                <a href="/topic/create" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;"><?= Icon::render('plus') ?> Thema</a>
            </div>

            <div class="glass-card" style="overflow: hidden;">
                <?php if (empty($latestTopics)): ?>
                    <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        Noch keine Themen vorhanden. Werde die/der Erste und erstelle ein Thema!
                    </div>
                <?php else: ?>
                    <?php foreach ($latestTopics as $topic): ?>
                        <div class="topic-item">
                            <div class="topic-left">
                                <img src="<?= htmlspecialchars($topic['avatar_url']) ?>" alt="Avatar" class="avatar-sm">
                                <div class="topic-details">
                                    <a href="/topic/<?= $topic['id'] ?>">
                                        <h4>
                                            <?php if ($topic['is_pinned']): ?>
                                                <span class="badge badge-pinned"><?= Icon::render('pin') ?> ANGEPINNT</span>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($topic['title']) ?>
                                        </h4>
                                    </a>
                                    <div class="topic-meta-tags">
                                        <a href="/category/<?= $topic['category_slug'] ?>" class="badge badge-<?= $topic['badge_color'] ?>">
                                            <?= Icon::render($topic['category_icon']) ?> <?= htmlspecialchars($topic['category_name']) ?>
                                        </a>
                                        <span>von <a href="/user/<?= urlencode($topic['username']) ?>" style="color: var(--text-main); font-weight: 600;"><?= htmlspecialchars($topic['username']) ?></a></span>
                                        <span>• <?= date('d.m.Y H:i', strtotime($topic['created_at'])) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="topic-stats">
                                <div style="text-align: center;">
                                    <div style="font-weight: 700; color: #fff;"><?= $topic['replies_count'] ?></div>
                                    <div style="font-size: 0.7rem; color: var(--text-dim);">Antworten</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="font-weight: 700; color: var(--primary-cyan);"><?= $topic['views'] ?></div>
                                    <div style="font-size: 0.7rem; color: var(--text-dim);">Aufrufe</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Sidebar -->
        <aside>
            <!-- Real-time Shoutbox Widget -->
            <div class="sidebar-widget glass-card">
                <h3 class="widget-title">
                    <span>Fan Live-Chat</span>
                    <span style="font-size: 0.7rem; background: #052e16; color: #4ade80; border: 1px solid #166534; padding: 2px 8px; border-radius: 99px;">LIVE</span>
                </h3>
                
                <div class="shoutbox-messages" id="shoutContainer">
                    <?php foreach ($recentShouts as $s): ?>
                        <div class="shout-item">
                            <img src="<?= htmlspecialchars($s['avatar_url']) ?>" class="avatar-sm" style="width: 28px; height: 28px;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="shout-user"><?= htmlspecialchars($s['username']) ?></span>
                                    <span class="shout-time"><?= date('H:i', strtotime($s['created_at'])) ?></span>
                                </div>
                                <div style="color: var(--text-main); font-size: 0.85rem; margin-top: 2px;"><?= htmlspecialchars($s['message']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (isset($user) && $user): ?>
                    <form id="shoutForm" class="shout-input-box">
                        <input type="text" id="shoutInput" class="input-field" placeholder="Schreibe eine kurze Nachricht..." required style="padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 0.9rem; font-size: 0.85rem;">&rarr;</button>
                    </form>
                <?php else: ?>
                    <div style="text-align: center; font-size: 0.85rem; color: var(--text-muted); background: #0f172a; padding: 0.75rem; border-radius: var(--radius-sm);">
                        <a href="/login">Melde dich an</a>, um im Live-Chat mitzuschreiben!
                    </div>
                <?php endif; ?>
            </div>

            <!-- Twins on Ice Profile Widget -->
            <div class="sidebar-widget glass-card">
                <h3 class="widget-title">Twins on Ice Info</h3>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #0284c7; color: #fff; display: flex; align-items: center; justify-content: center;">
                        <?= Icon::render('skate') ?>
                    </div>
                    <div>
                        <h4 style="color: #fff; font-size: 1.05rem;">Emilia & Letizia Macula</h4>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Figure Skaters & Influencers</span>
                    </div>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem; line-height: 1.5;">
                    Folge den Twins on Ice auf all ihren Kanaelen fuer taegliche Eislauf-Einblicke, Vlogs & Musik!
                </p>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="https://twinsonice.link" target="_blank" class="btn btn-secondary" style="font-size: 0.85rem; justify-content: flex-start;">
                        <?= Icon::render('external') ?> Official Linktree (twinsonice.link)
                    </a>
                    <a href="https://twinsonice.shop" target="_blank" class="btn btn-secondary" style="font-size: 0.85rem; justify-content: flex-start;">
                        <?= Icon::render('external') ?> Merch Shop (twinsonice.shop)
                    </a>
                </div>
            </div>
        </aside>

    </div>
</main>

<?php require __DIR__ . '/layout/footer.php'; ?>
