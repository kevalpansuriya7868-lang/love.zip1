<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Photo Moderation</h2>
        <p class="text-secondary small mb-0">Visual gallery of all uploaded photos across couples.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="<?= url('admin/photos'); ?>" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search filename or caption..." value="<?= e($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="couple_id" class="form-select">
                    <option value="">All Couples</option>
                    <?php foreach ($couples as $c): ?>
                        <option value="<?= $c['id']; ?>" <?= $coupleId == $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-grow-1">Filter</button>
                <a href="<?= url('admin/photos'); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<?php if (empty($photos)): ?>
    <div class="card border-0 shadow-sm p-5 text-center text-muted">
        <i class="fas fa-images fs-1 text-secondary mb-2"></i>
        <h5>No photos found.</h5>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($photos as $p): ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                    <img src="<?= upload_url($p['file_path']); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <div class="card-body p-3">
                        <div class="fw-bold small text-danger"><?= e($p['couple_name']); ?></div>
                        <div class="small text-muted mb-2">Uploaded by <?= e($p['uploader_name']); ?> &bull; <?= time_ago($p['uploaded_at']); ?></div>
                        <?php if ($p['caption']): ?>
                            <p class="small text-dark text-truncate mb-2"><?= e($p['caption']); ?></p>
                        <?php endif; ?>
                        
                        <form action="<?= url('admin/photos/delete'); ?>" method="POST" onsubmit="return confirm('Delete this photo permanently?');">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?= $p['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="fas fa-trash-alt me-1"></i> Delete Photo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
