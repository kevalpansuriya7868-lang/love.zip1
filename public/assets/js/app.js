// Global Client JS Application
document.addEventListener('DOMContentLoaded', () => {
    // Notification badge auto-poll
    const notifBadge = document.getElementById('notifBadge');
    if (notifBadge) {
        const checkNotifications = async () => {
            try {
                const res = await fetch(window.APP_URL + '/api/notifications/unread');
                const json = await res.json();
                if (json.success && json.unread_count > 0) {
                    notifBadge.innerText = json.unread_count;
                    notifBadge.classList.remove('d-none');
                } else {
                    notifBadge.classList.add('d-none');
                }
            } catch (err) {
                // ignore
            }
        };

        checkNotifications();
        setInterval(checkNotifications, 10000);
    }
});

// Helper for fetch API with CSRF
async function apiFetch(url, options = {}) {
    options.headers = options.headers || {};
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta) {
        options.headers['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
    }
    options.headers['X-Requested-With'] = 'XMLHttpRequest';
    return fetch(url, options);
}
