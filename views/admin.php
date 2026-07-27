<?php
$pageTitle = 'Admin Dashboard – Twins on Ice Forum';
require __DIR__ . '/layout/header.php';
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2.2rem; color: #fff; font-weight: 800;">⚙️ Admin Control Panel</h1>
            <p style="color: var(--text-muted);">Verwalte Kategorien, Benutzer & Forum Einstellungen</p>
        </div>
        <span class="badge badge-amber" style="font-size: 0.9rem; padding: 0.4rem 1rem;">Administrator</span>
    </div>

    <!-- Overview Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--primary-cyan);"><?= $usersCount ?></div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">Registrierte Mitglieder</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--accent-magenta);"><?= $topicsCount ?></div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">Forum Themen</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--accent-purple);"><?= $postsCount ?></div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">Beiträge & Antworten</div>
        </div>
    </div>

    <!-- Admin Actions Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- Add Category Form -->
        <div class="glass-card" style="padding: 2rem;">
            <h2 style="font-size: 1.3rem; color: #fff; margin-bottom: 1.25rem;">➕ Neue Kategorie erstellen</h2>
            
            <form action="/admin/category/create" method="POST">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Kategorie Name</label>
                    <input type="text" name="name" class="input-field" placeholder="z.B. Fan Art & Design" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">URL Slug</label>
                    <input type="text" name="slug" class="input-field" placeholder="z.B. fan-art-design" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Icon Emoji</label>
                    <input type="text" name="icon" class="input-field" value="🎨" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Badge Farbe</label>
                    <select name="badge_color" class="input-field">
                        <option value="cyan">Cyan</option>
                        <option value="magenta">Magenta</option>
                        <option value="purple">Purple</option>
                        <option value="amber">Amber</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Beschreibung</label>
                    <textarea name="description" class="input-field" rows="2" placeholder="Kurze Beschreibung..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Kategorie anlegen</button>
            </form>
        </div>

        <!-- Registered Users List -->
        <div class="glass-card" style="padding: 2rem;">
            <h2 style="font-size: 1.3rem; color: #fff; margin-bottom: 1.25rem;">👥 Neueste Mitglieder</h2>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
                <?php foreach ($recentUsers as $u): ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.8rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="<?= htmlspecialchars($u['avatar_url']) ?>" class="avatar-sm" style="width: 32px; height: 32px;">
                            <div>
                                <div style="font-weight: 700; color: #fff; font-size: 0.9rem;"><?= htmlspecialchars($u['username']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-dim);"><?= htmlspecialchars($u['email']) ?></div>
                            </div>
                        </div>
                        <span class="badge badge-<?= $u['role'] === 'admin' ? 'amber' : 'cyan' ?>" style="font-size: 0.7rem;">
                            <?= $u['role'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
