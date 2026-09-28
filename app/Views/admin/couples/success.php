<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-lg rounded-4 text-center p-5">
            <div class="text-success fs-1 mb-2"><i class="fas fa-check-circle"></i></div>
            <h3 class="fw-bold mb-2">Couple Created Successfully!</h3>
            <p class="text-muted small mb-4">The private space and user accounts for <strong><?= e($creds['couple_name']); ?></strong> have been provisioned.</p>

            <div class="bg-light p-4 rounded-3 text-start mb-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Partner Credentials:</h6>
                
                <div class="mb-3">
                    <span class="badge bg-primary me-2">Partner 1</span>
                    <div><strong>Email:</strong> <code><?= e($creds['p1_email']); ?></code></div>
                    <div><strong>Password:</strong> <code><?= e($creds['p1_password']); ?></code></div>
                </div>

                <div class="mb-3">
                    <span class="badge bg-success me-2">Partner 2</span>
                    <div><strong>Email:</strong> <code><?= e($creds['p2_email']); ?></code></div>
                    <div><strong>Password:</strong> <code><?= e($creds['p2_password']); ?></code></div>
                </div>

                <div class="border-top pt-2">
                    <strong>Login URL:</strong> <a href="<?= e($creds['login_url']); ?>" target="_blank"><?= e($creds['login_url']); ?></a>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="<?= url('admin/couples'); ?>" class="btn btn-outline-secondary rounded-pill px-4">Back to Couples</a>
                <a href="<?= url('admin/couples/create'); ?>" class="btn btn-danger rounded-pill px-4">Create Another Couple</a>
            </div>
        </div>
    </div>
</div>
