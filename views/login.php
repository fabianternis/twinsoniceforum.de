<?php
use App\Icon;

$pageTitle = 'Anmelden – Twins on Ice Forum';
require __DIR__ . '/layout/header.php';
?>

<div class="container" style="padding-top: 4rem; padding-bottom: 6rem; max-width: 480px;">
    
    <div class="glass-card" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="brand-icon" style="margin: 0 auto 1rem; width: 48px; height: 48px; font-size: 1.5rem;"><?= Icon::render('skate') ?></div>
            <h1 style="font-size: 1.8rem; color: #fff; font-weight: 800;">Willkommen zurueck!</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Melde dich im Twins on Ice Forum an</p>
        </div>

        <?php if (isset($error) && $error): ?>
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 0.8rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.88rem; text-align: center;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? '/') ?>">

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    Benutzername oder E-Mail
                </label>
                <input type="text" name="username" class="input-field" placeholder="z.B. EmiliaFan" required autofocus>
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    Passwort
                </label>
                <input type="password" name="password" class="input-field" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Jetzt Anmelden</button>
        </form>

        <div style="text-align: center; margin-top: 1.75rem; font-size: 0.9rem; color: var(--text-muted);">
            Noch kein Konto? <a href="/register" style="color: var(--primary-cyan); font-weight: 600;">Jetzt kostenlos registrieren</a>
        </div>
    </div>

</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
