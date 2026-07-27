<?php
use App\Icon;

$pageTitle = htmlspecialchars($category['name']) . ' – Twins on Ice Forum';
require __DIR__ . '/layout/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Category Header Card -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="background: #1e293b; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-md); border: 1px solid #334155; color: var(--primary-cyan);">
                <?= Icon::render($category['icon']) ?>
            </div>
            <div>
                <span class="badge badge-<?= $category['badge_color'] ?>" style="margin-bottom: 0.5rem; font-size: 0.85rem;">Kategorie</span>
                <h1 style="font-size: 2rem; color: #fff;"><?= htmlspecialchars($category['name']) ?></h1>
                <p style="color: var(--text-muted); margin-top: 0.25rem; font-size: 1rem;"><?= htmlspecialchars($category['description']) ?></p>
            </div>
        </div>

        <div>
            <a href="/topic/create?category=<?= $category['id'] ?>" class="btn btn-primary"><?= Icon::render('plus') ?> Thema in dieser Kategorie</a>
        </div>
    </div>

    <!-- Topics List -->
    <div class="glass-card" style="overflow: hidden;">
        <?php if (empty($topics)): ?>
            <div style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                <h3>Noch keine Themen in dieser Kategorie.</h3>
                <p style="margin-top: 0.5rem;">Starte jetzt die erste Diskussion!</p>
                <a href="/topic/create?category=<?= $category['id'] ?>" class="btn btn-primary" style="margin-top: 1.5rem;">Erstes Thema erstellen</a>
            </div>
        <?php else: ?>
            <?php foreach ($topics as $topic): ?>
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
                                <span>von <a href="/user/<?= urlencode($topic['username']) ?>" style="color: var(--text-main); font-weight: 600;"><?= htmlspecialchars($topic['username']) ?></a></span>
                                <span>• Erstellt am <?= date('d.m.Y H:i', strtotime($topic['created_at'])) ?></span>
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

<?php require __DIR__ . '/layout/footer.php'; ?>
