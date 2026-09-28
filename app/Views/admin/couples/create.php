<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Create New Couple</h2>
            <a href="<?= url('admin/couples'); ?>" class="btn btn-outline-secondary">Back to Couples</a>
        </div>

        <form action="<?= url('admin/couples/create'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

            <!-- Section 1: Couple Details -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-danger">
                    <i class="fas fa-heart me-2"></i> 1. Couple Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Couple Name *</label>
                            <input type="text" name="couple_name" class="form-control" placeholder="e.g. Emma & Noah" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Relationship Start Date *</label>
                            <input type="date" name="relationship_start_date" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Couple Cover / Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status *</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Short Bio / Motto</label>
                            <textarea name="bio" class="form-control" rows="2" placeholder="e.g. Together since 2024. Writing our story step by step."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Partner 1 Details -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-primary">
                    <i class="fas fa-user me-2"></i> 2. Partner 1 Credentials & Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">First Name *</label>
                            <input type="text" name="p1_first_name" class="form-control" placeholder="Emma" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Last Name</label>
                            <input type="text" name="p1_last_name" class="form-control" placeholder="Watson">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email / Username *</label>
                            <input type="email" name="p1_email" class="form-control" placeholder="emma@couple.local" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password *</label>
                            <input type="password" name="p1_password" class="form-control" placeholder="••••••••" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Profile Photo</label>
                            <input type="file" name="p1_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Birthday (Optional)</label>
                            <input type="date" name="p1_birthday" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Partner 2 Details -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-success">
                    <i class="fas fa-user me-2"></i> 3. Partner 2 Credentials & Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">First Name *</label>
                            <input type="text" name="p2_first_name" class="form-control" placeholder="Noah" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Last Name</label>
                            <input type="text" name="p2_last_name" class="form-control" placeholder="Smith">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email / Username *</label>
                            <input type="email" name="p2_email" class="form-control" placeholder="noah@couple.local" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password *</label>
                            <input type="password" name="p2_password" class="form-control" placeholder="••••••••" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Profile Photo</label>
                            <input type="file" name="p2_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Birthday (Optional)</label>
                            <input type="date" name="p2_birthday" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid mb-5">
                <button type="submit" class="btn btn-danger btn-lg rounded-3 fw-bold">
                    <i class="fas fa-check-circle me-2"></i> Create Couple Space & Accounts
                </button>
            </div>
        </form>
    </div>
</div>
