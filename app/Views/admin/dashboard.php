<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">System Overview</h2>
        <p class="text-secondary small mb-0">Platform performance & management dashboard.</p>
    </div>
    <a href="<?= url('admin/couples/create'); ?>" class="btn btn-danger rounded-3">
        <i class="fas fa-plus me-1"></i> Create New Couple
    </a>
</div>

<!-- Stat Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-semibold">Total Couples</span>
                <i class="fas fa-heart text-danger"></i>
            </div>
            <div class="stat-card-number"><?= number_format($stats['total_couples']); ?></div>
            <div class="small text-success mt-1"><i class="fas fa-check-circle me-1"></i> <?= $stats['active_couples']; ?> Active</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-semibold">Total Users</span>
                <i class="fas fa-users text-primary"></i>
            </div>
            <div class="stat-card-number"><?= number_format($stats['total_users']); ?></div>
            <div class="small text-secondary mt-1">2 users per couple</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-semibold">Total Messages</span>
                <i class="fas fa-comments text-info"></i>
            </div>
            <div class="stat-card-number"><?= number_format($stats['total_messages']); ?></div>
            <div class="small text-muted mt-1"><?= $stats['messages_today']; ?> today</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-secondary small fw-semibold">Photos & Memories</span>
                <i class="fas fa-images text-warning"></i>
            </div>
            <div class="stat-card-number"><?= number_format($stats['total_photos'] + $stats['total_memories']); ?></div>
            <div class="small text-secondary mt-1"><?= $stats['total_photos']; ?> photos &bull; <?= $stats['total_memories']; ?> memories</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Couples -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h5 class="fw-bold mb-0">Recent Couples</h5>
                <a href="<?= url('admin/couples'); ?>" class="small text-danger text-decoration-none">View All &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Couple</th>
                            <th>Partner 1</th>
                            <th>Partner 2</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentCouples as $c): ?>
                            <tr>
                                <td class="fw-bold"><?= e($c['name']); ?></td>
                                <td><?= e($c['partner_1']['name'] ?? 'N/A'); ?></td>
                                <td><?= e($c['partner_2']['name'] ?? 'N/A'); ?></td>
                                <td><?= date('M j, Y', strtotime($c['created_at'])); ?></td>
                                <td>
                                    <span class="badge bg-<?= $c['status'] === 'active' ? 'success' : 'danger'; ?> rounded-pill">
                                        <?= ucfirst($c['status']); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= url('admin/couples/edit?id=' . $c['id']); ?>" class="btn btn-sm btn-outline-secondary rounded-circle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Activity Log Preview -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h5 class="fw-bold mb-0">Recent Activity</h5>
                <a href="<?= url('admin/activity'); ?>" class="small text-danger text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($recentLogs as $log): ?>
                        <li class="list-group-item p-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span><i class="fas fa-history text-danger me-1"></i> <?= e($log['action']); ?></span>
                                <span><?= time_ago($log['created_at']); ?></span>
                            </div>
                            <div class="small fw-semibold text-dark"><?= e($log['description']); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
