<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <?php if (isset($folder)): ?>
            <h2 class="brand-font fw-bold mb-1" style="color: var(--burgundy-accent);"><i class="fas fa-folder-open me-2"></i><?= e($folder['name']); ?></h2>
            <p class="text-muted small mb-0">
                <a href="<?= url('photos'); ?>" class="text-decoration-none" style="color: var(--burgundy-accent);"><i class="fas fa-arrow-left me-1"></i> Back to All Photos</a>
            </p>
        <?php else: ?>
            <h2 class="brand-font fw-bold mb-1" style="color: var(--burgundy-accent);">Our Shared Moments</h2>
            <p class="text-muted small mb-0">High quality private photo gallery for your memories together.</p>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= url('photos/download-all'); ?>" class="btn btn-romantic-outline">
            <i class="fas fa-download me-1"></i> Download All
        </a>
        <?php if (!isset($folder)): ?>
        <button class="btn btn-romantic-outline" data-bs-toggle="modal" data-bs-target="#createFolderModal">
            <i class="fas fa-folder-plus me-1"></i> New Folder
        </button>
        <?php endif; ?>
        <button class="btn btn-romantic" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
            <i class="fas fa-plus me-1"></i> Upload Photo
        </button>
    </div>
</div>

<?php if (!isset($folder) && !empty($folders)): ?>
    <h5 class="brand-font mb-3"><i class="fas fa-folder text-danger me-2"></i> Folders</h5>
    <div class="row g-3 mb-5">
        <?php foreach ($folders as $f): ?>
            <div class="col-6 col-md-4 col-lg-3" id="folder-card-<?= $f['id']; ?>">
                <div class="card-romantic p-3 h-100 text-center position-relative cursor-pointer transition-all hover-lift" onclick="window.location.href='<?= url('photos?folder_id=' . $f['id']); ?>'">
                    <i class="fas fa-folder fs-1 mb-2" style="color: var(--burgundy-accent);"></i>
                    <h6 class="fw-bold mb-1 text-truncate"><?= e($f['name']); ?></h6>
                    <div class="small text-muted mb-2"><?= $f['photo_count']; ?> photo<?= $f['photo_count'] != 1 ? 's' : ''; ?></div>
                    
                    <!-- Folder deletion removed for users -->
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (empty($photos)): ?>
    <div class="card-romantic">
        <div class="empty-state py-5">
            <div class="empty-state-icon"><i class="fas fa-camera"></i></div>
            <h4>No photos here yet.</h4>
            <p class="small text-muted mb-4">Capture a moment you'll want to remember and upload it.</p>
            <button class="btn btn-romantic" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                <i class="fas fa-upload me-2"></i> Upload First Photo
            </button>
        </div>
    </div>
<?php else: ?>
    <?php if (!isset($folder)): ?>
        <h5 class="brand-font mb-3"><i class="fas fa-images text-danger me-2"></i> Unfiled Photos</h5>
    <?php endif; ?>
    <div class="gallery-grid">
        <?php foreach ($photos as $photo): ?>
            <div class="gallery-card" id="photo-card-<?= $photo['id']; ?>">
                <?php 
                $ext = strtolower(pathinfo($photo['file_path'], PATHINFO_EXTENSION));
                $isVideo = in_array($ext, ['mp4', 'mov', 'webm']);
                if ($isVideo): 
                ?>
                    <video src="<?= upload_url($photo['file_path']); ?>#t=0.1" preload="metadata" style="object-fit: cover; width: 100%; height: 250px; cursor: pointer;" onclick="window.open('<?= upload_url($photo['file_path']); ?>', '_blank')"></video>
                    <div class="position-absolute top-50 start-50 translate-middle pointer-events-none">
                        <i class="fas fa-play-circle text-white fs-1" style="opacity: 0.8; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));"></i>
                    </div>
                <?php else: ?>
                    <img src="<?= upload_url($photo['file_path']); ?>" loading="lazy" alt="Moment" onclick="openLightbox('<?= upload_url($photo['file_path']); ?>', '<?= e($photo['caption']); ?>')">
                <?php endif; ?>
                <div class="gallery-card-info d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold text-truncate" style="max-width: 180px;"><?= e($photo['caption'] ?: 'Shared Moment'); ?></div>
                        <div class="small text-muted" style="font-size: 0.75rem;"><?= time_ago($photo['uploaded_at']); ?> &bull; by <?= e($photo['uploader_name']); ?></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="<?= upload_url($photo['file_path']); ?>" download class="btn btn-link btn-sm text-primary p-0 ms-2" title="Download">
                            <i class="fas fa-download" style="color: #bc36f6;"></i>
                        </a>
                        <!-- Photo deletion removed for users -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Upload Photo Modal -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold brand-font">Upload Couple Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="photoUploadForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Select Image File</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                        <div class="form-text small">Allowed formats: JPG, PNG, WEBP. Max size 10MB.</div>
                    </div>
                    
                    <?php if (!empty($folders) || isset($folder)): ?>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Select Folder (Optional)</label>
                            <select name="folder_id" class="form-select">
                                <option value="">-- No Folder (Unfiled) --</option>
                                <?php 
                                // Make folders available even inside a folder view
                                $uploadFolders = $folders ?? (isset($folder) ? (new PhotoFolder())->getCoupleFolders($_SESSION['couple']['id']) : []);
                                foreach ($uploadFolders as $f): 
                                ?>
                                    <option value="<?= $f['id']; ?>" <?= (isset($folder) && $folder['id'] == $f['id']) ? 'selected' : ''; ?>><?= e($f['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Caption / Story (Optional)</label>
                        <textarea name="caption" class="form-control" rows="2" placeholder="Where was this photo taken? What were you feeling?"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-romantic rounded-pill px-4">Upload Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold brand-font">New Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="folderCreateForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Folder Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Paris Trip 2026">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-romantic rounded-pill px-4">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
}
.cursor-pointer {
    cursor: pointer;
}
</style>
