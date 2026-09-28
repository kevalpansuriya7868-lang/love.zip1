<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Edit Couple: <?= e($couple['name']); ?></h2>
            <a href="<?= url('admin/couples'); ?>" class="btn btn-outline-secondary">Back to Couples</a>
        </div>

        <form action="<?= url('admin/couples/edit?id=' . $couple['id']); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-danger">Couple Space Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Couple Name</label>
                            <input type="text" name="couple_name" class="form-control" value="<?= e($couple['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Relationship Start Date</label>
                            <input type="date" name="relationship_start_date" class="form-control" value="<?= e($couple['relationship_start_date']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Couple Cover Photo</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= $couple['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="disabled" <?= $couple['status'] === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Bio / Motto</label>
                            <textarea name="bio" class="form-control" rows="2"><?= e($couple['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partners Section -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 border-0 fw-bold text-primary">
                            Partner 1: <?= e($p1['name'] ?? 'Partner 1'); ?>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Name *</label>
                                <input type="text" name="p1_name" class="form-control" value="<?= e($p1['name'] ?? ''); ?>" placeholder="Partner 1 Name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Email / Username *</label>
                                <input type="text" name="p1_email" class="form-control" value="<?= e($p1['email'] ?? 'user1'); ?>" placeholder="user1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Reset Password (Optional)</label>
                                <input type="password" name="p1_password" class="form-control" placeholder="Leave empty to keep current">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 border-0 fw-bold text-success">
                            Partner 2: <?= e($p2['name'] ?? 'Partner 2'); ?>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Name *</label>
                                <input type="text" name="p2_name" class="form-control" value="<?= e($p2['name'] ?? ''); ?>" placeholder="Partner 2 Name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Email / Username *</label>
                                <input type="text" name="p2_email" class="form-control" value="<?= e($p2['email'] ?? 'user2'); ?>" placeholder="user2" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Reset Password (Optional)</label>
                                <input type="password" name="p2_password" class="form-control" placeholder="Leave empty to keep current">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-danger btn-lg rounded-3 w-100 fw-bold">Update Couple Info & Credentials</button>
        </form>
    </div>
</div>
