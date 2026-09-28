<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Chat History Monitor</h2>
        <p class="text-secondary small mb-0">Audit conversations, moderation, and message management.</p>
    </div>
</div>

<div class="alert alert-warning rounded-3 border-0 shadow-sm mb-4">
    <i class="fas fa-shield-alt me-2 text-warning fs-5"></i>
    <strong>Private Couple Data Notice:</strong> You are accessing private chat records. Use this interface exclusively for security verification, safety compliance, and moderation.
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="<?= url('admin/messages'); ?>" method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Select Couple Space *</label>
                <select name="couple_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($couples as $c): ?>
                        <option value="<?= $c['id']; ?>" <?= ($selectedCouple['id'] ?? 0) == $c['id'] ? 'selected' : ''; ?>>
                            <?= e($c['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Search Keyword</label>
                <input type="text" name="search" class="form-control" placeholder="Search message text..." value="<?= e($search); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary">Date From</label>
                <input type="date" name="date_from" class="form-control" value="<?= e($dateFrom ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary">Date To</label>
                <input type="date" name="date_to" class="form-control" value="<?= e($dateTo ?? ''); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-1">
                <button type="submit" class="btn btn-secondary flex-grow-1">Filter</button>
                <a href="<?= url('admin/messages?couple_id=' . ($selectedCouple['id'] ?? '')); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Messages Stream / Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-0 fw-bold fs-5">
        Messages Stream: <?= e($selectedCouple['name'] ?? 'N/A'); ?>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-admin mb-0">
            <thead>
                <tr>
                    <th>Sender</th>
                    <th>Message Content</th>
                    <th>Sent Time</th>
                    <th class="text-end">Moderation</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No messages found for this query.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($messages as $m): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= e($m['sender_name']); ?></div>
                                <div class="small text-muted"><?= e($m['sender_email']); ?></div>
                            </td>
                            <td>
                                <?php if ($m['message_type'] === 'image'): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= upload_url($m['message']); ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        <span class="small text-muted">[Photo Attachment]</span>
                                    </div>
                                <?php else: ?>
                                    <span class="<?= $m['deleted_at'] ? 'text-muted fst-italic' : ''; ?>"><?= e($m['message']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= date('M j, Y h:i A', strtotime($m['created_at'])); ?></td>
                            <td class="text-end">
                                <?php if (empty($m['deleted_at'])): ?>
                                    <form action="<?= url('admin/messages/delete'); ?>" method="POST" class="d-inline" onsubmit="return confirm('Remove message content?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                                        <input type="hidden" name="id" value="<?= $m['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt me-1"></i> Remove Content
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Deleted</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
