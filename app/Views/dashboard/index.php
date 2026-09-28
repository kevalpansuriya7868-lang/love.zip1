<div class="row mb-4">
    <div class="col-12">
        <div class="card-romantic p-4 p-md-5 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #FFFFFF 0%, #FAF2F3 100%);">
            <div class="position-relative z-1">
                <span class="duration-badge mb-3">
                    <i class="fas fa-heart text-danger"></i> Together for <?= e($stats['duration']); ?>
                </span>
                
                <h1 class="brand-font display-6 fw-bold mb-2" style="color: var(--burgundy-accent);">
                    Welcome back, Dharth ❤️
                </h1>
                
                <p class="text-muted mb-4 max-w-600 mx-auto fs-6">
                    <?= e($couple['bio'] ?: 'Your private digital home for two — conversations, photographs, memories, and every moment worth keeping.'); ?>
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="<?= url('chat'); ?>" class="btn btn-romantic">
                        <i class="fas fa-comments me-2"></i> Open Private Chat
                    </a>
                    <a href="<?= url('photos'); ?>" class="btn btn-romantic-outline">
                        <i class="fas fa-camera me-2"></i> Upload Moment
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stat Highlights -->
    <div class="col-6 col-md-3">
        <div class="card-romantic text-center p-3 h-100">
            <div class="fs-2 text-danger mb-1"><i class="fas fa-comments"></i></div>
            <div class="fs-3 fw-bold"><?= number_format($stats['total_messages']); ?></div>
            <div class="text-muted small">Messages</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-romantic text-center p-3 h-100">
            <div class="fs-2 text-danger mb-1"><i class="fas fa-images"></i></div>
            <div class="fs-3 fw-bold"><?= number_format($stats['total_photos']); ?></div>
            <div class="text-muted small">Photos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-romantic text-center p-3 h-100">
            <div class="fs-2 text-danger mb-1"><i class="fas fa-book-heart"></i></div>
            <div class="fs-3 fw-bold"><?= number_format($stats['total_memories']); ?></div>
            <div class="text-muted small">Memories</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-romantic text-center p-3 h-100">
            <div class="fs-2 text-danger mb-1"><i class="fas fa-calendar-alt"></i></div>
            <div class="fs-6 fw-bold mt-2"><?= date('M j, Y', strtotime($couple['relationship_start_date'])); ?></div>
            <div class="text-muted small">Anniversary</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Recent Photos & Memories -->
    <div class="col-lg-8">
        <div class="card-romantic mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="brand-font mb-0"><i class="fas fa-images text-danger me-2"></i> Shared Moments</h4>
                <a href="<?= url('photos'); ?>" class="small text-decoration-none" style="color: var(--burgundy-accent);">View All Gallery &rarr;</a>
            </div>

            <?php if (!empty($recentPhotos)): ?>
                <div class="row g-3">
                    <?php foreach ($recentPhotos as $photo): ?>
                        <div class="col-4 col-md-4">
                            <div class="gallery-card overflow-hidden rounded-3">
                                <img src="<?= upload_url($photo['file_path']); ?>" class="img-fluid w-100" style="height: 140px; object-fit: cover; cursor: pointer;" onclick="openLightbox('<?= upload_url($photo['file_path']); ?>', '<?= e($photo['caption']); ?>')">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-camera"></i></div>
                    <h5>No photos yet</h5>
                    <p class="small mb-3">Capture a moment you'll want to remember together.</p>
                    <a href="<?= url('photos'); ?>" class="btn btn-sm btn-romantic">Upload First Photo</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Memories -->
        <div class="card-romantic">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="brand-font mb-0"><i class="fas fa-book-heart text-danger me-2"></i> Scrapbook Memories</h4>
                <a href="<?= url('memories/create'); ?>" class="btn btn-sm btn-romantic">+ New Memory</a>
            </div>

            <?php if (!empty($memories)): ?>
                <div class="row g-3">
                    <?php foreach ($memories as $mem): ?>
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100 bg-light">
                                <?php if ($mem['cover_photo']): ?>
                                    <img src="<?= upload_url($mem['cover_photo']); ?>" class="img-fluid rounded-3 mb-2 w-100" style="height: 130px; object-fit: cover;">
                                <?php endif; ?>
                                <h6 class="fw-bold mb-1"><?= e($mem['title']); ?></h6>
                                <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1 text-danger"></i> <?= e($mem['location'] ?: 'Special Place'); ?> &bull; <?= date('M j, Y', strtotime($mem['memory_date'])); ?></p>
                                <p class="small text-secondary mb-0 text-truncate"><?= e($mem['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-heart"></i></div>
                    <h5>Nothing here yet</h5>
                    <p class="small">Add your first shared memory to your digital scrapbook.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Recent Messages & Quick Timeline -->
    <div class="col-lg-4">
        <div class="card-romantic mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="brand-font mb-0"><i class="fas fa-comments text-danger me-2"></i> Recent Chat</h5>
                <a href="<?= url('chat'); ?>" class="small text-decoration-none" style="color: var(--burgundy-accent);">Open Chat</a>
            </div>

            <?php if (!empty($recentMessages)): ?>
                <div class="d-flex flex-column gap-2 mb-3">
                    <?php foreach ($recentMessages as $msg): ?>
                        <div class="p-2.5 rounded-3 bg-light border small">
                            <div class="d-flex justify-content-between text-muted fs-7 mb-1">
                                <strong><?= e($msg['sender_name']); ?></strong>
                                <span><?= time_ago($msg['created_at']); ?></span>
                            </div>
                            <div class="text-dark">
                                <?php if ($msg['message_type'] === 'image'): ?>
                                    <i class="fas fa-camera text-danger me-1"></i> Photo shared
                                <?php else: ?>
                                    <?= e($msg['message']); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state py-4">
                    <p class="small text-muted mb-0">Your story starts here.<br>Send your first message and make it a memory.</p>
                </div>
            <?php endif; ?>

            <a href="<?= url('chat'); ?>" class="btn btn-sm btn-romantic w-100 mt-2">Send Message &rarr;</a>
        </div>

        <!-- Timeline Quick Events -->
        <div class="card-romantic">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="brand-font mb-0"><i class="fas fa-stream text-danger me-2"></i> Timeline Highlights</h5>
                <a href="<?= url('timeline'); ?>" class="small text-decoration-none" style="color: var(--burgundy-accent);">View All</a>
            </div>

            <?php if (!empty($timelineEvents)): ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach (array_slice($timelineEvents, 0, 4) as $ev): ?>
                        <li class="mb-3 d-flex align-items-start gap-2">
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-2 fs-6">
                                <i class="fas <?= e($ev['icon'] ?: 'fa-heart'); ?>"></i>
                            </div>
                            <div>
                                <div class="fw-bold small"><?= e($ev['title']); ?></div>
                                <div class="text-muted small"><?= date('M j, Y', strtotime($ev['event_date'])); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="small text-muted text-center mb-0">No timeline milestones added yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
