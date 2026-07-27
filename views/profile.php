<?php
use App\Icon;

$pageTitle = htmlspecialchars($profileUser['username']) . ' – Benutzerprofil';
require __DIR__ . '/layout/header.php';
$isOwnProfile = ($currentUser && $currentUser['id'] == $profileUser['id']);
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">

    <!-- User Header Card -->
    <div class="glass-card" style="padding: 2.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1.75rem;">
            <img src="<?= htmlspecialchars($profileUser['avatar_url']) ?>" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-cyan);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                    <h1 style="font-size: 2.2rem; color: #fff; font-weight: 800;"><?= htmlspecialchars($profileUser['username']) ?></h1>
                    <span class="badge badge-cyan"><?= htmlspecialchars($profileUser['rank_badge']) ?></span>
                    <?php if ($profileUser['role'] === 'admin'): ?>
                        <span class="badge badge-amber">Admin</span>
                    <?php endif; ?>
                </div>

                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.75rem;">
                    <?= !empty($profileUser['bio']) ? htmlspecialchars($profileUser['bio']) : 'Keine Bio angegeben.' ?>
                </p>

                <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: var(--text-dim);">
                    <span>Mitglied seit <?= date('M Y', strtotime($profileUser['created_at'])) ?></span>
                    <span><?= $postCount ?> Antworten verfasst</span>
                    <span><?= count($userTopics) ?> Themen gestartet</span>
                </div>
            </div>
        </div>

        <?php if ($isOwnProfile): ?>
            <div>
                <button onclick="document.getElementById('editProfileModal').style.display='flex'" class="btn btn-secondary">
                    <?= Icon::render('edit') ?> Profil bearbeiten
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- User Topics List -->
    <h2 style="font-size: 1.4rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <?= Icon::render('pin') ?> Erstellte Themen von <?= htmlspecialchars($profileUser['username']) ?>
    </h2>

    <div class="glass-card" style="overflow: hidden;">
        <?php if (empty($userTopics)): ?>
            <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                Dieser Benutzer hat bisher noch keine Themen verfasst.
            </div>
        <?php else: ?>
            <?php foreach ($userTopics as $topic): ?>
                <div class="topic-item">
                    <div class="topic-left">
                        <div class="topic-details">
                            <a href="/topic/<?= $topic['id'] ?>">
                                <h4><?= htmlspecialchars($topic['title']) ?></h4>
                            </a>
                            <div class="topic-meta-tags">
                                <span class="badge badge-<?= $topic['badge_color'] ?>"><?= Icon::render($topic['category_icon']) ?> <?= htmlspecialchars($topic['category_name']) ?></span>
                                <span>• Erstellt am <?= date('d.m.Y H:i', strtotime($topic['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div style="text-align: center;">
                            <div style="font-weight: 700; color: #fff;"><?= $topic['replies_count'] ?></div>
                            <div style="font-size: 0.7rem; color: var(--text-dim);">Antworten</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<!-- Edit Profile Modal -->
<?php if ($isOwnProfile): ?>
    <div class="modal-overlay" id="editProfileModal">
        <div class="modal-box" style="max-width: 500px;">
            <div class="modal-header">
                <h3 style="color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                    <?= Icon::render('edit') ?> Profil bearbeiten
                </h3>
                <button onclick="document.getElementById('editProfileModal').style.display='none'" style="background: none; border: none; color: #fff; cursor: pointer;">
                    <?= Icon::render('close') ?>
                </button>
            </div>
            <div style="padding: 1.5rem;">
                <form action="/user/update" method="POST">
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Avatar Bild-URL</label>
                        <input type="url" name="avatar_url" class="input-field" value="<?= htmlspecialchars($profileUser['avatar_url']) ?>" required>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Eis-Rang Badge Text</label>
                        <input type="text" name="rank_badge" class="input-field" value="<?= htmlspecialchars($profileUser['rank_badge']) ?>">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Biografie / Status</label>
                        <textarea name="bio" class="input-field" rows="3"><?= htmlspecialchars($profileUser['bio']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Aenderungen speichern</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/layout/footer.php'; ?>
