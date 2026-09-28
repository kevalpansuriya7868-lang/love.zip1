<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">System Activity Logs</h2>
        <p class="text-secondary small mb-0">Audit log of administrative and security events across the system.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-admin mb-0">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Action</th>
                    <th>Actor (Admin / User)</th>
                    <th>Couple Space</th>
                    <th>IP Address</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No activity logs recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td class="small text-muted"><?= date('M j, Y h:i:s A', strtotime($l['created_at'])); ?></td>
                            <td><span class="badge bg-secondary"><?= e($l['action']); ?></span></td>
                            <td class="fw-semibold">
                                <?php if ($l['admin_name']): ?>
                                    <span class="text-danger"><i class="fas fa-user-shield me-1"></i> <?= e($l['admin_name']); ?></span>
                                <?php elseif ($l['user_name']): ?>
                                    <span class="text-primary"><i class="fas fa-user me-1"></i> <?= e($l['user_name']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">System</span>
                                <?php endif; ?>
                            </td>
                            <td class="small"><?= e($l['couple_name'] ?: 'N/A'); ?></td>
                            <td><code><?= e($l['ip_address']); ?></code></td>
                            <td class="small"><?= e($l['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
