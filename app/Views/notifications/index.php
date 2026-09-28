<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="brand-font fw-bold mb-0" style="color: var(--burgundy-accent);">
                <i class="fas fa-bell text-danger me-2"></i> Notifications
            </h3>
            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="markAllRead()">
                <i class="fas fa-check-double me-1"></i> Mark all as read
            </button>
        </div>

        <div class="card-romantic p-0 overflow-hidden">
            <?php if (empty($notifications)): ?>
                <div class="empty-state py-5">
                    <div class="empty-state-icon"><i class="far fa-bell-slash"></i></div>
                    <h5>No notifications yet.</h5>
                    <p class="small text-muted mb-0">You'll see activity alerts here when your partner posts or sends messages.</p>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $n): ?>
                        <div class="list-group-item p-3.5 <?= $n['is_read'] ? '' : 'bg-danger bg-opacity-10'; ?>">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold mb-0 text-dark"><?= e($n['title']); ?></h6>
                                <span class="small text-muted" style="font-size: 0.75rem;"><?= time_ago($n['created_at']); ?></span>
                            </div>
                            <p class="small text-secondary mb-0"><?= e($n['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
async function markAllRead() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('csrf_token', csrfToken);

    try {
        const res = await fetch(window.APP_URL + '/api/notifications/read-all', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const json = await res.json();
        if (json.success) {
            location.reload();
        }
    } catch (e) {
        // ignore
    }
}
</script>
