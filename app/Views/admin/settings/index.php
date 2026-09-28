<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">System Settings</h2>
        <p class="text-secondary small mb-0">Configure global website rules, message retention, file upload limits, and security.</p>
    </div>
</div>

<form action="<?= url('admin/settings'); ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

    <!-- General Settings -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-danger">
            <i class="fas fa-globe me-2"></i> General Website Configuration
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Website Name</label>
                    <input type="text" name="site_name" class="form-control" value="<?= e($settings['site_name'] ?? 'Couples Private Space'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Default Timezone</label>
                    <select name="timezone" class="form-select">
                        <option value="UTC" <?= ($settings['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : ''; ?>>UTC (Coordinated Universal Time)</option>
                        <option value="America/New_York" <?= ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : ''; ?>>America/New_York (EST)</option>
                        <option value="Europe/London" <?= ($settings['timezone'] ?? '') === 'Europe/London' ? 'selected' : ''; ?>>Europe/London (GMT)</option>
                        <option value="Asia/Kolkata" <?= ($settings['timezone'] ?? '') === 'Asia/Kolkata' ? 'selected' : ''; ?>>Asia/Kolkata (IST)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Maintenance Mode</label>
                    <select name="maintenance_mode" class="form-select">
                        <option value="disabled" <?= ($settings['maintenance_mode'] ?? 'disabled') === 'disabled' ? 'selected' : ''; ?>>Disabled (Normal Operation)</option>
                        <option value="enabled" <?= ($settings['maintenance_mode'] ?? '') === 'enabled' ? 'selected' : ''; ?>>Enabled (Maintenance Lockout)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat & Disappearing Messages Settings -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-primary">
            <i class="fas fa-comments me-2"></i> Chat & Disappearing Messages Configuration
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Automatic Message Retention Period</label>
                    <select name="message_retention" class="form-select">
                        <option value="never" <?= ($settings['message_retention'] ?? 'never') === 'never' ? 'selected' : ''; ?>>Never Automatically Delete</option>
                        <option value="24h" <?= ($settings['message_retention'] ?? '') === '24h' ? 'selected' : ''; ?>>Delete After 24 Hours</option>
                        <option value="7d" <?= ($settings['message_retention'] ?? '') === '7d' ? 'selected' : ''; ?>>Delete After 7 Days</option>
                        <option value="30d" <?= ($settings['message_retention'] ?? '') === '30d' ? 'selected' : ''; ?>>Delete After 30 Days</option>
                        <option value="custom" <?= ($settings['message_retention'] ?? '') === 'custom' ? 'selected' : ''; ?>>Custom Retention Period</option>
                    </select>
                    <div class="form-text small">Expired messages are permanently cleaned up by <code>cron/cleanup.php</code>.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Custom Retention Days (If Custom)</label>
                    <input type="number" name="message_retention_days" class="form-control" value="<?= e($settings['message_retention_days'] ?? '30'); ?>" min="1">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Allow User Message Deletion</label>
                    <select name="allow_user_message_delete" class="form-select">
                        <option value="1" <?= ($settings['allow_user_message_delete'] ?? '1') === '1' ? 'selected' : ''; ?>>Yes (Users can delete own messages)</option>
                        <option value="0" <?= ($settings['allow_user_message_delete'] ?? '') === '0' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Allow Photo Attachments in Chat</label>
                    <select name="allow_image_messages" class="form-select">
                        <option value="1" <?= ($settings['allow_image_messages'] ?? '1') === '1' ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?= ($settings['allow_image_messages'] ?? '') === '0' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Typing Indicator</label>
                    <select name="typing_indicator" class="form-select">
                        <option value="1" <?= ($settings['typing_indicator'] ?? '1') === '1' ? 'selected' : ''; ?>>Enabled</option>
                        <option value="0" <?= ($settings['typing_indicator'] ?? '') === '0' ? 'selected' : ''; ?>>Disabled</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo & Upload Limits -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-warning">
            <i class="fas fa-camera me-2"></i> Photo Upload & Gallery Configuration
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Maximum Upload File Size (MB)</label>
                    <input type="number" name="max_file_size_mb" class="form-control" value="<?= e($settings['max_file_size_mb'] ?? '10'); ?>" min="1" max="50">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Allowed Image Extensions</label>
                    <input type="text" name="allowed_file_types" class="form-control" value="<?= e($settings['allowed_file_types'] ?? 'jpg,jpeg,png,webp'); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Security & Rate Limiting -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0 fw-bold fs-5 text-dark">
            <i class="fas fa-shield-alt me-2"></i> Security & Session Controls
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Max Login Attempt Limit (Before 15 min lock)</label>
                    <input type="number" name="login_max_attempts" class="form-control" value="<?= e($settings['login_max_attempts'] ?? '5'); ?>" min="3" max="10">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Session Timeout (Minutes)</label>
                    <input type="number" name="session_timeout_minutes" class="form-control" value="<?= e($settings['session_timeout_minutes'] ?? '120'); ?>" min="15">
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid mb-5">
        <button type="submit" class="btn btn-danger btn-lg rounded-3 fw-bold">
            <i class="fas fa-save me-2"></i> Save System Configuration Settings
        </button>
    </div>
</form>
