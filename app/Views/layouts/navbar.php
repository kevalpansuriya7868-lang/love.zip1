<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container">
        <a class="navbar-brand brand-font" href="<?= url('dashboard'); ?>">
            <i class="fas fa-heart text-danger"></i> Dharth
        </a>

        <?php if (is_logged_in()): ?>
            <div class="d-none d-md-flex align-items-center gap-3 desktop-nav-links">
                <a href="<?= url('dashboard'); ?>" class="nav-link <?= strpos($currentUri, '/dashboard') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-home me-1"></i> Home
                </a>
                <a href="<?= url('chat'); ?>" class="nav-link <?= strpos($currentUri, '/chat') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-comments me-1"></i> Chat
                </a>
                <a href="<?= url('photos'); ?>" class="nav-link <?= strpos($currentUri, '/photos') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-images me-1"></i> Photos
                </a>
                <a href="<?= url('memories'); ?>" class="nav-link <?= strpos($currentUri, '/memories') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-book-heart me-1"></i> Memories
                </a>
                <a href="<?= url('timeline'); ?>" class="nav-link <?= strpos($currentUri, '/timeline') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-stream me-1"></i> Timeline
                </a>
                <a href="<?= url('notifications'); ?>" class="nav-link position-relative <?= strpos($currentUri, '/notifications') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-bell"></i>
                    <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span>
                </a>

                <a href="<?= url('logout'); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 ms-2">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<?php if (is_logged_in()): ?>
<!-- Mobile Bottom Navigation Bar -->
<div class="mobile-bottom-nav">
    <div class="d-flex justify-content-around align-items-center w-100">
        <div class="nav-item">
            <a href="<?= url('dashboard'); ?>" class="nav-link <?= strpos($currentUri, '/dashboard') !== false ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= url('chat'); ?>" class="nav-link <?= strpos($currentUri, '/chat') !== false ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i>
                <span>Chat</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= url('photos'); ?>" class="nav-link <?= strpos($currentUri, '/photos') !== false ? 'active' : ''; ?>">
                <i class="fas fa-images"></i>
                <span>Photos</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= url('memories'); ?>" class="nav-link <?= strpos($currentUri, '/memories') !== false ? 'active' : ''; ?>">
                <i class="fas fa-book-heart"></i>
                <span>Memories</span>
            </a>
        </div>

    </div>
</div>
<?php endif; ?>
