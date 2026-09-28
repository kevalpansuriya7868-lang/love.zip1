<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="brand-font fw-bold mb-1" style="color: var(--burgundy-accent);">Our Digital Scrapbook</h2>
        <p class="text-muted small mb-0">Cherished memories, trips, dates, and milestone moments.</p>
    </div>
    <a href="<?= url('memories/create'); ?>" class="btn btn-romantic">
        <i class="fas fa-plus me-1"></i> Add Shared Memory
    </a>
</div>

<?php if (empty($memories)): ?>
    <div class="card-romantic">
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-book-heart"></i></div>
            <h4>Nothing here yet.</h4>
            <p class="small text-muted mb-4">Add your first shared memory (first date, first trip, anniversary) to your scrapbook.</p>
            <a href="<?= url('memories/create'); ?>" class="btn btn-romantic">
                <i class="fas fa-plus me-2"></i> Create Memory
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($memories as $mem): ?>
            <div class="col-md-6 col-lg-4" id="memory-card-<?= $mem['id']; ?>">
                <div class="card-romantic h-100 p-0 overflow-hidden d-flex flex-column">
                    <?php if (!empty($mem['cover_photo'])): ?>
                        <img src="<?= upload_url($mem['cover_photo']); ?>" class="w-100" style="height: 200px; object-fit: cover; cursor: pointer;" onclick="openLightbox('<?= upload_url($mem['cover_photo']); ?>', '<?= e($mem['title']); ?>')">
                    <?php endif; ?>
                    
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="brand-font fw-bold mb-0 text-dark"><?= e($mem['title']); ?></h5>
                            <button class="btn btn-link text-danger p-0 ms-2" onclick="deleteMemory(<?= $mem['id']; ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>

                        <div class="small text-muted mb-2">
                            <i class="fas fa-calendar-alt text-danger me-1"></i> <?= date('M j, Y', strtotime($mem['memory_date'])); ?>
                            <?php if (!empty($mem['location'])): ?>
                                &bull; <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($mem['location']); ?>
                            <?php endif; ?>
                        </div>

                        <p class="small text-secondary flex-grow-1 mb-3" style="line-height: 1.6;"><?= nl2br(e($mem['description'])); ?></p>

                        <?php if (!empty($mem['photos'])): ?>
                            <div class="d-flex gap-2 overflow-x-auto pt-2 border-top">
                                <?php foreach ($mem['photos'] as $p): ?>
                                    <img src="<?= upload_url($p['file_path']); ?>" class="rounded-2" style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;" onclick="openLightbox('<?= upload_url($p['file_path']); ?>')">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
async function deleteMemory(id) {
    if (!confirm('Are you sure you want to delete this memory?')) return;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrfToken);

    try {
        const res = await fetch(window.APP_URL + '/api/memories/delete', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const json = await res.json();
        if (json.success) {
            document.getElementById(`memory-card-${id}`).remove();
        } else {
            alert(json.message || 'Failed to delete memory.');
        }
    } catch (e) {
        alert('Failed to delete memory.');
    }
}
</script>
