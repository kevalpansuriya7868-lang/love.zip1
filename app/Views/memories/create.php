<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card-romantic p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <h3 class="brand-font fw-bold mb-0" style="color: var(--burgundy-accent);">
                    <i class="fas fa-book-heart text-danger me-2"></i> Create Scrapbook Memory
                </h3>
                <a href="<?= url('memories'); ?>" class="btn btn-sm btn-outline-secondary rounded-pill">Cancel</a>
            </div>

            <form action="<?= url('memories/create'); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Memory Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. First Weekend Getaway in Paris" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Memory Date *</label>
                        <input type="date" name="memory_date" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Paris, France">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Description / Story</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Write down your feelings, funny stories, or details about this day..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Cover Photo</label>
                    <input type="file" name="cover_photo" class="form-control" accept="image/*">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-muted">Additional Photos (Multiple)</label>
                    <input type="file" name="additional_photos[]" class="form-control" accept="image/*" multiple>
                </div>

                <button type="submit" class="btn btn-romantic w-100 py-2.5">
                    <i class="fas fa-save me-2"></i> Save Memory to Scrapbook
                </button>
            </form>
        </div>
    </div>
</div>
