<?php
$adminUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token(); ?>">
    <title><?= e($pageTitle ?? 'Admin Dashboard'); ?> — Couples Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts for Gaming UI -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Global Gaming UI Styles -->
    <link rel="stylesheet" href="<?= asset('css/style.css'); ?>?v=<?= time(); ?>">
    <!-- Admin Specific Styles -->
    <link rel="stylesheet" href="<?= asset('css/admin.css'); ?>?v=<?= time(); ?>">
</head>
<body class="admin-body d-flex min-vh-100 flex-column">

<!-- Custom Cursor Elements -->
<div class="cursor-dot"></div>
<div class="cursor-outline"></div>

<div class="container-fluid flex-grow-1">
    <div class="row">
        <!-- Admin Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
            <div class="position-sticky pt-3 px-3">
                <a href="<?= url('admin/dashboard'); ?>" class="d-flex align-items-center mb-4 text-white text-decoration-none fs-5 fw-bold">
                    <i class="fas fa-heart text-danger me-2"></i> Admin Panel
                </a>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($adminUri, '/admin/dashboard') !== false ? 'active' : ''; ?>" href="<?= url('admin/dashboard'); ?>">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($adminUri, '/admin/couples') !== false ? 'active' : ''; ?>" href="<?= url('admin/couples'); ?>">
                            <i class="fas fa-heart"></i> Manage Couples
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($adminUri, '/admin/users') !== false ? 'active' : ''; ?>" href="<?= url('admin/users'); ?>">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($adminUri, '/admin/messages') !== false ? 'active' : ''; ?>" href="<?= url('admin/messages'); ?>">
                            <i class="fas fa-comments"></i> Chat History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($adminUri, '/admin/photos') !== false ? 'active' : ''; ?>" href="<?= url('admin/photos'); ?>">
                            <i class="fas fa-images"></i> Manage Photos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($adminUri, '/admin/settings') !== false ? 'active' : ''; ?>" href="<?= url('admin/settings'); ?>">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($adminUri, '/admin/activity') !== false ? 'active' : ''; ?>" href="<?= url('admin/activity'); ?>">
                            <i class="fas fa-history"></i> Activity Logs
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="<?= url('admin/logout'); ?>">
                            <i class="fas fa-sign-out-alt"></i> Admin Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= e($_SESSION['flash_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= e($_SESSION['flash_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
