<?php
use App\Icon;

$pageTitle = 'Neues Thema erstellen – Twins on Ice Forum';
require __DIR__ . '/layout/header.php';
?>

<div class="container" style="padding-top: 2.5rem; padding-bottom: 4rem; max-width: 850px;">
    
    <div class="glass-card" style="padding: 2.5rem;">
        <h1 style="font-size: 1.8rem; color: #fff; margin-bottom: 0.5rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
            <?= Icon::render('plus') ?> Neues Thema erstellen
        </h1>
        <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 0.95rem;">
            Teile deine Gedanken, Fragen zu Eiskunstlauf, Feedback zur Single "CHECK DAS" oder erstelle einen neuen Thread in der Community.
        </p>

        <?php if (isset($error) && $error): ?>
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 0.85rem 1.2rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.9rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/topic/create" method="POST">
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: var(--text-main); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                    Kategorie auswaehlen *
                </label>
                <select name="category_id" class="input-field" required style="cursor: pointer;">
                    <option value="">-- Bitte Kategorie waehlen --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: var(--text-main); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                    Titel des Themas *
                </label>
                <input type="text" name="title" class="input-field" placeholder="Aussagekraeftiger Titel..." required max="200">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; color: var(--text-main); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                    Inhalt / Beschreibung *
                </label>
                <textarea name="content" class="input-field" rows="8" placeholder="Schreibe hier deinen Beitrag..." required></textarea>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Thema veroeffentlichen</button>
                <a href="/" class="btn btn-secondary">Abbrechen</a>
            </div>

        </form>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
