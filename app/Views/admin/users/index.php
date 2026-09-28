<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">User Management</h2>
        <p class="text-secondary small mb-0">View all partner accounts, reset passwords, or manage access.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="<?= url('admin/users'); ?>" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search user by name or email..." value="<?= e($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="couple_id" class="form-select">
                    <option value="">Filter by Couple Space</option>
                    <?php foreach ($couples as $c): ?>
                        <option value="<?= $c['id']; ?>" <?= $coupleId == $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-grow-1"><i class="fas fa-search me-1"></i> Filter</button>
                <a href="<?= url('admin/users'); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-admin mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Couple Space</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Seen</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No users found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= e($u['name']); ?></div>
                                <div class="small text-muted"><?= e($u['email']); ?></div>
                            </td>
                            <td class="fw-semibold text-danger"><?= e($u['couple_name']); ?></td>
                            <td><span class="badge bg-light text-dark border"><?= e($u['role']); ?></span></td>
                            <td>
                                <span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger'; ?> rounded-pill">
                                    <?= ucfirst($u['status']); ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= $u['last_seen'] ? time_ago($u['last_seen']) : 'Never'; ?></td>
                            <td class="text-end">
                                <form action="<?= url('admin/users/toggle'); ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                                    <input type="hidden" name="id" value="<?= $u['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-<?= $u['status'] === 'active' ? 'warning' : 'success'; ?>">
                                        <?= $u['status'] === 'active' ? 'Disable' : 'Enable'; ?>
                                    </button>
                                </form>

                                <button class="btn btn-sm btn-outline-secondary ms-1" onclick="openResetModal(<?= $u['id']; ?>, '<?= e(addslashes($u['name'])); ?>')">
                                    Reset Password
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Password Reset Modal -->
<div class="modal fade" id="adminResetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Reset Password for <span id="resetUserName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('admin/users/reset-password'); ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                <input type="hidden" name="id" id="resetUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New Password *</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResetModal(id, name) {
    document.getElementById('resetUserId').value = id;
    document.getElementById('resetUserName').innerText = name;
    new bootstrap.Modal(document.getElementById('adminResetModal')).show();
}
</script>
