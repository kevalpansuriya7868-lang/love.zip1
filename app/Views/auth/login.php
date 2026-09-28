<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Couples Private Space</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts for Gaming UI -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css'); ?>">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

<!-- Custom Cursor Elements -->
<div class="cursor-dot"></div>
<div class="cursor-outline"></div>

<!-- Background Animation Orbs -->
<div class="bg-animation">
    <div class="orb"></div>
    <div class="orb"></div>
    <div class="orb"></div>
    <div class="orb"></div>
</div>

<div class="container py-5 z-1 position-relative">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3 mb-3" style="background: rgba(0, 0, 0, 0.5); border: 1px solid rgba(0, 243, 255, 0.3); box-shadow: 0 0 20px rgba(0, 243, 255, 0.2);">
                    <i class="fas fa-heart text-danger fs-2" style="text-shadow: 0 0 15px var(--neon-secondary);"></i>
                </div>
                <h2 class="brand-font fw-bold mb-1">Our Private Corner</h2>
                <p class="text-muted small text-uppercase" style="letter-spacing: 1px;">Initialize Connection</p>
            </div>

            <div class="card-romantic p-4 p-md-5">
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger rounded-3 small py-2 mb-3 bg-dark border border-danger text-danger">
                        <i class="fas fa-exclamation-circle me-1"></i> <?= e($_SESSION['flash_error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success rounded-3 small py-2 mb-3 bg-dark border border-success text-success">
                        <i class="fas fa-check-circle me-1"></i> <?= e($_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= url('login'); ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

                    <div class="mb-3">
                        <label class="form-label">Username or Email</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="fas fa-user" style="color: var(--neon-primary);"></i></span>
                            <input type="text" name="email" class="form-control border-start-0 ps-0" placeholder="user1" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Password</label>
                            <a href="<?= url('forgot-password'); ?>" class="small text-decoration-none" style="color: var(--neon-secondary); text-shadow: 0 0 5px var(--neon-secondary);">Forgot?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="fas fa-lock" style="color: var(--neon-primary);"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-romantic w-100 py-2.5">
                        <i class="fas fa-sign-in-alt me-2"></i> Enter System
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top border-secondary">
                    <a href="<?= url('admin/login'); ?>" class="small text-muted text-decoration-none hover-neon">
                        <i class="fas fa-user-shield me-1"></i> Admin Override
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Custom Cursor
        const cursorDot = document.querySelector('.cursor-dot');
        const cursorOutline = document.querySelector('.cursor-outline');

        window.addEventListener('mousemove', (e) => {
            const posX = e.clientX;
            const posY = e.clientY;

            cursorDot.style.left = `${posX}px`;
            cursorDot.style.top = `${posY}px`;
            
            cursorOutline.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, { duration: 500, fill: "forwards" });
        });

        // Add hover effects for interactive elements to expand cursor
        const interactables = document.querySelectorAll('a, button, input');
        interactables.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorOutline.classList.add('cursor-hover');
                cursorDot.classList.add('cursor-hover');
            });
            el.addEventListener('mouseleave', () => {
                cursorOutline.classList.remove('cursor-hover');
                cursorDot.classList.remove('cursor-hover');
            });
        });

        // 3D Tilt Effect
        const cards = document.querySelectorAll('.card-romantic');
        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = ((y - centerY) / centerY) * -10; // Max 10 deg
                const rotateY = ((x - centerX) / centerX) * 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
            });
        });
    });
</script>

<script src="<?= asset('js/bg-animation.js'); ?>"></script>
</body>
</html>
