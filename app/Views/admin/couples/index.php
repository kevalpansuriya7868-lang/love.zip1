<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Manage Couples</h2>
        <p class="text-secondary small mb-0">Create, edit, search, and manage private couple spaces.</p>
    </div>
    <a href="<?= url('admin/couples/create'); ?>" class="btn btn-danger rounded-3">
        <i class="fas fa-plus me-1"></i> Create Couple
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="<?= url('admin/couples'); ?>" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by couple name..." value="<?= e($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" <?= $status === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="disabled" <?= $status === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-grow-1"><i class="fas fa-search me-1"></i> Filter</button>
                <a href="<?= url('admin/couples'); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Couples Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-admin mb-0">
            <thead>
                <tr>
                    <th>Couple Name</th>
                    <th>Partner 1</th>
                    <th>Partner 2</th>
                    <th>Anniversary</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($couples)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No couples found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($couples as $c): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= e($c['name']); ?></div>
                                <div class="small text-muted"><?= url('c/' . $c['slug']); ?></div>
                            </td>
                            <td>
                                <div><?= e($c['partner_1']['name'] ?? 'N/A'); ?></div>
                                <div class="small text-muted"><?= e($c['partner_1']['email'] ?? ''); ?></div>
                            </td>
                            <td>
                                <div><?= e($c['partner_2']['name'] ?? 'N/A'); ?></div>
                                <div class="small text-muted"><?= e($c['partner_2']['email'] ?? ''); ?></div>
                            </td>
                            <td><?= date('M j, Y', strtotime($c['relationship_start_date'])); ?></td>
                            <td>
                                <span class="badge bg-<?= $c['status'] === 'active' ? 'success' : 'danger'; ?> rounded-pill">
                                    <?= ucfirst($c['status']); ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($c['created_at'])); ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="<?= url('admin/messages?couple_id=' . $c['id']); ?>" class="btn btn-sm btn-outline-info" title="View Chat History">
                                        <i class="fas fa-comments"></i>
                                    </a>
                                    <a href="<?= url('admin/couples/edit?id=' . $c['id']); ?>" class="btn btn-sm btn-outline-secondary" title="Edit Couple">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= url('admin/couples/delete'); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this couple and all their data?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                                        <input type="hidden" name="id" value="<?= $c['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Couple">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
