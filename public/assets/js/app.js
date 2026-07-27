document.addEventListener('DOMContentLoaded', () => {
    // Search Modal Toggle
    const searchModal = document.getElementById('searchModal');
    const searchOpenBtn = document.getElementById('searchOpenBtn');
    const searchCloseBtn = document.getElementById('searchCloseBtn');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    if (searchOpenBtn && searchModal) {
        searchOpenBtn.addEventListener('click', () => {
            searchModal.classList.add('active');
            if (searchInput) searchInput.focus();
        });
    }

    if (searchCloseBtn && searchModal) {
        searchCloseBtn.addEventListener('click', () => {
            searchModal.classList.remove('active');
        });
    }

    // Keyboard shortcut Ctrl+K
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            if (searchModal) {
                searchModal.classList.toggle('active');
                if (searchModal.classList.contains('active') && searchInput) {
                    searchInput.focus();
                }
            }
        }
        if (e.key === 'Escape' && searchModal && searchModal.classList.contains('active')) {
            searchModal.classList.remove('active');
        }
    });

    // Client-side instant filter search
    if (searchInput && searchResults) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().trim();
            const topics = document.querySelectorAll('.topic-item');

            if (query === '') {
                searchResults.innerHTML = '<p style="color: var(--text-dim); text-align: center; padding: 1rem;">Tippe etwas ein, um Themen zu suchen...</p>';
                return;
            }

            let matches = 0;
            let html = '';
            topics.forEach(t => {
                const title = t.querySelector('h4')?.textContent || '';
                if (title.toLowerCase().includes(query)) {
                    matches++;
                    html += `<div style="padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">${t.innerHTML}</div>`;
                }
            });

            if (matches === 0) {
                searchResults.innerHTML = `<p style="color: var(--text-dim); text-align: center; padding: 1rem;">Keine Ergebnisse für "${query}" gefunden.</p>`;
            } else {
                searchResults.innerHTML = html;
            }
        });
    }

    // Shoutbox AJAX
    const shoutForm = document.getElementById('shoutForm');
    const shoutInput = document.getElementById('shoutInput');
    const shoutContainer = document.getElementById('shoutContainer');

    if (shoutForm && shoutInput && shoutContainer) {
        shoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = shoutInput.value.trim();
            if (!msg) return;

            try {
                const formData = new FormData();
                formData.append('message', msg);

                const res = await fetch('/api/chat', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.error) {
                    alert(data.error);
                } else {
                    shoutInput.value = '';
                    fetchShouts();
                }
            } catch (err) {
                console.error('Error posting shout:', err);
            }
        });

        // Auto Poll Shouts every 5 seconds
        setInterval(fetchShouts, 5000);
    }

    async function fetchShouts() {
        if (!shoutContainer) return;
        try {
            const res = await fetch('/api/chat');
            const data = await res.json();

            let html = '';
            data.forEach(s => {
                const dateStr = new Date(s.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                html += `
                    <div class="shout-item">
                        <img src="${s.avatar_url}" class="avatar-sm" style="width: 28px; height: 28px;">
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="shout-user">${escapeHtml(s.username)}</span>
                                <span class="shout-time">${dateStr}</span>
                            </div>
                            <div style="color: var(--text-main); font-size: 0.85rem; margin-top: 2px;">${escapeHtml(s.message)}</div>
                        </div>
                    </div>
                `;
            });
            shoutContainer.innerHTML = html;
            shoutContainer.scrollTop = shoutContainer.scrollHeight;
        } catch (err) {
            console.error('Error fetching shouts:', err);
        }
    }

    // Reaction Toggle
    window.toggleReaction = async function(topicId, type = 'heart') {
        try {
            const formData = new FormData();
            formData.append('type', type);

            const res = await fetch(`/topic/${topicId}/react`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.error) {
                alert(data.error);
                return;
            }

            if (data.success) {
                location.reload();
            }
        } catch (err) {
            console.error('Reaction failed:', err);
        }
    };

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
