<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="brand-font fw-bold mb-1" style="color: var(--burgundy-accent);">Our Love Timeline</h2>
        <p class="text-muted small mb-0">Every milestone, first time, anniversary, and special journey together.</p>
    </div>
    <button class="btn btn-romantic" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="fas fa-plus me-1"></i> Add Milestone
    </button>
</div>

<?php if (empty($events)): ?>
    <div class="card-romantic">
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-stream"></i></div>
            <h4>Your timeline is waiting.</h4>
            <p class="small text-muted mb-4">Add your first meeting, first date, or anniversary to begin your timeline.</p>
            <button class="btn btn-romantic" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="fas fa-plus me-2"></i> Add First Milestone
            </button>
        </div>
    </div>
<?php else: ?>
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="timeline">
                <?php foreach ($events as $event): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot">
                            <i class="fas <?= e($event['icon'] ?: 'fa-heart'); ?>"></i>
                        </div>
                        <div class="card-romantic p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1 mb-1">
                                        <?= date('F j, Y', strtotime($event['event_date'])); ?>
                                    </span>
                                    <h4 class="brand-font fw-bold mb-1 mt-1"><?= e($event['title']); ?></h4>
                                </div>
                            </div>
                            
                            <?php if (!empty($event['description'])): ?>
                                <p class="text-muted small mb-3"><?= nl2br(e($event['description'])); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($event['image'])): ?>
                                <img src="<?= upload_url($event['image']); ?>" class="img-fluid rounded-3 mt-2" style="max-height: 280px; object-fit: cover; cursor: pointer;" onclick="openLightbox('<?= upload_url($event['image']); ?>', '<?= e($event['title']); ?>')">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Add Milestone Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold brand-font">Add Milestone Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('timeline/store'); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Event Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. We first met" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Event Date *</label>
                            <input type="date" name="event_date" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Milestone Icon</label>
                            <select name="icon" class="form-select">
                                <option value="fa-heart">❤️ Heart (Met / Love)</option>
                                <option value="fa-coffee">☕ Coffee (First Date)</option>
                                <option value="fa-plane">✈️ Trip / Flight</option>
                                <option value="fa-ring">💍 Engagement / Anniversary</option>
                                <option value="fa-home">🏠 Moved Together</option>
                                <option value="fa-star">⭐ Special Moment</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Details about this milestone..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Milestone Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-romantic rounded-pill px-4">Add to Timeline</button>
                </div>
            </form>
        </div>
    </div>
</div>
