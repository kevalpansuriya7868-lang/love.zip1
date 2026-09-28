<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Couples Private Space</title>
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
            <div class="card-romantic p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="brand-font fw-bold mb-2">Reset Sequence</h3>
                    <p class="text-muted small">Initialize connection reset via email link.</p>
                </div>

                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success rounded-3 small py-2 mb-3 bg-dark border border-success text-success">
                        <i class="fas fa-check-circle me-1"></i> <?= e($_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= url('forgot-password'); ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="fas fa-envelope" style="color: var(--neon-primary);"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="you@couple.local" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-romantic w-100 py-2.5">
                        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top border-secondary">
                    <a href="<?= url('login'); ?>" class="small text-muted text-decoration-none hover-neon">
                        <i class="fas fa-arrow-left me-1"></i> Back to Login
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
