<?php
use App\Icon;
?>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div class="brand-icon" style="width: 36px; height: 36px; font-size: 1.2rem;">{!! Icon::render('skate') !!}</div>
                        <span style="font-size: 1.2rem; font-weight: 800; color: #fff;">Twins <span class="brand-accent">on Ice</span></span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; max-width: 420px; margin-bottom: 1rem;">
                        Offizielle Fansite & Community Plattform rund um Emilia & Letizia Macula (Twins on Ice). Eiskunstlauf, Musik, "CHECK DAS", Vlogs & Fashion.
                    </p>
                    <div style="display: flex; gap: 1rem; color: var(--text-muted);">
                        <a href="https://youtube.com" target="_blank" title="YouTube" style="display: flex; align-items: center; gap: 4px;">{!! Icon::render('video') !!} YouTube</a>
                        <a href="https://tiktok.com" target="_blank" title="TikTok" style="display: flex; align-items: center; gap: 4px;">{!! Icon::render('music') !!} TikTok</a>
                        <a href="https://twinsonice.link" target="_blank" title="Link Tree" style="display: flex; align-items: center; gap: 4px;">{!! Icon::render('external') !!} Linktree</a>
                    </div>
                </div>

                <div>
                    <h4 style="color: #fff; margin-bottom: 1rem; font-weight: 700;">Navigation</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                        <li><a href="/">Forum Uebersicht</a></li>
                        <li><a href="/category/eiskunstlauf-training">Eiskunstlauf & Training</a></li>
                        <li><a href="/category/musik-check-das">Musik & "CHECK DAS"</a></li>
                        <li><a href="/category/vlogs-social-media">Vlogs & Social Media</a></li>
                        <li><a href="/category/events-meet-greets">Meet & Greets & Events</a></li>
                    </ul>
                </div>

                <div>
                    <h4 style="color: #fff; margin-bottom: 1rem; font-weight: 700;">Community Links</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                        <li><a href="/register">Mitglied werden</a></li>
                        <li><a href="/topic/create">Neues Thema starten</a></li>
                        <li><a href="https://twinsonice.shop" target="_blank">Fan-Shop</a></li>
                        <li><a href="https://twinsoniceforum.dnbx.de">Subdomain Hub</a></li>
                    </ul>
                </div>
            </div>

            <div style="border-top: 1px solid #1e293b; padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: var(--text-dim);">
                <span>&copy; {{ date('Y') }} Twins on Ice Community Forum. Alle Rechte vorbehalten.</span>
                <span>Entwickelt fuer twinsoniceforum.de & twinsoniceforum.dnbx.de</span>
            </div>
        </div>
    </footer>

    <script src="/assets/js/app.js"></script>
</body>
</html>
